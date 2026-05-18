<?php

namespace App\Providers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Policies\BukuPolicy;
use App\Policies\PeminjamanPolicy;
use App\Repositories\BukuRepository;
use App\Repositories\Contracts\BukuRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BukuRepositoryInterface::class, BukuRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Buku::class, BukuPolicy::class);
        Gate::policy(Peminjaman::class, PeminjamanPolicy::class);

        Password::defaults(fn () => Password::min(8)->letters()->numbers());

        Paginator::defaultView('vendor.pagination.tailwind');
    }
}