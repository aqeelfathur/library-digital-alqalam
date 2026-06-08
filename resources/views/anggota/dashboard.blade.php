{{-- BREADCRUMB --}}
    <div class="pt-16 bg-emerald-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center gap-2 text-xs text-white">
                <a href="{{ route('beranda') }}" class="hover:text-emerald-700 transition-colors">Beranda</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white font-medium">{{ $judulHalaman }}</span>
            </nav>
        </div>
    </div>

<x-layouts.anggota title="Dasbor Anggota">

    @php
        $totalPinjam  = $anggota->peminjaman()->count();
        $totalKembali = $anggota->peminjaman()->where('status', 'returned')->count();
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

        {{-- Kolom Kiri: Peminjaman Aktif & Riwayat --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Sambutan --}}
            <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-700 p-6 sm:p-7 text-white shadow-lg">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-100">Dashboard Anggota</p>
                        <h1 class="font-playfair text-2xl sm:text-3xl font-bold mt-2">
                            Halo, {{ $anggota->name }}!
                        </h1>
                        <p class="text-sm text-emerald-50/90 mt-2 max-w-xl">
                            Selamat datang kembali. Kelola peminjamanmu dan temukan koleksi baru dari Perpustakaan Digital Al-Qalam.
                        </p>
                    </div>
                    <a href="{{ route('anggota.buku.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-emerald-800 shadow-sm transition hover:bg-emerald-50">
                        Cari Buku
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Quick Action --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <a href="{{ route('anggota.buku.index') }}"
                   class="group flex items-center gap-3 rounded-2xl border border-stone-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-100 hover:shadow-md">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 transition group-hover:bg-emerald-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold text-stone-800">Cari Buku</span>
                        <span class="block text-xs text-stone-500">Temukan judul cepat</span>
                    </span>
                </a>
                <a href="{{ route('anggota.buku.index') }}"
                   class="group flex items-center gap-3 rounded-2xl border border-stone-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-100 hover:shadow-md">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 transition group-hover:bg-emerald-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold text-stone-800">Lihat Koleksi</span>
                        <span class="block text-xs text-stone-500">Jelajahi katalog</span>
                    </span>
                </a>
                <a href="{{ route('anggota.peminjaman.index') }}"
                   class="group flex items-center gap-3 rounded-2xl border border-stone-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-100 hover:shadow-md">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 transition group-hover:bg-emerald-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold text-stone-800">Riwayat</span>
                        <span class="block text-xs text-stone-500">Cek peminjaman</span>
                    </span>
                </a>
            </div>

            {{-- Peminjaman Aktif --}}
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 sm:p-6 shadow-md shadow-emerald-900/5">
                <div class="flex items-center justify-between gap-4 mb-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Sedang Dibaca</p>
                        <h2 class="font-playfair text-xl font-bold text-stone-900 mt-1">Peminjaman Aktif</h2>
                    </div>
                    @if($peminjamanAktif)
                        <span @class([
                            'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold',
                            'bg-rose-100 text-rose-700' => $peminjamanAktif->terlambat(),
                            'bg-emerald-100 text-emerald-700' => !$peminjamanAktif->terlambat(),
                        ])>
                            {{ $peminjamanAktif->terlambat() ? 'Perlu dikembalikan' : 'Aktif' }}
                        </span>
                    @endif
                </div>

                @if($peminjamanAktif)
                    <div class="rounded-2xl bg-gradient-to-br from-emerald-50 via-white to-stone-50 p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row gap-5">
                            <img src="{{ $peminjamanAktif->buku->sampulUrl() }}"
                                 alt="{{ $peminjamanAktif->buku->title }}"
                                 class="w-28 h-40 object-cover rounded-xl flex-shrink-0 shadow-lg shadow-stone-900/10" />
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="text-xs font-semibold text-emerald-700 bg-white border border-emerald-100 px-2.5 py-1 rounded-full">
                                        {{ $peminjamanAktif->buku->kategori->name ?? '-' }}
                                    </span>
                                    <span @class([
                                        'font-semibold px-2.5 py-1 rounded-full text-xs',
                                        'bg-yellow-100 text-yellow-700' => $peminjamanAktif->status === 'pending',
                                        'bg-blue-100 text-blue-700'    => $peminjamanAktif->status === 'approved',
                                        'bg-green-100 text-green-700'  => $peminjamanAktif->status === 'borrowed',
                                        'bg-rose-100 text-rose-700'    => in_array($peminjamanAktif->status, ['late', 'rejected']),
                                    ])>
                                        {{ $peminjamanAktif->labelStatus() }}
                                    </span>
                                </div>
                                <h3 class="font-playfair text-2xl font-bold text-stone-900 leading-tight">
                                    {{ $peminjamanAktif->buku->title }}
                                </h3>
                                <p class="text-sm text-stone-500 mt-1 mb-5">{{ $peminjamanAktif->buku->author }}</p>

                                @if($peminjamanAktif->due_date)
                                    <div @class([
                                        'rounded-xl border p-4',
                                        'bg-rose-50 border-rose-100' => $peminjamanAktif->terlambat(),
                                        'bg-white border-stone-100' => !$peminjamanAktif->terlambat(),
                                    ])>
                                        <div class="flex items-start gap-3">
                                            @if($peminjamanAktif->terlambat())
                                                <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </span>
                                            @endif
                                            <div>
                                                <p @class([
                                                    'text-sm font-semibold',
                                                    'text-rose-700' => $peminjamanAktif->terlambat(),
                                                    'text-stone-800' => !$peminjamanAktif->terlambat(),
                                                ])>
                                                    {{ $peminjamanAktif->terlambat() ? 'Terlambat' : 'Batas Kembali' }}
                                                </p>
                                                <p class="text-sm text-stone-600 mt-0.5">
                                                    {{ $peminjamanAktif->due_date->translatedFormat('d F Y') }}
                                                    @if(!$peminjamanAktif->terlambat())
                                                        <span class="text-stone-400">({{ $peminjamanAktif->sisaHari() }} hari lagi)</span>
                                                    @else
                                                        <span class="text-rose-600">Perlu segera dikembalikan.</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-stone-200 bg-stone-50/70 text-center py-10 px-5">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-stone-800">Belum ada peminjaman aktif</h3>
                        <p class="text-stone-500 text-sm mt-1 mb-4">Mulai jelajahi koleksi dan ajukan buku yang ingin kamu baca.</p>
                        <a href="{{ route('anggota.buku.index') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                            Jelajahi koleksi buku
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Riwayat Peminjaman --}}
            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Aktivitas</p>
                        <h2 class="font-playfair text-xl font-bold text-stone-900 mt-1">Riwayat Peminjaman</h2>
                    </div>
                    <a href="{{ route('anggota.peminjaman.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                        Lihat semua
                    </a>
                </div>

                @if($riwayatPeminjaman->isNotEmpty())
                    <div class="space-y-2">
                        @foreach($riwayatPeminjaman as $item)
                            <div class="flex items-center gap-3 rounded-xl border border-stone-100 bg-white p-3 transition hover:border-emerald-100 hover:bg-emerald-50/40 hover:shadow-sm">
                                <img src="{{ $item->buku->sampulUrl() }}"
                                     alt="{{ $item->buku->title }}"
                                     class="w-11 h-14 object-cover rounded-lg flex-shrink-0 shadow-sm" />
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-stone-800 text-sm truncate">{{ $item->buku->title }}</p>
                                    <p class="text-xs text-stone-500 mt-0.5">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                                <span @class([
                                    'text-xs font-semibold px-2.5 py-1 rounded-full flex-shrink-0',
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
                    <div class="rounded-2xl border border-dashed border-stone-200 bg-stone-50/70 px-5 py-8 text-center">
                        <p class="text-sm font-medium text-stone-700">Belum ada riwayat peminjaman.</p>
                        <p class="text-xs text-stone-500 mt-1">Buku yang kamu pinjam akan muncul di sini.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Profil --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
                <div class="text-center mb-5">
                    <img src="{{ auth()->user()->fotoUrl() }}"
                         alt="{{ auth()->user()->name }}"
                         class="w-20 h-20 rounded-2xl object-cover mx-auto mb-3 border-4 border-emerald-50 shadow-sm" />
                    <h3 class="font-semibold text-stone-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-stone-500">{{ auth()->user()->email }}</p>
                    <span class="inline-flex mt-3 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full">
                        Anggota
                    </span>
                </div>

                <div class="border-t border-stone-100 pt-4 space-y-2">
                    <a href="{{ route('anggota.profil.edit') }}"
                       class="flex items-center gap-3 w-full px-3 py-3 text-sm font-medium text-stone-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-xl transition-colors">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-stone-50 text-stone-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </span>
                        Edit Profil
                    </a>
                    <a href="{{ route('anggota.peminjaman.index') }}"
                       class="flex items-center gap-3 w-full px-3 py-3 text-sm font-medium text-stone-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-xl transition-colors">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-stone-50 text-stone-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </span>
                        Riwayat Peminjaman
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 w-full px-3 py-3 text-sm font-medium text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </span>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            {{-- Statistik Anggota --}}
            <div class="bg-emerald-900 text-white rounded-2xl p-6 shadow-md shadow-emerald-900/10">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-200">Ringkasan</p>
                        <h3 class="font-playfair text-xl font-bold text-white mt-1">Statistik Saya</h3>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-emerald-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17a4 4 0 100-8 4 4 0 000 8zm0 0v4m0-16V3m4.95 13.95l2.83 2.83M3.22 4.22l2.83 2.83m0 9.9l-2.83 2.83M18.78 4.22l-2.83 2.83"/>
                        </svg>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-white/10 p-4">
                        <p class="text-2xl font-bold">{{ $totalPinjam }}</p>
                        <p class="text-xs text-emerald-100 mt-1">Total Peminjaman</p>
                    </div>
                    <div class="rounded-xl bg-white/10 p-4">
                        <p class="text-2xl font-bold">{{ $totalKembali }}</p>
                        <p class="text-xs text-emerald-100 mt-1">Berhasil Kembali</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                <h3 class="font-semibold text-stone-900">Temukan Buku Baru</h3>
                <p class="text-sm text-stone-600 mt-1 mb-4">Lihat koleksi yang tersedia dan ajukan peminjaman berikutnya.</p>
                <a href="{{ route('anggota.buku.index') }}"
                   class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                    Lihat Koleksi
                </a>
            </div>
        </div>
    </div>

</x-layouts.anggota>
