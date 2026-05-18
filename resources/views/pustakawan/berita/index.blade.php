<x-layouts.pustakawan title="Manajemen Berita">

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-stone-500">Kelola berita dan pengumuman perpustakaan</p>
        <a href="{{ route('pustakawan.berita.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tulis Berita
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 border-b border-stone-200">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Berita</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Tanggal Terbit</th>
                        <th class="text-left px-6 py-3 font-medium text-stone-500 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($berita as $item)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->thumbnailUrl() }}" class="w-14 h-10 object-cover rounded-lg flex-shrink-0" />
                                    <div>
                                        <p class="font-medium text-stone-800 max-w-xs truncate">{{ $item->title }}</p>
                                        <p class="text-xs text-stone-400">/berita/{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->published_at && $item->published_at->isPast())
                                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 text-green-700">Terbit</span>
                                @elseif($item->published_at)
                                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-700">Terjadwal</span>
                                @else
                                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-stone-100 text-stone-600">Draf</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-stone-500 text-xs">
                                {{ $item->published_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('pustakawan.berita.edit', $item) }}"
                                       class="p-1.5 text-stone-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('pustakawan.berita.destroy', $item) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-stone-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                onclick="return confirm('Hapus berita ini?')">
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
                            <td colspan="4" class="py-16">
                                <x-shared.kosong pesan="Belum ada berita." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($berita->hasPages())
            <div class="px-6 py-4 border-t border-stone-100">{{ $berita->links() }}</div>
        @endif
    </div>

</x-layouts.pustakawan>