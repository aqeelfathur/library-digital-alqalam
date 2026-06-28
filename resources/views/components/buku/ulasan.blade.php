@props([
    'buku',
    'ulasanSaya' => null,
    'bolehMengulas' => false,
])

@php
    $jumlahUlasan = $buku->ulasan_count ?? $buku->ulasan->count();
    $rataRating = $buku->ulasan_avg_rating ?? $buku->ulasan->avg('rating');
    $rataRatingTampil = $rataRating ? number_format($rataRating, 1, ',', '.') : '0,0';
@endphp

<section class="mt-8 bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden">
    <div class="p-6 border-b border-stone-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-stone-800">Ulasan Buku</h2>
                <p class="text-sm text-stone-500 mt-1">Komentar dan penilaian dari anggota perpustakaan.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex text-amber-400 text-lg leading-none" aria-label="Rating rata-rata {{ $rataRatingTampil }}">
                    @for($i = 1; $i <= 5; $i++)
                        <span>{{ $rataRating && $i <= round($rataRating) ? '★' : '☆' }}</span>
                    @endfor
                </div>
                <div>
                    <p class="text-sm font-semibold text-stone-800">{{ $rataRatingTampil }} / 5</p>
                    <p class="text-xs text-stone-500">{{ $jumlahUlasan }} ulasan</p>
                </div>
            </div>
        </div>
    </div>

    @if($bolehMengulas)
        <div class="p-6 border-b border-stone-100 bg-stone-50">
            <form method="POST" action="{{ route('anggota.buku.ulasan.simpan', $buku) }}" class="space-y-4">
                @csrf

                <div>
                    <label for="rating" class="block text-sm font-medium text-stone-700 mb-1">Rating</label>
                    <select
                        id="rating"
                        name="rating"
                        class="w-full sm:w-56 px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        required
                    >
                        <option value="">Pilih bintang</option>
                        @for($rating = 5; $rating >= 1; $rating--)
                            <option value="{{ $rating }}" @selected((int) old('rating', $ulasanSaya?->rating) === $rating)>
                                {{ $rating }} bintang
                            </option>
                        @endfor
                    </select>
                    @error('rating')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="comment" class="block text-sm font-medium text-stone-700 mb-1">Komentar</label>
                    <textarea
                        id="comment"
                        name="comment"
                        rows="4"
                        maxlength="1000"
                        class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        placeholder="Bagikan pendapatmu tentang buku ini..."
                        required
                    >{{ old('comment', $ulasanSaya?->comment) }}</textarea>
                    @error('comment')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-700 text-white font-semibold rounded-lg hover:bg-emerald-800 transition-colors text-sm"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $ulasanSaya ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
                </button>
            </form>
        </div>
    @endif

    <div class="divide-y divide-stone-100">
        @forelse($buku->ulasan as $ulasan)
            <article class="p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-semibold text-stone-800">{{ $ulasan->user->name }}</p>
                            <span class="text-xs text-stone-400">{{ $ulasan->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex text-amber-400 text-sm mt-1" aria-label="{{ $ulasan->rating }} dari 5 bintang">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= $ulasan->rating ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-sm text-stone-600 leading-relaxed mt-3">{{ $ulasan->comment }}</p>
            </article>
        @empty
            <div class="p-6 text-center">
                <p class="text-sm text-stone-500">Belum ada ulasan untuk buku ini.</p>
            </div>
        @endforelse
    </div>
</section>
