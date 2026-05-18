<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Berita\StoreBeritaRequest;
use App\Models\Berita;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function __construct(
        private readonly FileUploadService $fileUploadService
    ) {}

    public function index(): View
    {
        $berita = Berita::orderByDesc('created_at')->paginate(10);
        return view('pustakawan.berita.index', compact('berita'));
    }

    public function create(): View
    {
        return view('pustakawan.berita.create');
    }

    public function store(StoreBeritaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->fileUploadService->simpanThumbnailBerita($request->file('thumbnail'));
        }

        Berita::create($data);

        return redirect()->route('pustakawan.berita.index')
            ->with('sukses', 'Berita berhasil dipublikasikan.');
    }

    public function edit(Berita $berita): View
    {
        return view('pustakawan.berita.edit', compact('berita'));
    }

    public function update(StoreBeritaRequest $request, Berita $berita): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->fileUploadService->ganti(
                $request->file('thumbnail'),
                $berita->thumbnail,
                'thumbnail-berita'
            );
        } else {
            unset($data['thumbnail']);
        }

        $berita->update($data);

        return redirect()->route('pustakawan.berita.index')
            ->with('sukses', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        $this->fileUploadService->hapus($berita->thumbnail);
        $berita->delete();

        return back()->with('sukses', 'Berita berhasil dihapus.');
    }
}