<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Services\ChatbotService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Xử lý tin nhắn chatbot
     */
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'history' => 'nullable|array',
                'history.*.role' => 'required_with:history|string|in:user,model',
                'history.*.text' => 'required_with:history|string',
            ]);

            $userMessage = $request->input('message');
            $history = $request->input('history', []);

            $result = $this->chatbotService->sendMessage($userMessage, $history);

            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tin nhắn không hợp lệ. Vui lòng thử lại! 🙏'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ChatbotController error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau! 🙏'
            ]);
        }
    }
}
