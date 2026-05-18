<?php

namespace App\Http\Requests\Buku;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isPustakawan();
    }

    public function rules(): array
    {
        return [
            'category_id'          => ['required', 'exists:categories,id'],
            'title'                => ['required', 'string', 'max:255'],
            'author'               => ['required', 'string', 'max:255'],
            'publisher'            => ['nullable', 'string', 'max:255'],
            'edition'              => ['nullable', 'string', 'max:50'],
            'language'             => ['nullable', 'string', 'max:50'],
            'isbn_issn'            => ['nullable', 'string', 'max:50'],
            'call_number'          => ['nullable', 'string', 'max:100'],
            'classification'       => ['nullable', 'string', 'max:100'],
            'series_title'         => ['nullable', 'string', 'max:255'],
            'subject'              => ['nullable', 'string', 'max:255'],
            'physical_description' => ['nullable', 'string', 'max:255'],
            'content_type'         => ['nullable', 'string', 'max:100'],
            'media_type'           => ['nullable', 'string', 'max:100'],
            'carrier_type'         => ['nullable', 'string', 'max:100'],
            'specific_detail_info' => ['nullable', 'string'],
            'status'               => ['required', 'in:tersedia,dipinjam,maintenance,hilang'],
            'image_url'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak valid.',
            'title.required'       => 'Judul buku wajib diisi.',
            'author.required'      => 'Nama penulis wajib diisi.',
            'status.required'      => 'Status buku wajib dipilih.',
            'status.in'            => 'Status buku tidak valid.',
            'image_url.image'      => 'File harus berupa gambar.',
            'image_url.max'        => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}