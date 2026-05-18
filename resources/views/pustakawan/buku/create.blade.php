<x-layouts.pustakawan title="Tambah Buku">

    <div class="mb-6">
        <a href="{{ route('pustakawan.buku.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-emerald-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Buku
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
        <h2 class="font-semibold text-stone-800 text-lg mb-6 pb-4 border-b border-stone-100">
            Tambah Koleksi Buku Baru
        </h2>

        <form
            method="POST"
            action="{{ route('pustakawan.buku.store') }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf

            {{-- Informasi Utama --}}
            <div>
                <h3 class="text-sm font-semibold text-stone-700 uppercase tracking-wider mb-4">Informasi Utama</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">
                            Judul Buku <span class="text-red-500">*</span>
                        </label>
                        <input
                            name="title"
                            type="text"
                            value="{{ old('title') }}"
                            required
                            class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('title') border-red-300 bg-red-50 @else border-stone-300 @enderror"
                            placeholder="Masukkan judul buku"
                        />
                        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">
                            Penulis <span class="text-red-500">*</span>
                        </label>
                        <input
                            name="author"
                            type="text"
                            value="{{ old('author') }}"
                            required
                            class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('author') border-red-300 bg-red-50 @else border-stone-300 @enderror"
                            placeholder="Nama penulis"
                        />
                        @error('author') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" required class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('category_id') border-red-300 bg-red-50 @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ old('category_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Penerbit</label>
                        <input name="publisher" type="text" value="{{ old('publisher') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Nama penerbit" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Edisi</label>
                        <input name="edition" type="text" value="{{ old('edition') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Contoh: Edisi ke-3" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">ISBN / ISSN</label>
                        <input name="isbn_issn" type="text" value="{{ old('isbn_issn') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono"
                               placeholder="978-x-xx-xxxxxx-x" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Nomor Panggil</label>
                        <input name="call_number" type="text" value="{{ old('call_number') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono"
                               placeholder="Contoh: 813 HAR p" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Klasifikasi</label>
                        <input name="classification" type="text" value="{{ old('classification') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Nomor klasifikasi DDC" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Bahasa</label>
                        <input name="language" type="text" value="{{ old('language', 'Indonesia') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Indonesia" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="tersedia" {{ old('status', 'tersedia') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                            <option value="hilang" {{ old('status') === 'hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Judul Seri</label>
                        <input name="series_title" type="text" value="{{ old('series_title') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Judul seri buku (jika ada)" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Subyek</label>
                        <input name="subject" type="text" value="{{ old('subject') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Kata kunci subyek" />
                    </div>
                </div>
            </div>

            {{-- Deskripsi Fisik --}}
            <div class="border-t border-stone-100 pt-6">
                <h3 class="text-sm font-semibold text-stone-700 uppercase tracking-wider mb-4">Deskripsi Fisik & Teknis</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Deskripsi Fisik</label>
                        <input name="physical_description" type="text" value="{{ old('physical_description') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Contoh: viii, 320 hlm.; 21 cm" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Jenis Konten</label>
                        <input name="content_type" type="text" value="{{ old('content_type', 'Teks') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Teks" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Jenis Media</label>
                        <input name="media_type" type="text" value="{{ old('media_type', 'Tanpa Perantara') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Tanpa Perantara" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Jenis Carrier</label>
                        <input name="carrier_type" type="text" value="{{ old('carrier_type', 'Volume') }}"
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Volume" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Informasi Detail Spesifik</label>
                        <textarea name="specific_detail_info" rows="2"
                                  class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"
                                  placeholder="Informasi tambahan">{{ old('specific_detail_info') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Upload Sampul --}}
            <div class="border-t border-stone-100 pt-6">
                <h3 class="text-sm font-semibold text-stone-700 uppercase tracking-wider mb-4">Sampul Buku</h3>
                <div
                    x-data="{ preview: null, namaFile: '' }"
                    class="flex items-start gap-6"
                >
                    <div class="flex-shrink-0">
                        <div x-show="!preview" class="w-32 h-44 bg-stone-100 rounded-lg border-2 border-dashed border-stone-300 flex flex-col items-center justify-center text-stone-400">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs">Sampul</span>
                        </div>
                        <img x-show="preview" :src="preview" class="w-32 h-44 object-cover rounded-lg shadow" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-2">Unggah Sampul Buku</label>
                        <input
                            name="image_url"
                            type="file"
                            accept="image/*"
                            @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    preview = URL.createObjectURL(file);
                                    namaFile = file.name;
                                }
                            "
                            class="block w-full text-sm text-stone-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer"
                        />
                        <p class="mt-1.5 text-xs text-stone-400">Format: JPEG, PNG, WebP. Maksimal 2MB.</p>
                        @error('image_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="border-t border-stone-100 pt-6 flex items-center gap-3 justify-end">
                <a href="{{ route('pustakawan.buku.index') }}"
                   class="px-5 py-2.5 border border-stone-300 text-stone-600 text-sm rounded-lg hover:bg-stone-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                    Simpan Buku
                </button>
            </div>
        </form>
    </div>

</x-layouts.pustakawan>