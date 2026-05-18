<x-layouts.anggota title="Dasbor Anggota">

    {{-- Sambutan --}}
    <div class="mb-8">
        <h1 class="font-playfair text-2xl font-bold text-stone-800 mb-1">
            Halo, {{ auth()->user()->name }}!
        </h1>
        <p class="text-stone-500 text-sm">Selamat datang di area anggota perpustakaan digital Al-Qalam.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Peminjaman Aktif & Riwayat --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Peminjaman Aktif --}}
            <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                <h2 class="font-semibold text-stone-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Peminjaman Aktif
                </h2>

                @if($peminjamanAktif)
                    <div class="flex gap-4">
                        <img src="{{ $peminjamanAktif->buku->sampulUrl() }}"
                             alt="{{ $peminjamanAktif->buku->title }}"
                             class="w-20 h-28 object-cover rounded-lg flex-shrink-0 shadow" />
                        <div class="flex-1 min-w-0">
                            <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                {{ $peminjamanAktif->buku->kategori->name ?? '-' }}
                            </span>
                            <h3 class="font-semibold text-stone-800 mt-1 leading-snug">
                                {{ $peminjamanAktif->buku->title }}
                            </h3>
                            <p class="text-sm text-stone-500 mb-3">{{ $peminjamanAktif->buku->author }}</p>

                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-stone-500 w-28">Status</span>
                                    <span @class([
                                        'font-medium px-2 py-0.5 rounded-full text-xs',
                                        'bg-yellow-100 text-yellow-700' => $peminjamanAktif->status === 'pending',
                                        'bg-blue-100 text-blue-700'    => $peminjamanAktif->status === 'approved',
                                        'bg-green-100 text-green-700'  => $peminjamanAktif->status === 'borrowed',
                                    ])>
                                        {{ $peminjamanAktif->labelStatus() }}
                                    </span>
                                </div>
                                @if($peminjamanAktif->due_date)
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="text-stone-500 w-28">Batas Kembali</span>
                                        <span @class([
                                            'font-medium',
                                            'text-red-600'  => $peminjamanAktif->terlambat(),
                                            'text-stone-700' => !$peminjamanAktif->terlambat(),
                                        ])>
                                            {{ $peminjamanAktif->due_date->translatedFormat('d F Y') }}
                                            @if($peminjamanAktif->terlambat())
                                                <span class="text-red-500 text-xs">(Terlambat!)</span>
                                            @else
                                                <span class="text-stone-400 text-xs">({{ $peminjamanAktif->sisaHari() }} hari lagi)</span>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <p class="text-stone-500 text-sm mb-3">Anda tidak sedang meminjam buku.</p>
                        <a href="{{ route('anggota.buku.index') }}"
                           class="inline-flex items-center gap-1.5 text-sm text-emerald-700 font-medium hover:underline">
                            Jelajahi koleksi buku
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Riwayat Peminjaman --}}
            <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-stone-800">Riwayat Peminjaman</h2>
                    <a href="{{ route('anggota.peminjaman.index') }}" class="text-sm text-emerald-700 hover:underline">
                        Lihat semua
                    </a>
                </div>

                @if($riwayatPeminjaman->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($riwayatPeminjaman as $item)
                            <div class="flex items-center gap-3 p-3 bg-stone-50 rounded-lg">
                                <img src="{{ $item->buku->sampulUrl() }}"
                                     alt="{{ $item->buku->title }}"
                                     class="w-10 h-14 object-cover rounded flex-shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-stone-800 text-sm truncate">{{ $item->buku->title }}</p>
                                    <p class="text-xs text-stone-500">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                                <span @class([
                                    'text-xs font-medium px-2 py-0.5 rounded-full flex-shrink-0',
                                    'bg-yellow-100 text-yellow-700' => $item->status === 'pending',
                                    'bg-blue-100 text-blue-700'     => $item->status === 'approved',
                                    'bg-green-100 text-green-700'   => $item->status === 'borrowed',
                                    'bg-stone-100 text-stone-600'   => $item->status === 'returned',
                                    'bg-red-100 text-red-700'       => in_array($item->status, ['late', 'rejected']),
                                ])>
                                    {{ $item->labelStatus() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-shared.kosong pesan="Belum ada riwayat peminjaman." />
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Profil --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                <div class="text-center mb-4">
                    <img src="{{ auth()->user()->fotoUrl() }}"
                         alt="{{ auth()->user()->name }}"
                         class="w-20 h-20 rounded-full object-cover mx-auto mb-3 border-4 border-emerald-100" />
                    <h3 class="font-semibold text-stone-800">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-stone-500">{{ auth()->user()->email }}</p>
                    <span class="inline-block mt-2 text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                        Anggota
                    </span>
                </div>

                <div class="border-t border-stone-100 pt-4 space-y-2">
                    <a href="{{ route('anggota.profil.edit') }}"
                       class="flex items-center gap-2 w-full px-3 py-2 text-sm text-stone-600 hover:bg-stone-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Profil
                    </a>
                    <a href="{{ route('anggota.peminjaman.index') }}"
                       class="flex items-center gap-2 w-full px-3 py-2 text-sm text-stone-600 hover:bg-stone-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Riwayat Peminjaman
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            {{-- Statistik Anggota --}}
            <div class="bg-emerald-800 text-white rounded-xl p-6">
                <h3 class="font-semibold text-white mb-4">Statistik Saya</h3>
                @php
                    $totalPinjam    = auth()->user()->peminjaman()->count();
                    $totalKembali   = auth()->user()->peminjaman()->where('status', 'returned')->count();
                @endphp
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-emerald-300">Total Peminjaman</span>
                        <span class="font-semibold">{{ $totalPinjam }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-emerald-300">Berhasil Kembali</span>
                        <span class="font-semibold">{{ $totalKembali }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.anggota>