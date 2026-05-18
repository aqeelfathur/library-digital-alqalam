<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FileUploadService
{
    private const TIPE_GAMBAR_DIIZINKAN = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private const EKSTENSI_DIIZINKAN   = ['jpg', 'jpeg', 'png', 'webp'];
    private const UKURAN_MAKS_BYTES    = 2 * 1024 * 1024; // 2MB

    public function simpanSampulBuku(UploadedFile $file): string
    {
        $this->validasiFile($file);
        return $this->simpan($file, 'sampul-buku');
    }

    public function simpanFotoPengguna(UploadedFile $file): string
    {
        $this->validasiFile($file, 1 * 1024 * 1024); // 1MB untuk foto profil
        return $this->simpan($file, 'foto-pengguna');
    }

    public function simpanThumbnailBerita(UploadedFile $file): string
    {
        $this->validasiFile($file);
        return $this->simpan($file, 'thumbnail-berita');
    }

    public function hapus(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function ganti(UploadedFile $fileBaru, ?string $pathLama, string $folder): string
    {
        $this->hapus($pathLama);
        return $this->simpan($fileBaru, $folder);
    }

    private function simpan(UploadedFile $file, string $folder): string
    {
        $namaFile = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $namaFile, 'public');
    }

    private function validasiFile(UploadedFile $file, int $ukuranMaks = self::UKURAN_MAKS_BYTES): void
    {
        if (!in_array($file->getMimeType(), self::TIPE_GAMBAR_DIIZINKAN)) {
            throw ValidationException::withMessages([
                'file' => 'Tipe file tidak diizinkan. Gunakan JPEG, PNG, atau WebP.',
            ]);
        }

        if (!in_array(strtolower($file->getClientOriginalExtension()), self::EKSTENSI_DIIZINKAN)) {
            throw ValidationException::withMessages([
                'file' => 'Ekstensi file tidak diizinkan.',
            ]);
        }

        if ($file->getSize() > $ukuranMaks) {
            $ukuranMb = $ukuranMaks / (1024 * 1024);
            throw ValidationException::withMessages([
                'file' => "Ukuran file melebihi batas {$ukuranMb}MB.",
            ]);
        }
    }
}