<x-layouts.pustakawan title="Koleksi Buku">

    {{-- Header Aksi --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <p class="text-sm text-stone-500">Kelola seluruh koleksi buku perpustakaan</p>
        </div>
        <a href="{{ route('pustakawan.buku.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Buku
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-4 mb-6">
        <form method="GET" action="{{ route('pustakawan.buku.index') }}" class="flex flex-wrap gap-3">
            <input
                name="cari"
                value="{{ request('cari') }}"
                type="text"
                placeholder="Cari judul, penulis, ISBN..."
                class="px-4 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 flex-1 min-w-48"
            />
            <select name="kategori" class="px-4 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                        {{ $kat->name }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                @foreach(['tersedia' => 'Tersedia', 'dipinjam' => 'Dipinjam', 'maintenance' => 'Perawatan', 'hilang' => 'Hilang'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-4 py-2 bg-emerald-700 text-white text-sm rounded-lg hover:bg-emerald-800 transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['cari', 'kategori', 'status']))
                <a href="{{ route('pustakawan.buku.index') }}"
                   class="px-4 py-2 border border-stone-300 text-stone-600 text-sm rounded-lg hover:bg-stone-50 transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel Buku --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 border-b border-stone-200">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Buku</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Kategori</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">No. Panggil</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Ditambahkan</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($buku as $item)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->sampulUrl() }}"
                                         alt="{{ $item->title }}"
                                         class="w-10 h-14 object-cover rounded shadow-sm flex-shrink-0" />
                                    <div class="min-w-0">
                                        <p class="font-medium text-stone-800 max-w-[200px] truncate leading-snug">
                                            {{ $item->title }}
                                        </p>
                                        <p class="text-xs text-stone-500">{{ $item->author }}</p>
                                        @if($item->isbn_issn)
                                            <p class="text-xs text-stone-400">ISBN: {{ $item->isbn_issn }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-medium">
                                    {{ $item->kategori->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-stone-500 font-mono text-xs">
                                {{ $item->call_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span @class([
                                    'text-xs font-medium px-2 py-1 rounded-full',
                                    'bg-green-100 text-green-700'   => $item->status === 'tersedia',
                                    'bg-yellow-100 text-yellow-700' => $item->status === 'dipinjam',
                                    'bg-orange-100 text-orange-700' => $item->status === 'maintenance',
                                    'bg-red-100 text-red-700'       => $item->status === 'hilang',
                                ])>
                                    {{ $item->labelStatus() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-stone-500 text-xs">
                                {{ $item->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('pustakawan.buku.edit', $item) }}"
                                       class="p-1.5 text-stone-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('pustakawan.buku.destroy', $item) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-stone-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus buku \'{{ addslashes($item->title) }}\'?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16">
                                <x-shared.kosong pesan="Tidak ada buku ditemukan." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($buku->hasPages())
            <div class="px-6 py-4 border-t border-stone-100">
                {{ $buku->links() }}
            </div>
        @endif
    </div>

</x-layouts.pustakawan>