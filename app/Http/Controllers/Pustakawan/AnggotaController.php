<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function index(Request $request): View
    {
        $anggota = User::query()
            ->anggota()
            ->withCount('peminjaman')
            ->when($request->filled('cari'), fn ($q) => $q->where('name', 'like', "%{$request->string('cari')}%")
                ->orWhere('email', 'like', "%{$request->string('cari')}%"))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pustakawan.anggota.index', compact('anggota'));
    }

    public function ubahStatus(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:aktif,nonaktif,suspended'],
        ]);

        $user->update(['status' => $request->status]);

        return back()->with('sukses', "Status anggota '{$user->name}' berhasil diubah.");
    }

    public function aturUlangKataSandi(User $user): RedirectResponse
    {
        $kataKandiBaru = 'perpustakaan123';
        $user->update(['password' => Hash::make($kataKandiBaru)]);

        return back()->with('sukses', "Kata sandi anggota '{$user->name}' berhasil diatur ulang menjadi: {$kataKandiBaru}");
    }
}