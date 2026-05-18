<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buku\StoreBukuRequest;
use App\Http\Requests\Buku\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use App\Services\FileUploadService;
use App\Services\LogAktivitasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function __construct(
        private readonly FileUploadService   $fileUploadService,
        private readonly LogAktivitasService $logService
    ) {}

    public function index(Request $request): View
    {
        $buku = Buku::query()
            ->with('kategori')
            ->when($request->filled('cari'), fn ($q) => $q->cari($request->string('cari')))
            ->when($request->filled('kategori'), fn ($q) => $q->where('category_id', $request->integer('kategori')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $kategori = Kategori::orderBy('name')->get();

        return view('pustakawan.buku.index', compact('buku', 'kategori'));
    }

    public function create(): View
    {
        $kategori = Kategori::orderBy('name')->get();
        return view('pustakawan.buku.create', compact('kategori'));
    }

    public function store(StoreBukuRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image_url')) {
            $data['image_url'] = $this->fileUploadService->simpanSampulBuku($request->file('image_url'));
        }

        $buku = Buku::create($data);

        $this->logService->catat('tambah_buku', "Menambahkan buku: {$buku->title}");

        return redirect()->route('pustakawan.buku.index')
            ->with('sukses', "Buku '{$buku->title}' berhasil ditambahkan.");
    }

    public function edit(Buku $buku): View
    {
        $kategori = Kategori::orderBy('name')->get();
        return view('pustakawan.buku.edit', compact('buku', 'kategori'));
    }

    public function update(UpdateBukuRequest $request, Buku $buku): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image_url')) {
            $data['image_url'] = $this->fileUploadService->ganti(
                $request->file('image_url'),
                $buku->image_url,
                'sampul-buku'
            );
        } else {
            unset($data['image_url']);
        }

        $buku->update($data);

        $this->logService->catat('ubah_buku', "Memperbarui buku: {$buku->title}");

        return redirect()->route('pustakawan.buku.index')
            ->with('sukses', "Buku '{$buku->title}' berhasil diperbarui.");
    }

    public function destroy(Buku $buku): RedirectResponse
    {
        $judul = $buku->title;
        $this->fileUploadService->hapus($buku->image_url);
        $buku->delete();

        $this->logService->catat('hapus_buku', "Menghapus buku: {$judul}");

        return redirect()->route('pustakawan.buku.index')
            ->with('sukses', "Buku '{$judul}' berhasil dihapus.");
    }
}