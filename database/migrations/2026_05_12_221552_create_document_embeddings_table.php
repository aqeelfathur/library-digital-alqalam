<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel document_embeddings — Vector Store untuk RAG.
 *
 * Setiap baris mewakili satu "chunk" dokumen yang sudah di-embed.
 * source_type: 'biblio' atau 'library_info'
 * source_id:   ID dari tabel sumber (biblio_id atau library_info.id)
 * chunk_text:  potongan teks yang digunakan saat embedding
 * embedding:   JSON array float (768 dimensi dari Gemini text-embedding-004)
 *
 * Kenapa JSON bukan binary?
 *   → Laravel/MySQL tidak punya tipe vector native (kecuali pakai plugin).
 *   → Untuk dataset kecil (<5000 dokumen), cosine similarity di PHP sudah cukup cepat.
 *   → Saat scale-up, tinggal migrasikan ke pgvector atau Milvus.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_embeddings', function (Blueprint $table) {
            $table->id();
            $table->string('source_type', 20)->comment('biblio | library_info');
            $table->unsignedBigInteger('source_id')->comment('ID dari tabel sumber');
            $table->text('chunk_text')->comment('Teks asli yang di-embed');
            $table->longText('embedding')->comment('JSON array float[] dari Gemini Embedding API');
            $table->timestamps();

            // Index untuk filter cepat berdasarkan source
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_embeddings');
    }
};