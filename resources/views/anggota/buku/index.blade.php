<x-layouts.anggota title="Koleksi Buku">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="font-playfair text-2xl font-bold text-stone-800 mb-1">Koleksi Buku</h1>
        <p class="text-stone-500 text-sm">Temukan dan pinjam buku dari koleksi perpustakaan kami</p>
    </div>

    {{-- Peringatan Peminjaman Aktif --}}
    @if(auth()->user()->sedangMeminjam())
        @php $aktif = auth()->user()->peminjamanAktif(); @endphp
        <div class="mb-6 flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"/>
            </svg>
            <div>
                <p class="font-medium text-sm">Anda sedang meminjam buku.</p>
                <p class="text-xs mt-0.5">
                    Kembalikan <strong>{{ $aktif?->buku?->title }}</strong> sebelum meminjam buku lain.
                </p>
            </div>
        </div>
    @endif

    {{-- Filter & Cari --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-4 mb-6">
        <form method="GET" action="{{ route('anggota.buku.index') }}" class="flex flex-wrap gap-3">
            <input
                name="cari"
                value="{{ request('cari') }}"
                type="text"
                placeholder="Cari judul, penulis, ISBN..."
                class="px-4 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 flex-1 min-w-48"
            />
            <select name="kategori" class="px-4 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                        {{ $kat->name }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-emerald-700 text-white text-sm rounded-lg hover:bg-emerald-800 transition-colors">
                Cari
            </button>
            @if(request()->hasAny(['cari', 'kategori', 'status']))
                <a href="{{ route('anggota.buku.index') }}"
                   class="px-4 py-2 border border-stone-300 text-stone-600 text-sm rounded-lg hover:bg-stone-50 transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Grid Buku --}}
    @if($buku->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
            @foreach($buku as $item)
                <a href="{{ route('anggota.buku.show', $item) }}" class="block group">
                    <x-publik.kartu-buku :buku="$item" />
                </a>
            @endforeach
        </div>
        {{ $buku->links() }}
    @else
        <x-shared.kosong pesan="Tidak ada buku yang ditemukan sesuai pencarian." />
    @endif

</x-layouts.anggota>