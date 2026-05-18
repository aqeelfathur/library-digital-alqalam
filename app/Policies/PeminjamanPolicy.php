<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    public function setujui(User $user, Peminjaman $peminjaman): bool
    {
        return $user->isPustakawan() && $peminjaman->status === 'pending';
    }

    public function tolak(User $user, Peminjaman $peminjaman): bool
    {
        return $user->isPustakawan() && $peminjaman->status === 'pending';
    }

    public function kembalikan(User $user, Peminjaman $peminjaman): bool
    {
        return $user->isPustakawan() && $peminjaman->status === 'borrowed';
    }
}