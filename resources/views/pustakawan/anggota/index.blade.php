<x-layouts.pustakawan title="Manajemen Anggota">

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-4 mb-6">
        <form method="GET" action="{{ route('pustakawan.anggota.index') }}" class="flex flex-wrap gap-3">
            <input name="cari" value="{{ request('cari') }}" type="text"
                   placeholder="Cari nama atau email anggota..."
                   class="px-4 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 flex-1 min-w-48" />
            <select name="status" class="px-4 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-emerald-700 text-white text-sm rounded-lg hover:bg-emerald-800 transition-colors">Filter</button>
            @if(request()->hasAny(['cari', 'status']))
                <a href="{{ route('pustakawan.anggota.index') }}"
                   class="px-4 py-2 border border-stone-300 text-stone-600 text-sm rounded-lg hover:bg-stone-50 transition-colors">Reset</a>
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
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Total Pinjam</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Terdaftar</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($anggota as $item)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->fotoUrl() }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0" />
                                    <div>
                                        <p class="font-medium text-stone-800">{{ $item->name }}</p>
                                        <p class="text-xs text-stone-500">{{ $item->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span @class([
                                    'text-xs font-medium px-2 py-1 rounded-full',
                                    'bg-green-100 text-green-700'  => $item->status === 'aktif',
                                    'bg-stone-100 text-stone-600'  => $item->status === 'nonaktif',
                                    'bg-red-100 text-red-700'      => $item->status === 'suspended',
                                ])>
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-stone-600">{{ $item->peminjaman_count }}</td>
                            <td class="px-6 py-4 text-stone-500 text-xs">
                                {{ $item->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2" x-data="{ buka: false }">

                                    {{-- Ubah Status --}}
                                    <div class="relative">
                                        <button @click="buka = !buka"
                                                class="px-3 py-1.5 text-xs border border-stone-300 text-stone-600 rounded-lg hover:bg-stone-50 transition-colors">
                                            Ubah Status
                                        </button>
                                        <div x-show="buka" @click.outside="buka = false"
                                             class="absolute right-0 mt-1 bg-white border border-stone-200 rounded-lg shadow-lg z-10 py-1 min-w-[140px]">
                                            @foreach(['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif', 'suspended' => 'Tangguhkan'] as $val => $label)
                                                @if($item->status !== $val)
                                                    <form method="POST" action="{{ route('pustakawan.anggota.ubah-status', $item) }}">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ $val }}" />
                                                        <button type="submit"
                                                                class="w-full text-left px-4 py-2 text-xs text-stone-700 hover:bg-stone-50 transition-colors">
                                                            {{ $label }}
                                                        </button>
                                                    </form>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Reset Kata Sandi --}}
                                    <form method="POST" action="{{ route('pustakawan.anggota.atur-ulang-kata-sandi', $item) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs bg-amber-50 text-amber-700 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors"
                                                onclick="return confirm('Reset kata sandi {{ addslashes($item->name) }} ke default?')">
                                            Reset Sandi
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16">
                                <x-shared.kosong pesan="Tidak ada anggota ditemukan." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($anggota->hasPages())
            <div class="px-6 py-4 border-t border-stone-100">{{ $anggota->links() }}</div>
        @endif
    </div>

</x-layouts.pustakawan>