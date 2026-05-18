<x-layouts.anggota title="Riwayat Peminjaman">

    <div class="mb-6">
        <h1 class="font-playfair text-2xl font-bold text-stone-800 mb-1">Riwayat Peminjaman</h1>
        <p class="text-stone-500 text-sm">Seluruh catatan peminjaman buku Anda</p>
    </div>

    {{-- Peminjaman Aktif --}}
    @php $aktif = auth()->user()->peminjamanAktif(); @endphp

    @if($aktif)
        <div class="mb-6 p-5 bg-emerald-50 border border-emerald-200 rounded-xl">
            <p class="text-sm font-semibold text-emerald-800 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Peminjaman Aktif Saat Ini
            </p>
            <div class="flex gap-4">
                <img src="{{ $aktif->buku->sampulUrl() }}"
                     alt="{{ $aktif->buku->title }}"
                     class="w-14 h-20 object-cover rounded-lg flex-shrink-0 shadow" />
                <div>
                    <p class="font-semibold text-stone-800">{{ $aktif->buku->title }}</p>
                    <p class="text-sm text-stone-500 mb-2">{{ $aktif->buku->author }}</p>
                    <div class="flex flex-wrap gap-3 text-xs">
                        <span @class([
                            'px-2 py-1 rounded-full font-medium',
                            'bg-yellow-100 text-yellow-700' => $aktif->status === 'pending',
                            'bg-blue-100 text-blue-700'     => $aktif->status === 'approved',
                            'bg-green-100 text-green-700'   => $aktif->status === 'borrowed',
                        ])>
                            {{ $aktif->labelStatus() }}
                        </span>
                        @if($aktif->due_date)
                            <span @class([
                                'px-2 py-1 rounded-full font-medium',
                                'bg-red-100 text-red-700'    => $aktif->terlambat(),
                                'bg-stone-100 text-stone-600' => !$aktif->terlambat(),
                            ])>
                                Batas: {{ $aktif->due_date->translatedFormat('d M Y') }}
                                @if($aktif->terlambat())
                                    (Terlambat!)
                                @else
                                    ({{ $aktif->sisaHari() }} hari lagi)
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Tabel Riwayat --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-stone-100">
            <h2 class="font-semibold text-stone-800">Semua Peminjaman</h2>
        </div>

        @if($peminjaman->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 border-b border-stone-200">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Buku</th>
                            <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Tanggal Ajuan</th>
                            <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Batas Kembali</th>
                            <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Dikembalikan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($peminjaman as $item)
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->buku->sampulUrl() }}"
                                             alt="{{ $item->buku->title }}"
                                             class="w-9 h-12 object-cover rounded flex-shrink-0 shadow-sm" />
                                        <div class="min-w-0">
                                            <p class="font-medium text-stone-800 truncate max-w-[200px]">
                                                {{ $item->buku->title }}
                                            </p>
                                            <p class="text-xs text-stone-500">{{ $item->buku->author }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'text-xs font-medium px-2 py-1 rounded-full',
                                        'bg-yellow-100 text-yellow-700' => $item->status === 'pending',
                                        'bg-blue-100 text-blue-700'     => $item->status === 'approved',
                                        'bg-green-100 text-green-700'   => $item->status === 'borrowed',
                                        'bg-stone-100 text-stone-600'   => $item->status === 'returned',
                                        'bg-red-100 text-red-700'       => in_array($item->status, ['late', 'rejected']),
                                    ])>
                                        {{ $item->labelStatus() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-stone-500 text-xs">
                                    {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->due_date)
                                        <span @class([
                                            'text-sm',
                                            'text-red-600 font-medium' => $item->terlambat(),
                                            'text-stone-600'           => !$item->terlambat(),
                                        ])>
                                            {{ $item->due_date->translatedFormat('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-stone-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-stone-600 text-xs">
                                    {{ $item->returned_at?->translatedFormat('d M Y') ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($peminjaman->hasPages())
                <div class="px-6 py-4 border-t border-stone-100">
                    {{ $peminjaman->links() }}
                </div>
            @endif
        @else
            <div class="py-16">
                <x-shared.kosong pesan="Anda belum pernah melakukan peminjaman buku." />
            </div>
        @endif
    </div>

</x-layouts.anggota>