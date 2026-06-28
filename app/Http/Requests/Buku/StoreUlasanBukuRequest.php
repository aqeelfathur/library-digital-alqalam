<?php

namespace App\Http\Requests\Buku;

use Illuminate\Foundation\Http\FormRequest;

class StoreUlasanBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAnggota() && $this->user()?->isAktif();
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Pilih rating bintang terlebih dahulu.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'comment.required' => 'Komentar ulasan wajib diisi.',
            'comment.max' => 'Komentar ulasan maksimal 1000 karakter.',
        ];
    }
}
