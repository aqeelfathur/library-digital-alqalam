<?php

namespace App\Console\Commands;

use App\Services\EmbeddingService;
use Illuminate\Console\Command;
use Exception;

/**
 * Command: php artisan chatbot:embed
 *
 * Menjalankan proses embedding seluruh dokumen (buku + info perpustakaan)
 * ke tabel document_embeddings (vector store).
 *
 * Kapan dijalankan?
 *   - Pertama kali setup project
 *   - Setelah data buku/info diperbarui secara massal
 *   - Setelah swap dari dummy data ke data real SLiMS
 *
 * Durasi estimasi (free tier Gemini, ~22 dokumen):
 *   ~30–60 detik (ada jeda 0.2 detik per dokumen untuk menghindari rate limit)
 */
class EmbedDocumentsCommand extends Command
{
    protected $signature   = 'chatbot:embed
                              {--fresh : Hapus semua embedding lama sebelum proses (default: ya)}';

    protected $description = 'Embed semua dokumen perpustakaan ke vector store untuk RAG chatbot';

    public function __construct(
        private EmbeddingService $embeddingService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════════╗');
        $this->info('║   Chatbot RAG — Document Embedding Process   ║');
        $this->info('╚══════════════════════════════════════════════╝');
        $this->info('');
        $this->info('Model  : ' . config('gemini.embedding_model'));
        $this->info('Dimensi: 3071 (gemini-embedding-001)');
        $this->info('');

        if (!config('gemini.api_key')) {
            $this->error('❌ GEMINI_API_KEY belum dikonfigurasi di .env!');
            $this->line('   Tambahkan: GEMINI_API_KEY=AIza...');
            return Command::FAILURE;
        }

        $this->warn('⚠️  Proses ini akan menghapus semua embedding lama dan membuat ulang.');

        if (!$this->confirm('Lanjutkan?', true)) {
            $this->info('Dibatalkan.');
            return Command::SUCCESS;
        }

        $startTime = microtime(true);

        try {
            $stats = $this->embeddingService->embedAllDocuments(
                progress: fn(string $msg) => $this->line($msg)
            );

            $elapsed = round(microtime(true) - $startTime, 1);

            $this->info('');
            $this->info('╔══════════════════════════════════════════════╗');
            $this->info('║              Proses Selesai! ✅              ║');
            $this->info('╚══════════════════════════════════════════════╝');
            $this->table(
                ['Kategori', 'Jumlah Berhasil'],
                [
                    ['Buku (biblio)',          $stats['biblio']],
                    ['Info Perpustakaan',      $stats['library_info']],
                    ['Error',                  $stats['errors']],
                    ['Total Dokumen di Store', $stats['biblio'] + $stats['library_info']],
                ]
            );
            $this->info("⏱️  Waktu: {$elapsed} detik");
            $this->info('');
            $this->info('✅ Vector store siap! Chatbot dapat digunakan.');

            if ($stats['errors'] > 0) {
                $this->warn("⚠️  Ada {$stats['errors']} error. Cek log: storage/logs/laravel.log");
            }

        } catch (Exception $e) {
            $this->error('❌ Proses gagal: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}