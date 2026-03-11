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
        'gemini-2.5-flash',
        'gemini-2.0-flash-lite',
        'gemini-2.0-flash',
    ];

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
        ]);
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

            $context = "DANH SÁCH CÁC TOUR HIỆN CÓ TRÊN HỆ THỐNG TRAVELA:\n\n";
            foreach ($tours as $tour) {
                $domain = match($tour->domain ?? '') {
                    'b' => 'Miền Bắc',
                    't' => 'Miền Trung',
                    'n' => 'Miền Nam',
                    default => 'Khác'
                };
                $context .= "- Tour: {$tour->title}\n";
                $context .= "  Giá người lớn: " . number_format($tour->priceAdult, 0, ',', '.') . " VND\n";
                $context .= "  Giá trẻ em: " . number_format($tour->priceChild, 0, ',', '.') . " VND\n";
                $context .= "  Thời gian: {$tour->time}\n";
                $context .= "  Điểm đến: {$tour->destination}\n";
                $context .= "  Khu vực: {$domain}\n\n";
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
Bạn là "Trợ lý du lịch Travela" - một trợ lý AI thông minh và thân thiện của website du lịch Travela. 

NHIỆM VỤ CỦA BẠN:
1. Tư vấn và giới thiệu các tour du lịch Việt Nam đang có trên hệ thống Travela
2. Trả lời câu hỏi về điểm đến, lịch trình, giá cả các tour
3. Gợi ý tour phù hợp dựa trên yêu cầu của khách hàng (ngân sách, khu vực, thời gian)
4. Cung cấp thông tin hữu ích về du lịch Việt Nam (thời tiết, văn hóa, ẩm thực, mẹo du lịch)
5. Hướng dẫn cách đặt tour trên website

QUY TẮC:
- Luôn trả lời bằng tiếng Việt
- Thân thiện, nhiệt tình, chuyên nghiệp
- Khi gợi ý tour, hãy dựa trên dữ liệu tour thực bên dưới
- Nếu khách hỏi về tour cụ thể, hãy gợi ý khách truy cập trang Tours để xem chi tiết
- Trả lời ngắn gọn, rõ ràng, có cấu trúc
- Sử dụng emoji phù hợp để tạo sự thân thiện
- Nếu không biết câu trả lời, hãy thành thật nói rằng bạn không chắc và gợi ý khách liên hệ hotline: 0342589281

DỮ LIỆU TOUR:
{$toursContext}
PROMPT;
    }

    /**
     * Gửi tin nhắn đến Gemini API - thử nhiều model
     */
    public function sendMessage(string $userMessage, array $conversationHistory = [])
    {
        foreach ($this->models as $model) {
            $result = $this->tryModel($model, $userMessage, $conversationHistory);
            if ($result['success']) {
                return $result;
            }
            Log::info("Model {$model} failed, trying next...");
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
                $contents[] = [
                    'role' => $msg['role'],
                    'parts' => [
                        ['text' => $msg['text']]
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

            return [
                'success' => false,
                'message' => 'Xin lỗi, tôi không thể xử lý yêu cầu lúc này. 🙏'
            ];

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $status = $e->getResponse()->getStatusCode();
            $body = $e->getResponse()->getBody()->getContents();
            Log::error("Gemini API ClientError [{$model}]", ['status' => $status, 'body' => $body]);

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

