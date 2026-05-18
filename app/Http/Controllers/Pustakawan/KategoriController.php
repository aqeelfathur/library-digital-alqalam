<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(): View
    {
        $kategori = Kategori::withCount('buku')->orderBy('name')->paginate(15);
        return view('pustakawan.kategori.index', compact('kategori'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:100', 'unique:categories,name']],
            ['name.required' => 'Nama kategori wajib diisi.', 'name.unique' => 'Kategori sudah ada.']);

        Kategori::create(['name' => $request->name]);

        return back()->with('sukses', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', "unique:categories,name,{$kategori->id}"],
        ], ['name.required' => 'Nama kategori wajib diisi.', 'name.unique' => 'Kategori sudah ada.']);

        $kategori->update(['name' => $request->name]);

        return back()->with('sukses', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori): RedirectResponse
    {
        if ($kategori->buku()->exists()) {
            return back()->withErrors(['nama' => 'Kategori ini masih memiliki buku. Hapus atau pindahkan buku terlebih dahulu.']);
        }

        $kategori->delete();
        return back()->with('sukses', 'Kategori berhasil dihapus.');
    }
}