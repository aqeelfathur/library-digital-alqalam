@props([
    'label'       => '',
    'nama'        => '',
    'tipe'        => 'text',
    'placeholder' => '',
    'bantuan'     => '',
    'wajib'       => false,
])

<div {{ $attributes->only('class') }}>
    @if($label)
        <label for="{{ $nama }}" class="block text-sm font-medium text-stone-700 mb-1.5">
            {{ $label }}
            @if($wajib)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <input
        id="{{ $nama }}"
        name="{{ $nama }}"
        type="{{ $tipe }}"
        placeholder="{{ $placeholder }}"
        @if($wajib) required @endif
        {{ $attributes->except(['class', 'label', 'nama', 'tipe', 'placeholder', 'bantuan', 'wajib'])->merge([
            'class' => 'w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder:text-stone-400 ' . ($errors->has($nama) ? 'border-red-300 bg-red-50' : 'border-stone-300')
        ]) }}
    />

    @error($nama)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror

    @if($bantuan && !$errors->has($nama))
        <p class="mt-1 text-xs text-stone-400">{{ $bantuan }}</p>
    @endif
</div>