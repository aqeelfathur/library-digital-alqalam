<x-layouts.pustakawan title="Edit Berita">

    <div class="mb-6">
        <a href="{{ route('pustakawan.berita.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-emerald-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Berita
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
        <h2 class="font-semibold text-stone-800 text-lg mb-6 pb-4 border-b border-stone-100">
            Edit Berita: <span class="text-emerald-700">{{ Str::limit($berita->title, 40) }}</span>
        </h2>

        <form method="POST"
              action="{{ route('pustakawan.berita.update', $berita) }}"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">
                    Judul Berita <span class="text-red-500">*</span>
                </label>
                <input
                    name="title"
                    type="text"
                    value="{{ old('title', $berita->title) }}"
                    required
                    class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('title') border-red-300 bg-red-50 @enderror"
                />
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">
                    Isi Berita <span class="text-red-500">*</span>
                </label>
                <textarea
                    name="content"
                    rows="12"
                    required
                    class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-y @error('content') border-red-300 bg-red-50 @enderror"
                >{{ old('content', $berita->content) }}</textarea>
                @error('content') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div x-data="{ preview: '{{ $berita->thumbnailUrl() }}' }">
                    <label class="block text-sm font-medium text-stone-700 mb-2">Thumbnail</label>
                    <div class="mb-3">
                        <img :src="preview"
                             class="h-32 w-48 object-cover rounded-lg border border-stone-200 shadow-sm" />
                    </div>
                    <input
                        name="thumbnail"
                        type="file"
                        accept="image/*"
                        @change="
                            const file = $event.target.files[0];
                            if (file) preview = URL.createObjectURL(file);
                        "
                        class="block w-full text-sm text-stone-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer"
                    />
                    <p class="mt-1 text-xs text-stone-400">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                    @error('thumbnail') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Tanggal Terbit</label>
                    <input
                        name="published_at"
                        type="datetime-local"
                        value="{{ old('published_at', $berita->published_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
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
                    Perbarui Berita
                </button>
            </div>
        </form>
    </div>

</x-layouts.pustakawan>