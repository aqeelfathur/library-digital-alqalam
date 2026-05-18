<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class PustakawanPublikController extends Controller
{
    public function index(): View
    {
        $pustakawan = User::query()
            ->pustakawan()
            ->aktif()
            ->get();

        return view('publik.pustakawan', compact('pustakawan'));
    }
}