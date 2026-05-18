@if(session('sukses'))
    <div
        x-data="{ tampil: true }"
        x-show="tampil"
        x-init="setTimeout(() => tampil = false, 5000)"
        x-transition
        class="mb-6 flex items-start gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl"
    >
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"/>
        </svg>
        <p class="text-sm font-medium">{{ session('sukses') }}</p>
        <button @click="tampil = false" class="ml-auto text-green-600 hover:text-green-800">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
@endif

@if(session('gagal') || $errors->any())
    <div
        x-data="{ tampil: true }"
        x-show="tampil"
        class="mb-6 flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl"
    >
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"/>
        </svg>
        <div class="flex-1">
            @if(session('gagal'))
                <p class="text-sm font-medium">{{ session('gagal') }}</p>
            @endif
            @if($errors->any())
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        <button @click="tampil = false" class="text-red-600 hover:text-red-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
@endif