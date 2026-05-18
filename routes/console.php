<?php

use App\Console\Commands\CekPeminjamanTerlambat;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Tampilkan kutipan inspiratif');

// Jadwal pengecekan peminjaman terlambat setiap hari pukul 00.01
Schedule::command(CekPeminjamanTerlambat::class)->dailyAt('00:01');