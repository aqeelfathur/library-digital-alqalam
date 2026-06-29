<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * GeminiService — Wrapper untuk Gemini REST API.
 *
 * Tanggung jawab:
 *   1. generateText()  → kirim prompt ke Gemini, terima teks jawaban
 *   2. embedText()     → ubah teks menjadi vektor float[] (untuk RAG)
 *
 * Semua konfigurasi (API key, model, base URL) diambil dari config/gemini.php
 */
class GeminiService
{
    private string $apiKey;
    private string $model;
    private string $embeddingModel;
    private string $baseUrl;
    private int    $maxOutputTokens;
    private string $embeddingDriver;
    private string $ollamaUrl;
    private string $ollamaEmbeddingModel;
    private string $chatDriver;
    private string $ollamaChatModel;

    public function __construct()
    {
        $this->apiKey          = config('gemini.api_key');
        $this->model           = config('gemini.model');
        $this->embeddingModel  = config('gemini.embedding_model');
        $this->baseUrl         = config('gemini.base_url');
        $this->maxOutputTokens = config('gemini.max_output_tokens');
        $this->embeddingDriver      = config('gemini.embedding_driver', 'gemini');
        $this->ollamaUrl            = config('gemini.ollama_url', 'http://localhost:11434');
        $this->ollamaEmbeddingModel = config('gemini.ollama_embedding_model', 'bge-m3');
        $this->chatDriver      = config('gemini.chat_driver', 'gemini');
        $this->ollamaChatModel = config('gemini.ollama_chat_model', 'qwen2.5:3b-instruct');
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Generate teks (chat)
    // -------------------------------------------------------------------------

    /**
     * Kirim prompt ke Gemini dan dapatkan jawaban teks.
     *
     * @param  string  $systemPrompt  Instruksi sistem (peran chatbot)
     * @param  array   $history       Riwayat percakapan [['role'=>'user'|'model', 'text'=>'...']]
     * @param  string  $userMessage   Pesan terbaru dari user
     * @return string  Jawaban dari Gemini
     *
     * @throws Exception
     */
        public function generateText(string $systemPrompt, array $history, string $userMessage): string
    {
        return $this->chatDriver === 'ollama'
            ? $this->generateViaOllama($systemPrompt, $history, $userMessage)
            : $this->generateViaGemini($systemPrompt, $history, $userMessage);
    }

    public function generateViaGemini(string $systemPrompt, array $history, string $userMessage): string
    {
        $url = "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}";

        // Bangun array "contents" dari history + pesan terbaru
        $contents = [];

        foreach ($history as $turn) {
            $contents[] = [
                'role'  => $turn['role'], // 'user' atau 'model'
                'parts' => [['text' => $turn['text']]],
            ];
        }

        // Tambahkan pesan user saat ini
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $userMessage]],
        ];

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents'           => $contents,
            'generationConfig'   => [
                'maxOutputTokens' => $this->maxOutputTokens,
                'temperature'     => 0.3, // Rendah = lebih faktual, cocok untuk chatbot perpustakaan
                'topP'            => 0.8,
            ],
        ];

        $response = Http::timeout(30)
            ->retry(3, fn ($attempt) => $attempt * 1000, function ($e) {
                return $e instanceof \Illuminate\Http\Client\ConnectionException
                    || in_array(optional($e->response)->status(), [429, 503], true);
            }, throw: false)
            ->post($url, $payload);

        if ($response->failed()) {
            $error = $response->json('error.message', 'Unknown error');
            Log::error('[GeminiService] generateText failed', [
                'status'  => $response->status(),
                'message' => $error,
            ]);
            throw new Exception("Gemini API error: {$error}");
        }

        $text = $response->json('candidates.0.content.parts.0.text', '');

        if (empty($text)) {
            Log::warning('[GeminiService] generateText returned empty response', [
                'response' => $response->json(),
            ]);
            return 'Maaf, saya tidak dapat memberikan jawaban saat ini. Silakan coba lagi.';
        }

        return trim($text);
    }

    private function generateViaOllama(string $systemPrompt, array $history, string $userMessage): string
    {
        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($history as $turn) {
            $messages[] = [
                'role'    => ($turn['role'] === 'model') ? 'assistant' : 'user',
                'content' => $turn['text'],
            ];
        }
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        $response = Http::timeout(180)
            ->retry(1, 2000, fn ($e) => $e instanceof \Illuminate\Http\Client\ConnectionException, throw: false)
            ->post(rtrim($this->ollamaUrl, '/') . '/api/chat', [
                'model'      => $this->ollamaChatModel,
                'messages'   => $messages,
                'stream'     => false,
                'keep_alive' => '30m',   // model tetap di RAM antar-permintaan
                'options'    => [
                    'temperature' => 0.3,
                    'top_p'       => 0.8,
                    'num_predict' => $this->maxOutputTokens,
                ],
            ]);

        if ($response->failed()) {
            $error = $response->json('error', 'Unknown error');
            Log::error('[GeminiService] generateViaOllama failed', [
                'status' => $response->status(), 'message' => $error,
            ]);
            throw new Exception("Ollama chat error: {$error}. Pastikan Ollama jalan & model '{$this->ollamaChatModel}' sudah di-pull.");
        }

        $text = $response->json('message.content', '');
        if (empty($text)) {
            return 'Maaf, saya tidak dapat memberikan jawaban saat ini. Silakan coba lagi.';
        }
        return trim($text);
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Embed teks → vektor float[]
    // -------------------------------------------------------------------------

    /**
     * Ubah teks menjadi vektor embedding (float array 768 dimensi).
     *
     * @param  string  $text  Teks yang akan di-embed
     * @param  string  $taskType
     *   - 'RETRIEVAL_DOCUMENT' → untuk dokumen yang disimpan di vector store
     *   - 'RETRIEVAL_QUERY'    → untuk query user saat pencarian
     * @return float[]  Array vektor 768 dimensi
     *
     * @throws Exception
     */
    public function embedText(string $text, string $taskType = 'RETRIEVAL_DOCUMENT'): array
    {
        $text = mb_substr($text, 0, 8000);

        return $this->embeddingDriver === 'ollama'
            ? $this->embedViaOllama($text)
            : $this->embedViaGemini($text, $taskType);
    }

    private function embedViaGemini(string $text, string $taskType): array
    {
        $url = "{$this->baseUrl}/models/{$this->embeddingModel}:embedContent?key={$this->apiKey}";

        $payload = [
            'model'    => "models/{$this->embeddingModel}",
            'content'  => ['parts' => [['text' => $text]]],
            'taskType' => $taskType,
        ];

        $response = Http::timeout(30)
            ->retry(3, fn ($attempt) => $attempt * 1000, function ($e) {
                return $e instanceof \Illuminate\Http\Client\ConnectionException
                    || in_array(optional($e->response)->status(), [429, 503], true);
            }, throw: false)
            ->post($url, $payload);

        if ($response->failed()) {
            $error = $response->json('error.message', 'Unknown error');
            Log::error('[GeminiService] embedViaGemini failed', [
                'status' => $response->status(), 'message' => $error,
                'text'   => mb_substr($text, 0, 100) . '...',
            ]);
            throw new Exception("Gemini Embedding API error: {$error}");
        }

        $values = $response->json('embedding.values');
        if (empty($values)) {
            throw new Exception('Gemini embedding returned empty vector.');
        }
        return $values;
    }

    private function embedViaOllama(string $text): array
    {
        $response = Http::timeout(60)
            ->retry(2, 1000, fn ($e) => $e instanceof \Illuminate\Http\Client\ConnectionException, throw: false)
            ->post(rtrim($this->ollamaUrl, '/') . '/api/embed', [
                'model' => $this->ollamaEmbeddingModel,
                'input' => $text,
            ]);

        if ($response->failed()) {
            $error = $response->json('error', 'Unknown error');
            Log::error('[GeminiService] embedViaOllama failed', [
                'status' => $response->status(), 'message' => $error,
                'text'   => mb_substr($text, 0, 100) . '...',
            ]);
            throw new Exception("Ollama Embedding error: {$error}. Pastikan Ollama jalan & model '{$this->ollamaEmbeddingModel}' sudah di-pull.");
        }

        $values = $response->json('embeddings.0');
        if (empty($values)) {
            throw new Exception('Ollama embedding returned empty vector.');
        }
        return $values; // bge-m3 → 1024 dimensi
    }

    // -------------------------------------------------------------------------
    // PUBLIC: Test koneksi API
    // -------------------------------------------------------------------------

    /**
     * Test apakah API key valid dengan request sederhana.
     *
     * @return array ['success' => bool, 'message' => string]
     */
    public function testConnection(): array
    {
        try {
            $result = $this->generateText(
                'Kamu adalah asisten test.',
                [],
                'Balas hanya dengan kata: OK'
            );

            return [
                'success' => true,
                'message' => "Koneksi berhasil. Response: {$result}",
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}