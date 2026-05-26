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
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('beranda') }}" class="flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0">
                    <img
                        src="{{ asset('images/smamda-logo.png') }}"
                        alt="Logo"
                        class="w-12 h-12 object-contain"
                    />
                </div>
                <div>
                    <p class="font-playfair font-bold text-lg leading-none transition-colors duration-300"
                       :class="scrolled ? 'text-black' : 'text-white'">
                        Perpustakaan Al Qalam SMA Muhammadiyah 2 Surabaya
                    </p>
                    <p class="text-xs leading-none mt-0.5 transition-colors duration-300"
                       :class="scrolled ? 'text-stone-500' : 'text-white/70'">
                        Perpustakaan
                    </p>
                </div>
            </a>

            {{-- Menu Desktop --}}
            @php
            $menuNav = [
                ['rute' => 'beranda',           'label' => 'Beranda',    'aktif' => 'beranda'],
                ['rute' => 'explore',            'label' => 'Koleksi',   'aktif' => 'explore'],
                ['rute' => 'informasi',          'label' => 'Informasi', 'aktif' => 'informasi'],
                ['rute' => 'berita.index',       'label' => 'Berita',    'aktif' => 'berita.*'],
                ['rute' => 'pustakawan.profil',  'label' => 'Pustakawan','aktif' => 'pustakawan.profil'],
                ['rute' => 'bantuan',            'label' => 'Bantuan',   'aktif' => 'bantuan'],
            ];
            @endphp

            <div class="hidden md:flex items-center gap-6">
                @foreach($menuNav as $item)
                    <a
                        href="{{ route($item['rute']) }}"
                        class="text-sm font-medium transition-colors duration-300
                               {{ request()->routeIs($item['aktif'])
                                    ? 'text-emerald-300'
                                    : '' }}"
                        :class="!{{ request()->routeIs($item['aktif']) ? 'true' : 'false' }} && (scrolled ? 'text-stone-600 hover:text-emerald-700' : 'text-white/90 hover:text-white')"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Aksi Desktop --}}
            <div class="hidden md:flex items-center gap-3">
                @auth
                    @if(auth()->user()->isAnggota())
                        <a href="{{ route('anggota.dasbor') }}"
                           class="text-sm font-medium transition-colors duration-300"
                           :class="scrolled ? 'text-emerald-700 hover:text-emerald-800' : 'text-white/90 hover:text-white'">
                            Dasbor Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-sm px-4 py-2 rounded-lg transition-colors duration-300"
                                    :class="scrolled
                                        ? 'border border-stone-300 text-stone-600 hover:bg-stone-100'
                                        : 'border border-white/40 text-white hover:bg-white/10'">
                                Keluar
                            </button>
                        </form>
                    @elseif(auth()->user()->isPustakawan())
                        <a href="{{ route('pustakawan.dasbor') }}"
                           class="text-sm font-medium transition-colors duration-300"
                           :class="scrolled ? 'text-emerald-700 hover:text-emerald-800' : 'text-white/90 hover:text-white'">
                            Panel Pustakawan
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-sm px-4 py-2 rounded-lg transition-colors duration-300"
                                    :class="scrolled
                                        ? 'border border-stone-300 text-stone-600 hover:bg-stone-100'
                                        : 'border border-white/40 text-white hover:bg-white/10'">
                                Keluar
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('anggota.login') }}"
                       class="text-sm font-medium transition-colors duration-300"
                       :class="scrolled ? 'text-stone-600 hover:text-emerald-700' : 'text-white/90 hover:text-white'">
                        Masuk
                    </a>
                @endauth
            </div>

            {{-- Tombol Hamburger Mobile --}}
            <button
                @click="menuTerbuka = !menuTerbuka"
                class="md:hidden p-2 rounded-lg transition-colors duration-300"
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
    <div x-show="menuTerbuka" x-collapse x-cloak class="md:hidden border-t bg-white"
         :class="scrolled ? 'border-stone-200' : 'border-white/20'">
        <div class="px-4 py-4 space-y-1">
            @foreach($menuNav as $item)
                <a
                    href="{{ route($item['rute']) }}"
                    class="block px-3 py-2.5 text-sm rounded-lg transition-colors
                           {{ request()->routeIs($item['aktif'])
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
                            Dasbor Saya
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