<x-layouts.pustakawan title="Manajemen Peminjaman">

    {{-- Filter & Cari --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-4 mb-6">
        <form method="GET" action="{{ route('pustakawan.peminjaman.index') }}" class="flex flex-wrap gap-3">
            <input
                name="cari"
                value="{{ request('cari') }}"
                type="text"
                placeholder="Cari anggota atau buku..."
                class="px-4 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 flex-1 min-w-48"
            />
            <select name="status" class="px-4 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'borrowed' => 'Dipinjam', 'returned' => 'Dikembalikan', 'late' => 'Terlambat', 'rejected' => 'Ditolak'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-4 py-2 bg-emerald-700 text-white text-sm rounded-lg hover:bg-emerald-800 transition-colors">
                Filter
            </button>
            @if(request('cari') || request('status'))
                <a href="{{ route('pustakawan.peminjaman.index') }}"
                   class="px-4 py-2 border border-stone-300 text-stone-600 text-sm rounded-lg hover:bg-stone-50 transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 border-b border-stone-200">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Anggota</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Buku</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Batas Kembali</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($peminjaman as $item)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $item->anggota->fotoUrl() }}" class="w-8 h-8 rounded-full object-cover" />
                                    <div>
                                        <p class="font-medium text-stone-800">{{ $item->anggota->name }}</p>
                                        <p class="text-xs text-stone-500">{{ $item->anggota->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $item->buku->sampulUrl() }}" class="w-8 h-11 object-cover rounded" />
                                    <div>
                                        <p class="font-medium text-stone-800 max-w-[150px] truncate">{{ $item->buku->title }}</p>
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
                            <td class="px-6 py-4 text-stone-600">
                                {{ $item->borrowed_at?->translatedFormat('d M Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->due_date)
                                    <span @class([
                                        'text-sm',
                                        'text-red-600 font-medium' => $item->terlambat(),
                                        'text-stone-600' => !$item->terlambat(),
                                    ])>
                                        {{ $item->due_date->translatedFormat('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-stone-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($item->status === 'pending')
                                        <form method="POST" action="{{ route('pustakawan.peminjaman.setujui', $item) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="text-xs px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition-colors font-medium"
                                                    onclick="return confirm('Setujui peminjaman ini?')">
                                                Setujui
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('pustakawan.peminjaman.tolak', $item) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="text-xs px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium"
                                                    onclick="return confirm('Tolak peminjaman ini?')">
                                                Tolak
                                            </button>
                                        </form>
                                    @elseif($item->status === 'borrowed')
                                        <form method="POST" action="{{ route('pustakawan.peminjaman.kembalikan', $item) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="text-xs px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-medium"
                                                    onclick="return confirm('Tandai buku ini sebagai dikembalikan?')">
                                                Kembalikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-stone-400">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-stone-400 text-sm">
                                Tidak ada data peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($peminjaman->hasPages())
            <div class="px-6 py-4 border-t border-stone-100">
                {{ $peminjaman->links() }}
            </div>
        @endif
    </div>

</x-layouts.pustakawan>