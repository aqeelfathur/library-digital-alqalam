<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\InformasiPerpustakaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InformasiPerpustakaanController extends Controller
{
    public function edit(): View
    {
        $informasi = InformasiPerpustakaan::ambil();
        return view('pustakawan.informasi.edit', compact('informasi'));
    }

    public function perbarui(Request $request): RedirectResponse
    {
        $request->validate([
            'address'                => ['nullable', 'string'],
            'phone'                  => ['nullable', 'string', 'max:30'],
            'email'                  => ['nullable', 'email'],
            'operational_hours'      => ['nullable', 'string'],
            'membership_information' => ['nullable', 'string'],
            'maps_embed_url'         => ['nullable', 'string'],
        ]);

        InformasiPerpustakaan::ambil()->update($request->validated());

        return back()->with('sukses', 'Informasi perpustakaan berhasil diperbarui.');
    }
}