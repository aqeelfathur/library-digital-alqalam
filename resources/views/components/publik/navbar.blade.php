{{-- resources/views/components/publik/navbar.blade.php --}}

@props(['areaAnggota' => false])

<nav
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
    x-data="{
        menuTerbuka: false,
        scrolled: false,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 40
            })
        }
    }"
    :class="scrolled
        ? 'bg-white/95 backdrop-blur border-b border-stone-200 shadow-sm'
        : 'bg-transparent border-b border-white/10'"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- Logo --}}
            <a href="{{ route('beranda') }}" class="flex min-w-0 items-center gap-3 group">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0">
                    <img
                        src="{{ asset('images/smamda-logo.png') }}"
                        alt="Logo"
                        class="w-12 h-12 object-contain"
                    />
                </div>
                <div class="min-w-0">
                    <p class="max-w-[220px] truncate font-playfair font-bold text-lg leading-none transition-colors duration-300 sm:max-w-[320px] lg:max-w-[360px] xl:max-w-[460px]"
                       :class="scrolled ? 'text-black' : 'text-white'">
                        Perpustakaan Al Qalam SMA Muhammadiyah 2 Surabaya
                    </p>
                    <p class="truncate text-xs leading-none mt-0.5 transition-colors duration-300"
                       :class="scrolled ? 'text-stone-500' : 'text-white/70'">
                        Perpustakaan
                    </p>
                </div>
            </a>

            {{-- Menu Desktop --}}
            @php
            $menuNav = [
                ['rute' => 'beranda',           'label' => 'Beranda',    'aktif' => 'beranda'],
                ['rute' => 'explore',            'label' => 'Koleksi',   'aktif' => ['explore', 'anggota.buku.*']],
                ['rute' => 'informasi',          'label' => 'Informasi', 'aktif' => 'informasi'],
                ['rute' => 'berita.index',       'label' => 'Berita',    'aktif' => 'berita.*'],
                ['rute' => 'pustakawan.profil',  'label' => 'Pustakawan','aktif' => 'pustakawan.profil'],
                ['rute' => 'bantuan',            'label' => 'Bantuan',   'aktif' => 'bantuan'],
            ];
            @endphp

            <div class="hidden lg:flex items-center gap-5 xl:gap-6">
                @foreach($menuNav as $item)
                    @php $sedangAktif = request()->routeIs(...(array) $item['aktif']); @endphp
                    <a
                        href="{{ route($item['rute']) }}"
                        class="whitespace-nowrap text-sm font-medium transition-colors duration-300
                               {{ $sedangAktif
                                    ? 'text-emerald-300'
                                    : '' }}"
                        :class="!{{ $sedangAktif ? 'true' : 'false' }} && (scrolled ? 'text-stone-600 hover:text-emerald-700' : 'text-white/90 hover:text-white')"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Aksi Desktop --}}
            <div class="hidden flex-shrink-0 lg:flex items-center">
                @auth
                    @if(auth()->user()->isAnggota())
                        <div class="inline-flex items-center gap-2 whitespace-nowrap">
                            <a href="{{ route('anggota.dasbor') }}"
                               class="inline-flex h-9 items-center justify-center rounded-lg px-2 text-sm font-medium transition-colors duration-300"
                               :class="scrolled ? 'text-emerald-700 hover:text-emerald-800' : 'text-white/90 hover:text-white'">
                                Dashboard Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit"
                                        class="inline-flex h-9 items-center justify-center rounded-lg px-4 text-sm transition-colors duration-300"
                                        :class="scrolled
                                            ? 'border border-stone-300 text-stone-600 hover:bg-stone-100'
                                            : 'border border-white/40 text-white hover:bg-white/10'">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @elseif(auth()->user()->isPustakawan())
                        <div class="inline-flex items-center gap-2 whitespace-nowrap">
                            <a href="{{ route('pustakawan.dasbor') }}"
                               class="inline-flex h-9 items-center justify-center rounded-lg px-2 text-sm font-medium transition-colors duration-300"
                               :class="scrolled ? 'text-emerald-700 hover:text-emerald-800' : 'text-white/90 hover:text-white'">
                                Panel Pustakawan
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit"
                                        class="inline-flex h-9 items-center justify-center rounded-lg px-4 text-sm transition-colors duration-300"
                                        :class="scrolled
                                            ? 'border border-stone-300 text-stone-600 hover:bg-stone-100'
                                            : 'border border-white/40 text-white hover:bg-white/10'">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <a href="{{ route('anggota.login') }}"
                       class="inline-flex h-9 items-center justify-center whitespace-nowrap rounded-lg px-2 text-sm font-medium transition-colors duration-300"
                       :class="scrolled ? 'text-stone-600 hover:text-emerald-700' : 'text-white/90 hover:text-white'">
                        Masuk
                    </a>
                @endauth
            </div>

            {{-- Tombol Hamburger Mobile --}}
            <button
                @click="menuTerbuka = !menuTerbuka"
                class="flex-shrink-0 p-2 rounded-lg transition-colors duration-300 lg:hidden"
                :class="scrolled ? 'text-stone-600 hover:bg-stone-100' : 'text-white hover:bg-white/10'"
                aria-label="Menu"
            >
                <svg x-show="!menuTerbuka" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="menuTerbuka" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Menu Mobile --}}
    <div x-show="menuTerbuka" x-collapse x-cloak class="lg:hidden border-t bg-white"
         :class="scrolled ? 'border-stone-200' : 'border-white/20'">
        <div class="px-4 py-4 space-y-1">
            @foreach($menuNav as $item)
                @php $sedangAktif = request()->routeIs(...(array) $item['aktif']); @endphp
                <a
                    href="{{ route($item['rute']) }}"
                    class="block px-3 py-2.5 text-sm rounded-lg transition-colors
                           {{ $sedangAktif
                                ? 'bg-emerald-50 text-emerald-700 font-medium'
                                : 'text-stone-700 hover:bg-stone-100' }}"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="pt-3 mt-1 border-t border-stone-100 space-y-2">
                @auth
                    @if(auth()->user()->isAnggota())
                        <a href="{{ route('anggota.dasbor') }}"
                           class="block px-3 py-2.5 text-sm text-center bg-emerald-700 text-white rounded-lg font-medium">
                            Dashboard Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full px-3 py-2.5 text-sm border border-stone-300 rounded-lg text-stone-600 hover:bg-stone-100 transition-colors">
                                Keluar
                            </button>
                        </form>
                    @elseif(auth()->user()->isPustakawan())
                        <a href="{{ route('pustakawan.dasbor') }}"
                           class="block px-3 py-2.5 text-sm text-center bg-emerald-700 text-white rounded-lg font-medium">
                            Panel Pustakawan
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full px-3 py-2.5 text-sm border border-stone-300 rounded-lg text-stone-600 hover:bg-stone-100 transition-colors">
                                Keluar
                            </button>
                        </form>
                    @endif
                @else
                    <div class="flex gap-2">
                        <a href="{{ route('anggota.login') }}"
                           class="flex-1 text-center py-2.5 text-sm border border-stone-300 rounded-lg text-stone-600 hover:bg-stone-50 transition-colors">
                            Masuk
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
