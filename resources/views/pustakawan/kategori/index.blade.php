<x-layouts.pustakawan title="Manajemen Kategori">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form Tambah --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6 sticky top-6">
                <h2 class="font-semibold text-stone-800 mb-4">Tambah Kategori Baru</h2>
                <form method="POST" action="{{ route('pustakawan.kategori.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Nama Kategori</label>
                        <input
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('name') border-red-300 bg-red-50 @enderror"
                            placeholder="Masukkan nama kategori"
                        />
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit"
                            class="w-full py-2.5 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                        Tambah Kategori
                    </button>
                </form>
            </div>
        </div>

        {{-- Daftar Kategori --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-100">
                    <h2 class="font-semibold text-stone-800">Daftar Kategori</h2>
                </div>
                <div class="divide-y divide-stone-100">
                    @forelse($kategori as $kat)
                        <div class="px-6 py-4 flex items-center gap-4" x-data="{ edit: false }">
                            <div class="flex-1 min-w-0">
                                <div x-show="!edit">
                                    <p class="font-medium text-stone-800">{{ $kat->name }}</p>
                                    <p class="text-xs text-stone-500">{{ $kat->buku_count }} buku</p>
                                </div>
                                <form x-show="edit" method="POST" action="{{ route('pustakawan.kategori.update', $kat) }}" class="flex gap-2">
                                    @csrf @method('PUT')
                                    <input
                                        name="name"
                                        type="text"
                                        value="{{ $kat->name }}"
                                        class="flex-1 px-3 py-1.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    />
                                    <button type="submit"
                                            class="px-3 py-1.5 bg-emerald-700 text-white text-xs rounded-lg hover:bg-emerald-800 transition-colors">
                                        Simpan
                                    </button>
                                    <button type="button" @click="edit = false"
                                            class="px-3 py-1.5 border border-stone-300 text-stone-600 text-xs rounded-lg hover:bg-stone-50 transition-colors">
                                        Batal
                                    </button>
                                </form>
                            </div>
                            <div class="flex items-center gap-2" x-show="!edit">
                                <button @click="edit = true"
                                        class="p-1.5 text-stone-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                @if($kat->buku_count == 0)
                                    <form method="POST" action="{{ route('pustakawan.kategori.destroy', $kat) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-stone-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                onclick="return confirm('Hapus kategori ini?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="p-1.5 text-stone-300 cursor-not-allowed" title="Tidak dapat dihapus karena masih ada buku">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-6">
                            <x-shared.kosong pesan="Belum ada kategori." />
                        </div>
                    @endforelse
                </div>

                @if($kategori->hasPages())
                    <div class="px-6 py-4 border-t border-stone-100">
                        {{ $kategori->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-layouts.pustakawan>