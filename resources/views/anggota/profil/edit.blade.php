<x-layouts.anggota title="Edit Profil">

    <div class="mb-6">
        <h1 class="font-playfair text-2xl font-bold text-stone-800 mb-1">Edit Profil</h1>
        <p class="text-stone-500 text-sm">Perbarui informasi akun Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form Profil --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Informasi Pribadi --}}
            <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                <h2 class="font-semibold text-stone-800 mb-5">Informasi Pribadi</h2>

                <form method="POST" action="{{ route('anggota.profil.perbarui') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf @method('PATCH')

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Nama Lengkap</label>
                        <input name="name" type="text" value="{{ old('name', $pengguna->name) }}" required
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('name') border-red-300 bg-red-50 @enderror" />
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Alamat Email</label>
                        <input name="email" type="email" value="{{ old('email', $pengguna->email) }}" required
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('email') border-red-300 bg-red-50 @enderror" />
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="{ preview: '{{ $pengguna->fotoUrl() }}' }">
                        <label class="block text-sm font-medium text-stone-700 mb-2">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            <img :src="preview" class="w-16 h-16 rounded-full object-cover border-2 border-stone-200" />
                            <div>
                                <input
                                    name="image_url"
                                    type="file"
                                    accept="image/*"
                                    @change="
                                        const file = $event.target.files[0];
                                        if (file) preview = URL.createObjectURL(file);
                                    "
                                    class="block text-sm text-stone-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer"
                                />
                                <p class="mt-1 text-xs text-stone-400">JPEG, PNG, WebP. Maks. 1MB</p>
                            </div>
                        </div>
                        @error('image_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-5 py-2.5 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Ganti Kata Sandi --}}
            <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
                <h2 class="font-semibold text-stone-800 mb-5">Ganti Kata Sandi</h2>

                <form method="POST" action="{{ route('anggota.profil.ganti-kata-sandi') }}" class="space-y-4">
                    @csrf @method('PATCH')

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Kata Sandi Lama</label>
                        <input name="kata_sandi_lama" type="password" required
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('kata_sandi_lama') border-red-300 bg-red-50 @enderror" />
                        @error('kata_sandi_lama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Kata Sandi Baru</label>
                        <input name="kata_sandi_baru" type="password" required
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('kata_sandi_baru') border-red-300 bg-red-50 @enderror" />
                        @error('kata_sandi_baru') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                        <input name="kata_sandi_baru_confirmation" type="password" required
                               class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-5 py-2.5 bg-stone-800 text-white text-sm font-medium rounded-lg hover:bg-stone-900 transition-colors">
                            Ganti Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6 sticky top-6">
                <div class="text-center">
                    <img src="{{ $pengguna->fotoUrl() }}" class="w-20 h-20 rounded-full object-cover mx-auto mb-3 border-4 border-emerald-100" />
                    <h3 class="font-semibold text-stone-800">{{ $pengguna->name }}</h3>
                    <p class="text-sm text-stone-500">{{ $pengguna->email }}</p>
                    <span class="inline-block mt-2 text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                        Anggota Aktif
                    </span>
                </div>
                <div class="mt-5 pt-5 border-t border-stone-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-stone-500">Bergabung</span>
                        <span class="text-stone-700 font-medium">{{ $pengguna->created_at->translatedFormat('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.anggota>