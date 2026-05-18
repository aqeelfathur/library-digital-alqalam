<x-layouts.pustakawan title="Tulis Berita">

    <div class="mb-6">
        <a href="{{ route('pustakawan.berita.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-emerald-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
        <h2 class="font-semibold text-stone-800 text-lg mb-6 pb-4 border-b border-stone-100">Tulis Berita Baru</h2>

        <form method="POST" action="{{ route('pustakawan.berita.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Judul Berita <span class="text-red-500">*</span></label>
                <input name="title" type="text" value="{{ old('title') }}" required
                       class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('title') border-red-300 bg-red-50 @enderror"
                       placeholder="Masukkan judul berita" />
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Isi Berita <span class="text-red-500">*</span></label>
                <textarea name="content" rows="10" required
                          class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-y @error('content') border-red-300 bg-red-50 @enderror"
                          placeholder="Tulis isi berita di sini...">{{ old('content') }}</textarea>
                @error('content') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Thumbnail</label>
                    <input name="thumbnail" type="file" accept="image/*"
                           class="block w-full text-sm text-stone-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer" />
                    <p class="mt-1 text-xs text-stone-400">Format: JPEG, PNG, WebP. Maks. 2MB</p>
                    @error('thumbnail') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Tanggal Terbit</label>
                    <input name="published_at" type="datetime-local"
                           value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    <p class="mt-1 text-xs text-stone-400">Kosongkan untuk menyimpan sebagai draf.</p>
                </div>
            </div>

            <div class="border-t border-stone-100 pt-4 flex items-center gap-3 justify-end">
                <a href="{{ route('pustakawan.berita.index') }}"
                   class="px-5 py-2.5 border border-stone-300 text-stone-600 text-sm rounded-lg hover:bg-stone-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                    Simpan & Terbitkan
                </button>
            </div>
        </form>
    </div>

</x-layouts.pustakawan>