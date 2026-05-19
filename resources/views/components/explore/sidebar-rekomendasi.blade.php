@props(['buku', 'kategori', 'filter', 'totalBuku'])

<div class="space-y-5 lg:sticky lg:top-20">

    {{-- Kategori Cepat --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-5">
        <h3 class="font-semibold text-stone-800 text-sm mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Telusuri Kategori
        </h3>
        <div class="space-y-1">
            <a href="{{ route('explore') }}"
               @class([
                   'flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors',
                   'bg-emerald-700 text-white' => !filled($filter['kategori']),
                   'text-stone-600 hover:bg-stone-50' => filled($filter['kategori']),
               ])>
                <span>Semua Kategori</span>
                <span @class([
                    'text-xs px-1.5 py-0.5 rounded',
                    'bg-emerald-600 text-white' => !filled($filter['kategori']),
                    'bg-stone-100 text-stone-500' => filled($filter['kategori']),
                ])>
                    {{ $totalBuku }}
                </span>
            </a>
            @foreach($kategori as $kat)
                <a href="{{ route('explore', ['kategori' => $kat->slug]) }}"
                   @class([
                       'flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors',
                       'bg-emerald-700 text-white' => $filter['kategori'] === $kat->slug,
                       'text-stone-600 hover:bg-stone-50' => $filter['kategori'] !== $kat->slug,
                   ])>
                    <span>{{ $kat->name }}</span>
                    <span @class([
                        'text-xs px-1.5 py-0.5 rounded',
                        'bg-emerald-600 text-white' => $filter['kategori'] === $kat->slug,
                        'bg-stone-100 text-stone-500' => $filter['kategori'] !== $kat->slug,
                    ])>
                        {{ $kat->buku_count }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Koleksi Populer --}}
    @if($buku->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-5">
            <h3 class="font-semibold text-stone-800 text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Koleksi Populer
            </h3>
            <div class="space-y-3">
                @foreach($buku->take(5) as $item)
                    <x-explore.kartu-rekomendasi :buku="$item" />
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t border-stone-100">
                <a href="{{ route('explore', ['urutan' => 'populer']) }}"
                   class="text-xs text-emerald-700 font-medium hover:underline flex items-center gap-1">
                    Lihat semua koleksi populer
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    @endif

    {{-- Koleksi Terbaru --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-5">
        <h3 class="font-semibold text-stone-800 text-sm mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Baru Ditambahkan
        </h3>
        @php
            $terbaru = \App\Models\Buku::with('kategori')->orderByDesc('created_at')->limit(4)->get();
        @endphp
        <div class="space-y-3">
            @foreach($terbaru as $item)
                <x-explore.kartu-rekomendasi :buku="$item" />
            @endforeach
        </div>
        <div class="mt-4 pt-4 border-t border-stone-100">
            <a href="{{ route('explore', ['urutan' => 'terbaru']) }}"
               class="text-xs text-emerald-700 font-medium hover:underline flex items-center gap-1">
                Lihat semua koleksi terbaru
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Bantuan Pencarian --}}
    <div class="bg-emerald-50 rounded-xl border border-emerald-100 p-5">
        <h3 class="font-semibold text-emerald-800 text-sm mb-2">Tips Pencarian</h3>
        <ul class="space-y-1.5 text-xs text-emerald-700">
            <li class="flex items-start gap-1.5">
                <span class="mt-0.5">•</span>
                Gunakan pencarian spesifik untuk hasil lebih tepat
            </li>
            <li class="flex items-start gap-1.5">
                <span class="mt-0.5">•</span>
                Kombinasikan beberapa filter sekaligus
            </li>
            <li class="flex items-start gap-1.5">
                <span class="mt-0.5">•</span>
                Cari berdasarkan ISBN untuk temukan buku tertentu
            </li>
            <li class="flex items-start gap-1.5">
                <span class="mt-0.5">•</span>
                Gunakan filter kategori untuk mempersempit hasil
            </li>
        </ul>
    </div>
</div>