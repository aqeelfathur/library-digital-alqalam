@props(['buku'])

<div class="flex gap-3 group">
    <img
        src="{{ $buku->sampulUrl() }}"
        alt="{{ $buku->title }}"
        class="w-10 h-14 object-cover rounded-lg flex-shrink-0 shadow-sm border border-stone-100"
        loading="lazy"
    />
    <div class="flex-1 min-w-0">
        <p class="text-xs font-semibold text-stone-800 line-clamp-2 leading-snug group-hover:text-emerald-700 transition-colors">
            {{ $buku->title }}
        </p>
        <p class="text-xs text-stone-500 mt-0.5 truncate">{{ $buku->author }}</p>
        <span @class([
            'inline-block text-xs mt-1 px-1.5 py-0.5 rounded',
            'bg-green-100 text-green-700'   => $buku->status === 'tersedia',
            'bg-yellow-100 text-yellow-700' => $buku->status === 'dipinjam',
            'bg-stone-100 text-stone-500'   => !in_array($buku->status, ['tersedia', 'dipinjam']),
        ])>
            {{ $buku->labelStatus() }}
        </span>
    </div>
</div>