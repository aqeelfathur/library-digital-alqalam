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

    public function __construct()
    {
        $this->apiKey          = config('gemini.api_key');
        $this->model           = config('gemini.model');
        $this->embeddingModel  = config('gemini.embedding_model');
        $this->baseUrl         = config('gemini.base_url');
        $this->maxOutputTokens = config('gemini.max_output_tokens');
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
        $url = "{$this->baseUrl}/models/{$this->embeddingModel}:embedContent?key={$this->apiKey}";

        // Potong teks jika terlalu panjang (Gemini embedding max ~2048 token)
        $text = mb_substr($text, 0, 8000);

        $payload = [
            'model'   => "models/{$this->embeddingModel}",
            'content' => [
                'parts' => [['text' => $text]],
            ],
            'taskType' => $taskType,
        ];

        $response = Http::timeout(30)
            ->post($url, $payload);

        if ($response->failed()) {
            $error = $response->json('error.message', 'Unknown error');
            Log::error('[GeminiService] embedText failed', [
                'status'  => $response->status(),
                'message' => $error,
                'text'    => mb_substr($text, 0, 100) . '...',
            ]);
            throw new Exception("Gemini Embedding API error: {$error}");
        }

        $values = $response->json('embedding.values');

        if (empty($values)) {
            throw new Exception('Gemini embedding returned empty vector.');
        }

        return $values; // float[] dengan 768 dimensi
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