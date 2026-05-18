<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'kata_sandi_lama',
        'kata_sandi_baru',
        'kata_sandi_baru_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Sesi Anda telah berakhir.'], 401);
        }

        // Arahkan ke login yang sesuai berdasarkan guard
        return redirect()->guest(route('anggota.login'))
            ->withErrors(['email' => 'Silakan masuk untuk melanjutkan.']);
    }
}