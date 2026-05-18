<x-layouts.pustakawan title="Informasi Perpustakaan">

    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
        <h2 class="font-semibold text-stone-800 text-lg mb-6 pb-4 border-b border-stone-100">
            Kelola Informasi Perpustakaan
        </h2>

        <form method="POST" action="{{ route('pustakawan.informasi.perbarui') }}" class="space-y-5">
            @csrf @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('address', $informasi->address) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Nomor Telepon</label>
                    <input name="phone" type="text" value="{{ old('phone', $informasi->phone) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="+62 xxx-xxxx-xxxx" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Alamat Email</label>
                    <input name="email" type="email" value="{{ old('email', $informasi->email) }}"
                           class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="perpustakaan@sekolah.ac.id" />
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Jam Operasional</label>
                    <textarea name="operational_hours" rows="3"
                              class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"
                              placeholder="Contoh: Senin - Jumat: 08.00 - 16.00&#10;Sabtu: 08.00 - 12.00">{{ old('operational_hours', $informasi->operational_hours) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Informasi Keanggotaan</label>
                    <textarea name="membership_information" rows="4"
                              class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-y"
                              placeholder="Syarat dan ketentuan keanggotaan perpustakaan...">{{ old('membership_information', $informasi->membership_information) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Embed URL Google Maps</label>
                    <textarea name="maps_embed_url" rows="3"
                              class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none font-mono text-xs"
                              placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." ...></iframe>'>{{ old('maps_embed_url', $informasi->maps_embed_url) }}</textarea>
                    <p class="mt-1 text-xs text-stone-400">Salin kode embed dari Google Maps (Share > Embed a map).</p>
                </div>
            </div>

            <div class="border-t border-stone-100 pt-4 flex justify-end">
                <button type="submit"
                        class="px-5 py-2.5 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                    Simpan Informasi
                </button>
            </div>
        </form>
    </div>

</x-layouts.pustakawan>