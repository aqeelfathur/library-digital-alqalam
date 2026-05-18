<?php

namespace App\Http\Requests\Berita;

use Illuminate\Foundation\Http\FormRequest;

class StoreBeritaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isPustakawan();
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Judul berita wajib diisi.',
            'content.required' => 'Isi berita wajib diisi.',
        ];
    }
}