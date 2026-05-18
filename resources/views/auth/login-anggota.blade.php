<x-layouts.publik title="Masuk sebagai Anggota">
    <div class="min-h-screen bg-gradient-to-br from-stone-100 to-emerald-50 flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">

            {{-- Header --}}
            <div class="text-center mb-8">
                <a href="{{ route('beranda') }}" class="inline-flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-emerald-700 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                </a>
                <h1 class="font-playfair text-3xl font-bold text-stone-800 mb-1">Selamat Datang</h1>
                <p class="text-stone-500 text-sm">Masuk ke akun anggota perpustakaan Anda</p>
            </div>

            {{-- Kartu Form --}}
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-stone-100">
                <x-shared.alert />

                <form method="POST" action="{{ route('anggota.login.proses') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">
                            Alamat Email
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition
                                   @error('email') border-red-300 bg-red-50 @else border-stone-300 @enderror"
                            placeholder="email@sekolah.ac.id"
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
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            placeholder="••••••••"
                        />
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="ingat_saya" class="w-4 h-4 text-emerald-600 border-stone-300 rounded" />
                            <span class="text-sm text-stone-600">Ingat saya</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl transition-colors"
                    >
                        Masuk
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-stone-100 text-center">
                    <p class="text-sm text-stone-500">
                        Apakah Anda pustakawan?
                        <a href="{{ route('pustakawan.login') }}" class="text-emerald-700 font-medium hover:underline">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('beranda') }}" class="text-sm text-stone-500 hover:text-emerald-700 transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-layouts.publik>