<x-layouts.publik title="Kesalahan Server">
    <div class="min-h-[60vh] flex items-center justify-center">
        <div class="text-center px-4">
            <p class="text-8xl font-bold text-red-100 font-playfair mb-4">500</p>
            <h1 class="text-2xl font-bold text-stone-800 mb-2">Terjadi Kesalahan</h1>
            <p class="text-stone-500 mb-8">Mohon maaf, terjadi kesalahan pada server. Silakan coba beberapa saat lagi.</p>
            <a href="{{ route('beranda') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-700 text-white font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-layouts.publik>