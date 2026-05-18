<?php

namespace App\Policies;

use App\Models\Buku;
use App\Models\User;

class BukuPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Buku $buku): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isPustakawan();
    }

    public function update(User $user, Buku $buku): bool
    {
        return $user->isPustakawan();
    }

    public function delete(User $user, Buku $buku): bool
    {
        return $user->isPustakawan();
    }

    public function pinjam(User $user, Buku $buku): bool
    {
        return $user->isAnggota()
            && $user->isAktif()
            && $buku->isTersedia()
            && !$user->sedangMeminjam();
    }
}