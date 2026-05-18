<x-layouts.publik title="Pustakawan — Perpustakaan Al-Qalam">

    <div class="bg-emerald-800 py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="font-playfair text-4xl font-bold text-white mb-3">Tim Pustakawan</h1>
            <p class="text-emerald-200">Kenali tim pustakawan yang siap membantu kebutuhan literasi Anda.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($pustakawan->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($pustakawan as $p)
                    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6 flex items-center gap-5">
                        <img src="{{ $p->fotoUrl() }}"
                             alt="{{ $p->name }}"
                             class="w-20 h-20 rounded-full object-cover flex-shrink-0 border-4 border-emerald-100" />
                        <div>
                            <h3 class="font-semibold text-stone-800 text-lg">{{ $p->name }}</h3>
                            <p class="text-sm text-emerald-700 font-medium mb-2">Pustakawan</p>
                            <p class="text-sm text-stone-500">{{ $p->email }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-shared.kosong pesan="Tidak ada pustakawan yang ditampilkan saat ini." />
        @endif
    </div>

</x-layouts.publik>