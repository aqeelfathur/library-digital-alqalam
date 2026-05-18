<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekPeranPustakawan
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isPustakawan()) {
            abort(403, 'Akses ditolak. Area ini khusus untuk pustakawan.');
        }

        return $next($request);
    }
}