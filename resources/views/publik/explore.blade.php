<x-layouts.publik :title="$judulHalaman . ' — Perpustakaan Al-Qalam'">

    {{-- BREADCRUMB --}}
    <div class="bg-stone-100 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center gap-2 text-xs text-stone-500">
                <a href="{{ route('beranda') }}" class="hover:text-emerald-700 transition-colors">Beranda</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-stone-700 font-medium">{{ $judulHalaman }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- SEARCH BAR UTAMA --}}
        <div class="mb-6">
            <form action="{{ route('explore') }}" method="GET" id="form-explore">
                <div class="flex gap-0 bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="flex-1 flex items-center gap-3 px-4">
                        <svg class="w-5 h-5 text-stone-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            name="search"
                            type="text"
                            value="{{ $filter['search'] }}"
                            placeholder="Masukkan kata kunci untuk mencari koleksi..."
                            class="flex-1 py-3.5 text-stone-800 text-sm focus:outline-none placeholder:text-stone-400 bg-transparent"
                            autocomplete="off"
                        />
                        @if(filled($filter['search']))
                            <a href="{{ route('explore', array_filter(array_merge($filter, ['search' => '']))) }}"
                               class="text-stone-400 hover:text-stone-600 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        @endif
                    </div>

                    {{-- Selector urutan --}}
                    <div class="hidden sm:flex items-center border-l border-stone-200 px-3">
                        <select name="urutan"
                                onchange="document.getElementById('form-explore').submit()"
                                class="text-sm text-stone-600 focus:outline-none bg-transparent cursor-pointer py-2">
                            @foreach([
                                'terbaru'  => 'Terbaru',
                                'terlama'  => 'Terlama',
                                'populer'  => 'Paling Populer',
                                'az'       => 'A - Z',
                                'za'       => 'Z - A',
                            ] as $val => $label)
                                <option value="{{ $val }}" {{ $filter['urutan'] === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                            class="px-6 py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm transition-colors flex-shrink-0">
                        Cari
                    </button>
                </div>

                {{-- Hidden fields untuk pertahankan filter lain --}}
                @foreach(array_filter(array_diff_key($filter, array_flip(['search', 'urutan']))) as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}" />
                @endforeach
            </form>
        </div>

        {{-- LAYOUT UTAMA: Konten Kiri + Sidebar Kanan --}}
        <div class="flex flex-col lg:flex-row gap-6" x-data="{ advancedTerbuka: {{ $adaFilter && !filled($filter['search']) ? 'true' : 'false' }} }">

            {{-- ── KOLOM KIRI ── --}}
            <div class="flex-1 min-w-0">

                {{-- ADVANCED SEARCH FORM --}}
                <div class="bg-white rounded-xl shadow-sm border border-stone-100 mb-6 overflow-hidden">
                    <button
                        @click="advancedTerbuka = !advancedTerbuka"
                        class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-stone-50 transition-colors"
                    >
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                            </svg>
                            <span class="text-sm font-semibold text-stone-700">Pencarian Spesifik</span>
                            @if($adaFilter)
                                <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-medium">
                                    {{ $filterAktif->count() }} filter aktif
                                </span>
                            @endif
                        </div>
                        <svg class="w-4 h-4 text-stone-400 transition-transform duration-200"
                             :class="advancedTerbuka ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="advancedTerbuka" x-collapse>
                        <form action="{{ route('explore') }}" method="GET" class="px-5 pb-5 border-t border-stone-100">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">

                                {{-- Judul --}}
                                <div>
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Judul</label>
                                    <input name="judul" type="text"
                                           value="{{ $filter['judul'] }}"
                                           placeholder="Masukkan judul"
                                           class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                </div>

                                {{-- Pengarang --}}
                                <div>
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Pengarang</label>
                                    <input name="pengarang" type="text"
                                           value="{{ $filter['pengarang'] }}"
                                           placeholder="Masukkan nama pengarang"
                                           class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                </div>

                                {{-- Subjek --}}
                                <div>
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Subjek</label>
                                    <input name="subjek" type="text"
                                           value="{{ $filter['subjek'] }}"
                                           placeholder="Masukkan subjek"
                                           class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                </div>

                                {{-- ISBN/ISSN --}}
                                <div>
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">ISBN / ISSN</label>
                                    <input name="isbn" type="text"
                                           value="{{ $filter['isbn'] }}"
                                           placeholder="Masukkan ISBN/ISSN"
                                           class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                </div>

                                {{-- Penerbit --}}
                                <div>
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Penerbit</label>
                                    <input name="penerbit" type="text"
                                           value="{{ $filter['penerbit'] }}"
                                           placeholder="Masukkan nama penerbit"
                                           class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Kategori</label>
                                    <select name="kategori"
                                            class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="">Semua Kategori</option>
                                        @foreach($kategori as $kat)
                                            <option value="{{ $kat->slug }}"
                                                    {{ $filter['kategori'] === $kat->slug ? 'selected' : '' }}>
                                                {{ $kat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tipe Koleksi --}}
                                <div>
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Tipe Koleksi</label>
                                    <select name="tipe_koleksi"
                                            class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="semua">Semua Koleksi</option>
                                        @foreach(['buku' => 'Buku', 'majalah' => 'Majalah', 'jurnal' => 'Jurnal', 'skripsi' => 'Skripsi', 'ebook' => 'Ebook'] as $val => $label)
                                            <option value="{{ $val }}" {{ $filter['tipe_koleksi'] === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- GMD / Media --}}
                                <div>
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">GMD / Media</label>
                                    <select name="gmd"
                                            class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="semua">Semua GMD/Media</option>
                                        @foreach(['Teks' => 'Teks', 'Audio' => 'Audio', 'Video' => 'Video', 'Digital' => 'Digital'] as $val => $label)
                                            <option value="{{ $val }}" {{ $filter['gmd'] === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Lokasi --}}
                                <div>
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Lokasi</label>
                                    <select name="lokasi"
                                            class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="semua">Semua Lokasi</option>
                                        @foreach(['Rak A' => 'Rak A', 'Rak B' => 'Rak B', 'Rak C' => 'Rak C', 'Perpustakaan Utama' => 'Perpustakaan Utama'] as $val => $label)
                                            <option value="{{ $val }}" {{ $filter['lokasi'] === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tahun Terbit --}}
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Tahun Terbit</label>
                                    <div class="flex items-center gap-3">
                                        <input name="tahun_dari" type="number"
                                               value="{{ $filter['tahun_dari'] }}"
                                               placeholder="Dari tahun"
                                               min="1900" max="{{ date('Y') }}"
                                               class="flex-1 px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                                        <span class="text-stone-400 text-sm flex-shrink-0">s.d.</span>
                                        <input name="tahun_sampai" type="number"
                                               value="{{ $filter['tahun_sampai'] }}"
                                               placeholder="Sampai tahun"
                                               min="1900" max="{{ date('Y') }}"
                                               class="flex-1 px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                                    </div>
                                </div>

                                {{-- Urutan (mobile) --}}
                                <div class="sm:hidden">
                                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Urutan</label>
                                    <select name="urutan"
                                            class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        @foreach(['terbaru' => 'Terbaru', 'terlama' => 'Terlama', 'populer' => 'Paling Populer', 'az' => 'A - Z', 'za' => 'Z - A'] as $val => $label)
                                            <option value="{{ $val }}" {{ $filter['urutan'] === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mt-5 pt-4 border-t border-stone-100">
                                <button type="submit"
                                        class="px-5 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                                    Terapkan Filter
                                </button>
                                @if($adaFilter)
                                    <a href="{{ route('explore') }}"
                                       class="px-5 py-2 border border-stone-300 text-stone-600 text-sm rounded-lg hover:bg-stone-50 transition-colors">
                                        Hapus Semua Filter
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- STATISTIK PENCARIAN + FILTER BADGE --}}
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <div>
                        @if($adaFilter || filled($filter['search']))
                            <p class="text-sm text-stone-600">
                                Ditemukan
                                <span class="font-semibold text-stone-800">{{ number_format($jumlahHasil) }}</span>
                                hasil
                                @if(filled($filter['search']))
                                    dari pencarian
                                    <span class="font-semibold text-emerald-700">"{{ $filter['search'] }}"</span>
                                @endif
                                <span class="text-stone-400 text-xs ml-1">({{ $durasi }}ms)</span>
                            </p>
                        @else
                            <p class="text-sm text-stone-600">
                                Menampilkan
                                <span class="font-semibold text-stone-800">{{ number_format($jumlahHasil) }}</span>
                                koleksi
                            </p>
                        @endif

                        {{-- Filter Badge --}}
                        @if($filterAktif->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                @foreach($filterAktif as $f)
                                    <x-explore.filter-badge
                                        :label="$f['label']"
                                        :nilai="$f['nilai']"
                                        :kunci="$f['kunci']"
                                        :filter="$filter"
                                    />
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- DAFTAR HASIL KOLEKSI --}}
                @if($hasil->isNotEmpty())
                    <div class="space-y-3 mb-6">
                        @foreach($hasil as $buku)
                            <x-explore.kartu-koleksi :buku="$buku" />
                        @endforeach
                    </div>

                    {{-- PAGINATION --}}
                    {{ $hasil->links() }}

                @else
                    {{-- EMPTY STATE --}}
                    <x-explore.kosong-explore :filter="$filter" />
                @endif
            </div>

            {{-- ── SIDEBAR KANAN ── --}}
            <x-explore.sidebar-rekomendasi
                :buku="$rekomendasiBuku"
                :kategori="$kategori"
                :filter="$filter"
                :totalBuku="$totalBuku"
            />
        </div>
    </div>

</x-layouts.publik>