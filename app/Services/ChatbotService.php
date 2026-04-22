<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class ChatbotService
{
    protected $apiKey;
    protected $client;
    protected $models = [
        'gemini-2.0-flash',
        'gemini-2.0-flash-lite',
        'gemini-2.5-flash',
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
        ]);

        if (empty($this->apiKey)) {
            Log::error('ChatbotService: GEMINI_API_KEY is not set in .env');
        }
    }

    /**
     * Lấy danh sách tours từ DB để đưa vào context cho AI
     */
    protected function getToursContext()
    {
        try {
            $tours = DB::table('tbl_tours')
                ->where('availability', 1)
                ->select('tourId', 'title', 'description', 'priceAdult', 'priceChild', 'time', 'destination', 'startDate', 'endDate', 'quantity', 'domain')
                ->get();

            if ($tours->isEmpty()) {
                return "Hiện tại chưa có tour nào trong hệ thống.";
            }

            $baseUrl = config('app.url');
            $context = "DANH SÁCH TOUR THỰC TẾ TRÊN HỆ THỐNG TRAVELA (chỉ được dùng những tour này, KHÔNG được bịa):\n\n";
            foreach ($tours as $tour) {
                $domain = match($tour->domain ?? '') {
                    'b' => 'Miền Bắc',
                    't' => 'Miền Trung',
                    'n' => 'Miền Nam',
                    default => 'Khác'
                };
                $detailUrl = $baseUrl . '/tour/' . $tour->tourId;
                $context .= "---\n";
                $context .= "TourID: {$tour->tourId}\n";
                $context .= "Tên tour: {$tour->title}\n";
                $context .= "Giá người lớn: " . number_format($tour->priceAdult, 0, ',', '.') . " VND\n";
                $context .= "Giá trẻ em: " . number_format($tour->priceChild, 0, ',', '.') . " VND\n";
                $context .= "Thời gian: {$tour->time}\n";
                $context .= "Điểm đến: {$tour->destination}\n";
                $context .= "Khu vực: {$domain}\n";
                $context .= "Link chi tiết: {$detailUrl}\n\n";
            }

            return $context;
        } catch (\Exception $e) {
            Log::error('Error getting tours context: ' . $e->getMessage());
            return "Không thể tải dữ liệu tour.";
        }
    }

    /**
     * Tạo system prompt cho AI
     */
    protected function getSystemPrompt()
    {
        $toursContext = $this->getToursContext();

        return <<<PROMPT
Bạn là "Trợ lý du lịch Travela" - trợ lý AI của website du lịch Travela.

NHIỆM VỤ:
1. Tư vấn và giới thiệu tour du lịch Việt Nam dựa HOÀN TOÀN vào dữ liệu thực tế bên dưới
2. Lọc và gợi ý tour phù hợp với yêu cầu khách hàng (ngân sách, thời gian, khu vực, điểm đến)
3. Cung cấp link xem chi tiết từng tour để khách có thể click vào
4. Hướng dẫn cách đặt tour trên website

QUY TẮC BẮT BUỘC:
- Luôn trả lời bằng tiếng Việt, thân thiện, dùng emoji phù hợp
- **CHỈ được giới thiệu các tour có trong danh sách dữ liệu bên dưới**
- **TUYỆT ĐỐI KHÔNG được bịa ra tên tour, giá, địa điểm không có trong dữ liệu**
- Khi khách hỏi tour theo giá (ví dụ "dưới 5 triệu"), hãy lọc và liệt kê ĐÚNG những tour trong dữ liệu có giá phù hợp
- Khi khách hỏi theo thời gian (ví dụ "3 ngày 2 đêm"), hãy liệt kê ĐÚNG những tour trong dữ liệu có trường "Thời gian" phù hợp
- Khi liệt kê tour, luôn kèm link chi tiết dạng: [Xem chi tiết](link_chi_tiet)
- Nếu không có tour phù hợp trong dữ liệu, hãy thành thật nói "Hiện tại chúng tôi chưa có tour phù hợp" và gợi ý các tour gần nhất
- Nếu không biết câu trả lời, gợi ý khách liên hệ hotline: 0342589281

DỮ LIỆU TOUR THỰC TẾ:
{$toursContext}
PROMPT;
    }

    /**
     * Gửi tin nhắn đến Gemini API - thử nhiều model
     */
    public function sendMessage(string $userMessage, array $conversationHistory = [])
    {
        Log::info("ChatbotService::sendMessage started. API Key: " . substr($this->apiKey, 0, 5) . "... userMessage: {$userMessage}");
        if (empty($this->apiKey)) {
            Log::error('ChatbotService: Cannot send message - GEMINI_API_KEY is not configured');
            return [
                'success' => false,
                'message' => 'Xin lỗi, hệ thống chưa được cấu hình. Vui lòng liên hệ quản trị viên. 🙏'
            ];
        }

        $wasRateLimited = false;

        foreach ($this->models as $model) {
            Log::info("Trying model: {$model}");
            $result = $this->tryModel($model, $userMessage, $conversationHistory);
            
            if ($result['success']) {
                Log::info("Model {$model} succeeded!");
                return $result;
            }
            
            if (!empty($result['rate_limited'])) {
                Log::warning("Model {$model} rate limited (429).");
                $wasRateLimited = true;
            }
            
            Log::warning("Chatbot: Model {$model} failed, trying next...", [
                'message_preview' => substr($userMessage, 0, 50)
            ]);
        }

        Log::error("All models failed. wasRateLimited: " . ($wasRateLimited ? 'true' : 'false'));

        if ($wasRateLimited) {
            return [
                'success' => false,
                'message' => 'Hệ thống đang nhận quá nhiều yêu cầu. Vui lòng đợi khoảng 30 giây rồi thử lại nhé! ⏳'
            ];
        }

        return [
            'success' => false,
            'message' => 'Xin lỗi, hệ thống đang gặp sự cố. Vui lòng thử lại sau hoặc liên hệ hotline: 0342589281 🙏'
        ];
    }

    /**
     * Thử gọi API với một model cụ thể
     */
    protected function tryModel(string $model, string $userMessage, array $conversationHistory = [])
    {
        try {
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}";

            // Xây dựng contents
            $contents = [];

            foreach ($conversationHistory as $msg) {
                // Ensure text is clean
                $text = is_string($msg['text']) ? $msg['text'] : json_encode($msg['text']);
                $contents[] = [
                    'role' => $msg['role'],
                    'parts' => [
                        ['text' => $text]
                    ]
                ];
            }

            $contents[] = [
                'role' => 'user',
                'parts' => [
                    ['text' => $userMessage]
                ]
            ];

            $requestBody = [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $this->getSystemPrompt()]
                    ]
                ],
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topP' => 0.95,
                    'topK' => 40,
                    'maxOutputTokens' => 1024,
                ]
            ];

            $response = $this->client->post($apiUrl, [
                'json' => $requestBody,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return [
                    'success' => true,
                    'message' => $data['candidates'][0]['content']['parts'][0]['text']
                ];
            }

            Log::error("Gemini API missing text in response [{$model}]");
            return [
                'success' => false,
                'message' => 'Xin lỗi, tôi không thể xử lý yêu cầu lúc này. 🙏'
            ];

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $status = $e->getResponse()->getStatusCode();
            $body = $e->getResponse()->getBody()->getContents();
            Log::error("Gemini API ClientError [{$model}] status: {$status}, body: " . substr($body, 0, 300));

            // Rate limit - trả về thông báo rõ ràng, không cần thử model khác
            if ($status === 429) {
                return [
                    'success' => false,
                    'message' => 'Hệ thống đang nhận quá nhiều yêu cầu. Vui lòng đợi khoảng 30 giây rồi thử lại nhé! ⏳',
                    'rate_limited' => true,
                ];
            }

            return [
                'success' => false,
                'message' => ''
            ];
        } catch (\Exception $e) {
            Log::error("Chatbot Exception [{$model}]: " . $e->getMessage());

            return [
                'success' => false,
                'message' => ''
            ];
        }
    }
}

