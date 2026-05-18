<aside
    class="w-64 bg-emerald-900 text-white flex flex-col flex-shrink-0 transition-transform duration-300"
    :class="{ '-translate-x-full': !sidebarTerbuka, 'translate-x-0': sidebarTerbuka }"
>
    {{-- Logo --}}
    <div class="p-6 border-b border-emerald-800">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <div>
                <p class="font-playfair font-bold text-white leading-none">Al-Qalam</p>
                <p class="text-xs text-emerald-300 leading-none mt-0.5">Panel Pustakawan</p>
            </div>
        </div>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        @php
        $menu = [
            ['rute' => 'pustakawan.dasbor',          'label' => 'Dasbor',          'ikon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['rute' => 'pustakawan.buku.index',       'label' => 'Koleksi Buku',    'ikon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['rute' => 'pustakawan.kategori.index',   'label' => 'Kategori',        'ikon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z'],
            ['rute' => 'pustakawan.peminjaman.index', 'label' => 'Peminjaman',      'ikon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
            ['rute' => 'pustakawan.anggota.index',    'label' => 'Anggota',         'ikon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ['rute' => 'pustakawan.berita.index',     'label' => 'Berita',          'ikon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
            ['rute' => 'pustakawan.informasi.edit',   'label' => 'Informasi',       'ikon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
        @endphp

        @foreach($menu as $item)
            <a
                href="{{ route($item['rute']) }}"
                @class([
                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                    'bg-emerald-700 text-white' => request()->routeIs($item['rute']),
                    'text-emerald-200 hover:bg-emerald-800 hover:text-white' => !request()->routeIs($item['rute']),
                ])
            >
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['ikon'] }}" />
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    {{-- Profil & Keluar --}}
    <div class="p-4 border-t border-emerald-800">
        <div class="flex items-center gap-3 mb-3">
            <img src="{{ auth()->user()->fotoUrl() }}" alt="Foto" class="w-8 h-8 rounded-full object-cover" />
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-emerald-400">Pustakawan</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-2 text-sm text-emerald-300 hover:text-white hover:bg-emerald-800 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>