@props(['areaAnggota' => false])

<nav
    class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-stone-200 shadow-sm"
    x-data="{ menuTerbuka: false }"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('beranda') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 bg-emerald-700 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <p class="font-playfair font-bold text-emerald-800 text-lg leading-none">Al-Qalam</p>
                    <p class="text-xs text-stone-500 leading-none mt-0.5">Perpustakaan Digital</p>
                </div>
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('beranda') }}"
                   class="text-sm font-medium {{ request()->routeIs('beranda') ? 'text-emerald-700' : 'text-stone-600 hover:text-emerald-700' }} transition-colors">
                    Beranda
                </a>
                <a href="{{ route('informasi') }}"
                   class="text-sm font-medium {{ request()->routeIs('informasi') ? 'text-emerald-700' : 'text-stone-600 hover:text-emerald-700' }} transition-colors">
                    Informasi
                </a>
                <a href="{{ route('berita.index') }}"
                   class="text-sm font-medium {{ request()->routeIs('berita.*') ? 'text-emerald-700' : 'text-stone-600 hover:text-emerald-700' }} transition-colors">
                    Berita
                </a>
                <a href="{{ route('pustakawan.profil') }}"
                   class="text-sm font-medium {{ request()->routeIs('pustakawan.profil') ? 'text-emerald-700' : 'text-stone-600 hover:text-emerald-700' }} transition-colors">
                    Pustakawan
                </a>
                <a href="{{ route('bantuan') }}"
                   class="text-sm font-medium {{ request()->routeIs('bantuan') ? 'text-emerald-700' : 'text-stone-600 hover:text-emerald-700' }} transition-colors">
                    Bantuan
                </a>
            </div>

            {{-- Aksi --}}
            <div class="hidden md:flex items-center gap-3">
                @auth
                    @if(auth()->user()->isAnggota())
                        <a href="{{ route('anggota.dasbor') }}"
                           class="text-sm font-medium text-emerald-700 hover:text-emerald-800 transition-colors">
                            Dasbor Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-sm px-4 py-2 border border-stone-300 rounded-lg text-stone-600 hover:bg-stone-100 transition-colors">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('pustakawan.dasbor') }}"
                           class="text-sm font-medium text-emerald-700 hover:text-emerald-800 transition-colors">
                            Panel Pustakawan
                        </a>
                    @endif
                @else
                    <a href="{{ route('anggota.login') }}"
                       class="text-sm font-medium text-stone-600 hover:text-emerald-700 transition-colors">
                        Masuk
                    </a>
                    
                @endauth
            </div>

            {{-- Tombol Mobile --}}
            <button @click="menuTerbuka = !menuTerbuka" class="md:hidden p-2 rounded-lg text-stone-600 hover:bg-stone-100">
                <svg x-show="!menuTerbuka" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="menuTerbuka" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Menu Mobile --}}
    <div x-show="menuTerbuka" x-collapse class="md:hidden border-t border-stone-200 bg-white">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ route('beranda') }}" class="block px-3 py-2 text-sm text-stone-700 hover:bg-stone-100 rounded-lg">Beranda</a>
            <a href="{{ route('informasi') }}" class="block px-3 py-2 text-sm text-stone-700 hover:bg-stone-100 rounded-lg">Informasi</a>
            <a href="{{ route('berita.index') }}" class="block px-3 py-2 text-sm text-stone-700 hover:bg-stone-100 rounded-lg">Berita</a>
            <a href="{{ route('pustakawan.profil') }}" class="block px-3 py-2 text-sm text-stone-700 hover:bg-stone-100 rounded-lg">Pustakawan</a>
            <a href="{{ route('bantuan') }}" class="block px-3 py-2 text-sm text-stone-700 hover:bg-stone-100 rounded-lg">Bantuan</a>
            <div class="pt-2 border-t border-stone-100 flex gap-2">
                <a href="{{ route('anggota.login') }}" class="flex-1 text-center py-2 text-sm border border-stone-300 rounded-lg">Masuk</a>
            </div>
        </div>
    </div>
</nav>