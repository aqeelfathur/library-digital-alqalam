<footer class="bg-emerald-900 text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            {{-- Brand --}}
            <div class="md:col-span-1">
                <div class="gap-3 mb-4">
                    <img
                        src="{{ asset('images/smamda-logo.png') }}"
                        alt="Logo"
                        class="w-16 h-16 object-contain"
                    />
                </div>
                <p class="font-playfair font-bold text-white text-lg leading-none">Perpustakaan Al Qalam SMA Muhammadiyah 2 Surabaya</p>
            </div>

            {{-- Tautan Cepat --}}
            <div>
                <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Tautan Cepat</h4>
                <ul class="space-y-2">
                    @foreach([['beranda', 'Beranda'], ['explore', 'Koleksi'], ['informasi', 'Informasi'], ['berita.index', 'Berita'], ['bantuan', 'Bantuan'], ['pustakawan.profil', 'Pustakawan']] as [$rute, $label])
                        <li>
                            <a href="{{ route($rute) }}" class="text-sm text-emerald-300 hover:text-white transition-colors">
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Area Pengguna --}}
            <div>
                <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Area Pengguna</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('anggota.login') }}" class="text-sm text-emerald-300 hover:text-white transition-colors">
                            Masuk sebagai Anggota
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pustakawan.login') }}" class="text-sm text-emerald-300 hover:text-white transition-colors">
                            Masuk sebagai Pustakawan
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Kontak</h4>
                @php $info = \App\Models\InformasiPerpustakaan::ambil(); @endphp
                <ul class="space-y-3">
                    <li class="flex gap-2 text-sm text-emerald-200">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $info->address ?? '-' }}</span>
                    </li>
                    <li class="flex gap-2 text-sm text-emerald-200">
                        <svg class="w-4 h-4 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>{{ $info->phone ?? '-' }}</span>
                    </li>
                    <li class="flex gap-2 text-sm text-emerald-200">
                        <svg class="w-4 h-4 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $info->operational_hours ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-emerald-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs text-emerald-400">
                &copy; {{ date('Y') }} Perpustakaan Al-Qalam. Hak cipta dilindungi.
            </p>
            <p class="text-xs text-emerald-500">Dibangun dengan Laravel & TailwindCSS</p>
        </div>
    </div>
</footer>