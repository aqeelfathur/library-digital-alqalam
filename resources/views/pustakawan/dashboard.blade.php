<x-layouts.pustakawan title="Dasbor">

    {{-- Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <x-shared.kartu-statistik
            judul="Total Buku"
            :nilai="$statistik['total_buku']"
            ikon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
            warna="emerald"
        />
        <x-shared.kartu-statistik
            judul="Total Anggota"
            :nilai="$statistik['total_anggota']"
            ikon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
            warna="blue"
        />
        <x-shared.kartu-statistik
            judul="Peminjaman Aktif"
            :nilai="$statistik['peminjaman_aktif']"
            ikon="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
            warna="amber"
        />
        <x-shared.kartu-statistik
            judul="Buku Tersedia"
            :nilai="$statistik['buku_tersedia']"
            ikon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            warna="emerald"
        />
        <x-shared.kartu-statistik
            judul="Dikembalikan Bulan Ini"
            :nilai="$statistik['dikembalikan_bulan_ini']"
            ikon="M5 13l4 4L19 7"
            warna="blue"
        />
        <x-shared.kartu-statistik
            judul="Peminjaman Hari Ini"
            :nilai="$statistik['peminjaman_hari_ini']"
            ikon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
            warna="amber"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Peminjaman Terbaru --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-stone-800">Peminjaman Terbaru</h2>
                    <a href="{{ route('pustakawan.peminjaman.index') }}" class="text-sm text-emerald-700 hover:underline">
                        Lihat semua
                    </a>
                </div>

                @if($peminjamanTerbaru->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-stone-100">
                                    <th class="text-left pb-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Anggota</th>
                                    <th class="text-left pb-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Buku</th>
                                    <th class="text-left pb-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Status</th>
                                    <th class="text-left pb-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-50">
                                @foreach($peminjamanTerbaru as $item)
                                    <tr class="hover:bg-stone-50 transition-colors">
                                        <td class="py-3">
                                            <div class="flex items-center gap-2">
                                                <img src="{{ $item->anggota->fotoUrl() }}"
                                                     alt="{{ $item->anggota->name }}"
                                                     class="w-7 h-7 rounded-full object-cover flex-shrink-0" />
                                                <span class="font-medium text-stone-800 truncate max-w-[100px]">
                                                    {{ $item->anggota->name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3 text-stone-600 truncate max-w-[120px]">
                                            {{ $item->buku->title }}
                                        </td>
                                        <td class="py-3">
                                            <span @class([
                                                'text-xs font-medium px-2 py-0.5 rounded-full',
                                                'bg-yellow-100 text-yellow-700' => $item->status === 'pending',
                                                'bg-blue-100 text-blue-700'     => $item->status === 'approved',
                                                'bg-green-100 text-green-700'   => $item->status === 'borrowed',
                                                'bg-stone-100 text-stone-600'   => $item->status === 'returned',
                                                'bg-red-100 text-red-700'       => in_array($item->status, ['late', 'rejected']),
                                            ])>
                                                {{ $item->labelStatus() }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-stone-500">
                                            {{ $item->created_at->translatedFormat('d M Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-shared.kosong pesan="Belum ada peminjaman." />
                @endif
            </div>
        </div>

        {{-- Log Aktivitas --}}
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                <h2 class="font-semibold text-stone-800 mb-4">Aktivitas Terkini</h2>
                @if($logAktivitas->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($logAktivitas as $log)
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-stone-700 leading-snug">{{ $log->description }}</p>
                                    <p class="text-xs text-stone-400 mt-0.5">
                                        {{ $log->pengguna?->name ?? 'Sistem' }} &middot; {{ $log->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-shared.kosong pesan="Belum ada aktivitas." />
                @endif
            </div>
        </div>
    </div>

</x-layouts.pustakawan>