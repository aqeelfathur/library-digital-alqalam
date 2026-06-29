<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gemini API Configuration
    |--------------------------------------------------------------------------
    | Semua konfigurasi Gemini diambil dari .env agar mudah diganti
    | tanpa menyentuh kode.
    */

    'api_key' => env('GEMINI_API_KEY', ''),

    // Model untuk generate teks (chat)
    'model' => env('GEMINI_MODEL', 'gemini-flash-latest'),

    // Model untuk embedding vektor
    'embedding_model' => env('GEMINI_EMBEDDING_MODEL', 'gemini-embedding-001'),

    // Base URL Gemini REST API
    'base_url' => 'https://generativelanguage.googleapis.com/v1beta',

    // Batas token output dari LLM
    'max_output_tokens' => 1024,

    // Jumlah dokumen konteks yang diambil dari vector store untuk RAG
    'rag_top_k' => 3,

    // Threshold minimum cosine similarity (0.0–1.0)
    // Di bawah nilai ini, dokumen dianggap tidak relevan
    'similarity_threshold' => 0.55,

    'embedding_driver'       => env('EMBEDDING_DRIVER', 'gemini'),
    'ollama_url'             => env('OLLAMA_URL', 'http://localhost:11434'),
    'ollama_embedding_model' => env('OLLAMA_EMBEDDING_MODEL', 'bge-m3'),

    'chat_driver'       => env('CHAT_DRIVER', 'gemini'),
    'ollama_chat_model' => env('OLLAMA_CHAT_MODEL', 'qwen2.5:3b-instruct'),

];