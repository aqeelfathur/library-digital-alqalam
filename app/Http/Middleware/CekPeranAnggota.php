<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekPeranAnggota
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAnggota()) {
            abort(403, 'Akses ditolak. Area ini khusus untuk anggota.');
        }

        if (!auth()->user()->isAktif()) {
            auth()->logout();
            return redirect()->route('anggota.login')
                ->withErrors(['status' => 'Akun Anda tidak aktif. Hubungi pustakawan.']);
        }

        return $next($request);
    }
}