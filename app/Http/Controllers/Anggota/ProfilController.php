<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profil\UpdateProfilRequest;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    ) {}

    public function edit(): View
    {
        return view('anggota.profil.edit', ['pengguna' => auth()->user()]);
    }

    public function perbarui(UpdateProfilRequest $request): RedirectResponse
    {
        $pengguna = auth()->user();
        $data     = $request->validated();

        if ($request->hasFile('image_url')) {
            $data['image_url'] = $this->fileUploadService->ganti(
                $request->file('image_url'),
                $pengguna->image_url,
                'foto-pengguna'
            );
        } else {
            unset($data['image_url']);
        }

        $pengguna->update($data);

        return back()->with('sukses', 'Profil berhasil diperbarui.');
    }

    public function gantiKataSandi(Request $request): RedirectResponse
    {
        $request->validate([
            'kata_sandi_lama' => ['required'],
            'kata_sandi_baru' => ['required', 'confirmed', Password::defaults()],
        ], [
            'kata_sandi_lama.required' => 'Kata sandi lama wajib diisi.',
            'kata_sandi_baru.required' => 'Kata sandi baru wajib diisi.',
            'kata_sandi_baru.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $pengguna = auth()->user();

        if (!Hash::check($request->kata_sandi_lama, $pengguna->password)) {
            return back()->withErrors(['kata_sandi_lama' => 'Kata sandi lama tidak sesuai.']);
        }

        $pengguna->update(['password' => $request->kata_sandi_baru]);

        return back()->with('sukses', 'Kata sandi berhasil diubah.');
    }
}