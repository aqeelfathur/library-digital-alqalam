<?php

namespace App\Http\Controllers;

use App\Services\RagService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * ChatbotController — Endpoint API untuk SIPUS chatbot widget.
 *
 * Di project Al-Qalam, chatbot tidak punya halaman tersendiri —
 * ia muncul sebagai floating widget di semua halaman via Blade component.
 *
 * Route:
 *   POST /sipus/pesan  → proses pesan user, return JSON
 */
class ChatbotController extends Controller
{
    public function __construct(
        private RagService $rag
    ) {}

    /**
     * Proses pesan dari widget chatbot.
     *
     * Request JSON:
     * {
     *   "message": "Jam buka perpustakaan?",
     *   "history": [...]   // opsional
     * }
     *
     * Response JSON:
     * {
     *   "success": true,
     *   "answer": "Perpustakaan buka jam 07.00..."
     * }
     */
    public function kirimPesan(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message'         => 'required|string|min:1|max:1000',
            'history'         => 'nullable|array|max:20',
            'history.*.role' => 'required|in:user,model',
            'history.*.text' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error'   => 'Input tidak valid: ' . $validator->errors()->first(),
            ], 422);
        }

        $userMessage = trim($request->input('message'));
        $history     = $request->input('history', []);

        try {
            $result = $this->rag->chat($userMessage, $history);

            return response()->json([
                'success' => true,
                'answer'  => $result['answer'],
            ]);

        } catch (\Exception $e) {
            Log::error('[ChatbotController] kirimPesan error', [
                'message' => $e->getMessage(),
                'user'    => mb_substr($userMessage, 0, 100),
            ]);

            return response()->json([
                'success' => false,
                'error'   => 'Maaf, terjadi kesalahan. Silakan coba lagi.',
            ], 500);
        }
    }
}