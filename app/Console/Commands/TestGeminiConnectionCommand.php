<?php

namespace App\Console\Commands;

use App\Services\GeminiService;
use Illuminate\Console\Command;

/**
 * Command: php artisan chatbot:test-connection
 *
 * Test apakah API key Gemini valid dan koneksi berjalan normal.
 * Jalankan ini SEBELUM php artisan chatbot:embed untuk memastikan
 * tidak ada masalah konfigurasi.
 */
class TestGeminiConnectionCommand extends Command
{
    protected $signature   = 'chatbot:test-connection';
    protected $description = 'Test koneksi ke Gemini API';

    public function __construct(
        private GeminiService $gemini
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('');
        $this->info('🔍 Testing koneksi ke Gemini API...');
        $this->info('   API Key: ' . $this->maskKey(config('gemini.api_key')));
        $this->info('   Model  : ' . config('gemini.model'));
        $this->info('');

        // Test 1: Generate text
        $this->line('Test 1/2 — Generate Text...');
        $result = $this->gemini->testConnection();

        if ($result['success']) {
            $this->info('   ✅ ' . $result['message']);
        } else {
            $this->error('   ❌ ' . $result['message']);
            return Command::FAILURE;
        }

        // Test 2: Embedding
        $this->line('');
        $this->line('Test 2/2 — Text Embedding...');
        try {
            $vector = $this->gemini->embedText('Tes embedding buku perpustakaan');
            $this->info('   ✅ Embedding berhasil! Dimensi vektor: ' . count($vector));
            $this->info('   Contoh 3 nilai pertama: [' .
                round($vector[0], 6) . ', ' .
                round($vector[1], 6) . ', ' .
                round($vector[2], 6) . ', ...]');
        } catch (\Exception $e) {
            $this->error('   ❌ ' . $e->getMessage());
            return Command::FAILURE;
        }

        $this->info('');
        $this->info('🎉 Semua test berhasil! Kamu bisa jalankan:');
        $this->info('   php artisan chatbot:embed');

        return Command::SUCCESS;
    }

    private function maskKey(string $key): string
    {
        if (strlen($key) < 8) return '(tidak diset)';
        return substr($key, 0, 6) . '...' . substr($key, -4);
    }
}