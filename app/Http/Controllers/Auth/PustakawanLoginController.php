<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LogAktivitasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PustakawanLoginController extends Controller
{
    public function __construct(
        private readonly LogAktivitasService $logService
    ) {}

    public function tampilkan(): View
    {
        return view('auth.login-pustakawan');
    }

    public function proses(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('ingat_saya'))) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi tidak sesuai.',
            ])->onlyInput('email');
        }

        $pengguna = Auth::user();

        if (!$pengguna->isPustakawan()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun ini bukan akun pustakawan.',
            ])->onlyInput('email');
        }

        $this->logService->catatLogin($pengguna->id);
        $request->session()->regenerate();

        return redirect()->intended(route('pustakawan.dasbor'));
    }
}