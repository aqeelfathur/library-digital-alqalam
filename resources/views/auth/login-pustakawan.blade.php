<x-layouts.auth title="Masuk sebagai Pustakawan">
    <div class="min-h-screen bg-gradient-to-br from-emerald-900 to-teal-900 flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">

                <h1 class="font-playfair text-3xl font-bold text-white mb-1">Masuk Otomasi Perpustakaan | Perpustakaan Al Qalam SMA Muhammadiyah 2 Surabaya</h1>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <x-shared.alert />

                <form method="POST" action="{{ route('pustakawan.login.proses') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">
                            Email Pustakawan
                        </label>
                        <input
                            id="email" name="email" type="email"
                            value="{{ old('email') }}"
                            required autofocus
                            class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition @error('email') border-red-300 bg-red-50 @enderror"
                            placeholder="pustakawan@sekolah.ac.id"
                        />
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-stone-700 mb-1.5">
                            Kata Sandi
                        </label>
                        <input
                            id="password" name="password" type="password"
                            required autocomplete="current-password"
                            class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="ingat_saya" id="ingat_saya" class="w-4 h-4 text-emerald-600 border-stone-300 rounded" />
                        <label for="ingat_saya" class="text-sm text-stone-600">Ingat saya</label>
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold rounded-xl transition-colors">
                        Masuk ke Panel
                    </button>
                </form>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('beranda') }}" class="text-sm text-emerald-300 hover:text-white transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-layouts.publik>