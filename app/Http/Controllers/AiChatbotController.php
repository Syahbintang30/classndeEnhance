<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiChatbotService;

class AiChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(AiChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Handle incoming chat message request
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
        ]);

        $userMessage = trim($request->input('message'));
        $history = $request->input('history', []);

        $reply = $this->chatbotService->ask($userMessage, $history);

        return response()->json([
            'status' => 'success',
            'reply' => $reply,
        ]);
    }
}
