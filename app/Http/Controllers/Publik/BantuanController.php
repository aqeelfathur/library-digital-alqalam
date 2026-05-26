<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BantuanController extends Controller
{
    public function index(): View
    {
        $judulHalaman = "Bantuan";
        return view('publik.bantuan', compact('judulHalaman'));
    }
}