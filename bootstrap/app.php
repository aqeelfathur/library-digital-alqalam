<?php

use App\Http\Middleware\CekPeranAnggota;
use App\Http\Middleware\CekPeranPustakawan;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'anggota'    => CekPeranAnggota::class,
            'pustakawan' => CekPeranPustakawan::class,
        ]);

        // Rate limit untuk rute login
        $middleware->throttleWithRedis();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'sipus/pesan',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if (!$request->expectsJson()) {
                return redirect()->route('anggota.login')
                    ->withErrors(['email' => 'Sesi Anda telah berakhir. Silakan masuk kembali.']);
            }
        });
    })->create();