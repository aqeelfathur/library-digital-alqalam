<x-layouts.pustakawan title="Edit Buku">

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
            Edit Buku: <span class="text-emerald-700">{{ $buku->title }}</span>
        </h2>

        <form
            method="POST"
            action="{{ route('pustakawan.buku.update', $buku) }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                    <input name="title" type="text" value="{{ old('title', $buku->title) }}" required
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('title') border-red-300 @enderror" />
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Penulis <span class="text-red-500">*</span></label>
                    <input name="author" type="text" value="{{ old('author', $buku->author) }}" required
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('category_id', $buku->category_id) == $kat->id ? 'selected' : '' }}>
                                {{ $kat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Penerbit</label>
                    <input name="publisher" type="text" value="{{ old('publisher', $buku->publisher) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Edisi</label>
                    <input name="edition" type="text" value="{{ old('edition', $buku->edition) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">ISBN / ISSN</label>
                    <input name="isbn_issn" type="text" value="{{ old('isbn_issn', $buku->isbn_issn) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Nomor Panggil</label>
                    <input name="call_number" type="text" value="{{ old('call_number', $buku->call_number) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Klasifikasi</label>
                    <input name="classification" type="text" value="{{ old('classification', $buku->classification) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Bahasa</label>
                    <input name="language" type="text" value="{{ old('language', $buku->language) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach(['tersedia' => 'Tersedia', 'dipinjam' => 'Dipinjam', 'maintenance' => 'Perawatan', 'hilang' => 'Hilang'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $buku->status) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Judul Seri</label>
                    <input name="series_title" type="text" value="{{ old('series_title', $buku->series_title) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Subyek</label>
                    <input name="subject" type="text" value="{{ old('subject', $buku->subject) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Deskripsi Fisik</label>
                    <input name="physical_description" type="text" value="{{ old('physical_description', $buku->physical_description) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Jenis Konten</label>
                    <input name="content_type" type="text" value="{{ old('content_type', $buku->content_type) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Jenis Media</label>
                    <input name="media_type" type="text" value="{{ old('media_type', $buku->media_type) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Jenis Carrier</label>
                    <input name="carrier_type" type="text" value="{{ old('carrier_type', $buku->carrier_type) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Informasi Detail Spesifik</label>
                    <textarea name="specific_detail_info" rows="2"
                              class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('specific_detail_info', $buku->specific_detail_info) }}</textarea>
                </div>
            </div>

            {{-- Sampul --}}
            <div class="border-t border-stone-100 pt-6">
                <h3 class="text-sm font-semibold text-stone-700 uppercase tracking-wider mb-4">Sampul Buku</h3>
                <div x-data="{ preview: '{{ $buku->sampulUrl() }}' }" class="flex items-start gap-6">
                    <img :src="preview" class="w-32 h-44 object-cover rounded-lg shadow" />
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-2">Ganti Sampul Buku</label>
                        <input
                            name="image_url"
                            type="file"
                            accept="image/*"
                            @change="
                                const file = $event.target.files[0];
                                if (file) preview = URL.createObjectURL(file);
                            "
                            class="block text-sm text-stone-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer"
                        />
                        <p class="mt-1.5 text-xs text-stone-400">Biarkan kosong jika tidak ingin mengubah sampul.</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-stone-100 pt-6 flex items-center gap-3 justify-end">
                <a href="{{ route('pustakawan.buku.index') }}"
                   class="px-5 py-2.5 border border-stone-300 text-stone-600 text-sm rounded-lg hover:bg-stone-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                    Perbarui Buku
                </button>
            </div>
        </form>
    </div>

</x-layouts.pustakawan>