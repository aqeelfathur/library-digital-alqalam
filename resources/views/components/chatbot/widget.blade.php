{{--
    resources/views/components/chatbot/widget.blade.php
    Design mengikuti Al-Qalam: emerald primary, stone neutral,
    Playfair Display + Plus Jakarta Sans
--}}

<div
    id="sipus-widget"
    x-data="sipusChat()"
    x-init="init()"
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3"
>
    {{-- ── Chat Window ── --}}
    <div
        x-show="buka"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="w-[360px] bg-white rounded-2xl shadow-2xl border border-stone-200 flex flex-col overflow-hidden"
        style="height: 500px; display: none;"
    >
        {{-- Header --}}
        <div class="bg-emerald-800 px-4 py-3 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-700 border border-emerald-600 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    <img src="{{ asset('images/smamda-logo.png') }}" alt="Logo" class="w-7 h-7 object-contain">
                </div>
                <div>
                    <p class="text-white font-semibold text-sm leading-tight" style="font-family: 'Playfair Display', serif;">SIPUS</p>
                    <p class="text-emerald-300 text-xs flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
                        Asisten Perpustakaan Al-Qalam
                    </p>
                </div>
            </div>
            <button
                @click="buka = false"
                class="text-emerald-300 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-emerald-700"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div
            id="sipus-messages"
            class="flex-1 overflow-y-auto px-4 py-3 flex flex-col gap-3 bg-stone-50"
            style="scroll-behavior: smooth;"
        >
            {{-- Pesan sambutan --}}
            <div class="flex gap-2 items-start">
                <div class="w-7 h-7 rounded-lg bg-emerald-800 flex items-center justify-center flex-shrink-0 mt-0.5 overflow-hidden">
                    <img src="{{ asset('images/smamda-logo.png') }}" alt="SIPUS" class="w-5 h-5 object-contain">
                </div>
                <div class="bg-white border border-stone-200 rounded-2xl rounded-tl-sm px-3 py-2.5 text-sm text-stone-700 max-w-[85%] leading-relaxed shadow-sm">
                    <p class="font-semibold text-emerald-800" style="font-family: 'Playfair Display', serif;">Halo! Saya SIPUS 👋</p>
                    <p class="mt-1 text-stone-600">Asisten virtual Perpustakaan Al-Qalam. Tanyakan tentang koleksi buku, peminjaman, atau info perpustakaan.</p>
                </div>
            </div>

            {{-- Suggestion chips --}}
            <div class="flex flex-wrap gap-1.5 pl-9">
                <button
                    @click="kirimSuggestion($el)"
                    class="text-xs bg-emerald-50 border border-emerald-200 text-emerald-700 px-2.5 py-1 rounded-full hover:bg-emerald-100 transition-colors font-medium"
                >Cek ketersediaan buku</button>
                <button
                    @click="kirimSuggestion($el)"
                    class="text-xs bg-emerald-50 border border-emerald-200 text-emerald-700 px-2.5 py-1 rounded-full hover:bg-emerald-100 transition-colors font-medium"
                >Jam buka perpustakaan</button>
                <button
                    @click="kirimSuggestion($el)"
                    class="text-xs bg-emerald-50 border border-emerald-200 text-emerald-700 px-2.5 py-1 rounded-full hover:bg-emerald-100 transition-colors font-medium"
                >Info denda keterlambatan</button>
            </div>

            {{-- Dynamic messages --}}
            <template x-for="(msg, i) in pesan" :key="i">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex gap-2 items-start'">

                    <div
                        x-show="msg.role === 'bot'"
                        class="w-7 h-7 rounded-lg bg-emerald-800 flex items-center justify-center flex-shrink-0 mt-0.5 overflow-hidden"
                    >
                        <img src="{{ asset('images/smamda-logo.png') }}" alt="SIPUS" class="w-5 h-5 object-contain">
                    </div>

                    <div
                        :class="msg.role === 'user'
                            ? 'bg-emerald-700 text-white rounded-2xl rounded-tr-sm px-3 py-2 text-sm max-w-[85%] leading-relaxed shadow-sm'
                            : 'bg-white border border-stone-200 text-stone-700 rounded-2xl rounded-tl-sm px-3 py-2 text-sm max-w-[85%] leading-relaxed shadow-sm'"
                        x-html="msg.role === 'bot' ? formatTeks(msg.text) : escapeHtml(msg.text)"
                    ></div>
                </div>
            </template>

            {{-- Typing indicator --}}
            <div x-show="loading" class="flex gap-2 items-start">
                <div class="w-7 h-7 rounded-lg bg-emerald-800 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    <img src="{{ asset('images/smamda-logo.png') }}" alt="SIPUS" class="w-5 h-5 object-contain">
                </div>
                <div class="bg-white border border-stone-200 rounded-2xl rounded-tl-sm px-4 py-3 flex gap-1.5 items-center shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-bounce" style="animation-delay: 150ms"></span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-bounce" style="animation-delay: 300ms"></span>
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="px-3 pb-3 pt-2.5 border-t border-stone-200 bg-white flex-shrink-0">
            <div class="flex gap-2 items-end bg-stone-50 border border-stone-200 rounded-xl px-3 py-2 focus-within:border-emerald-400 focus-within:ring-2 focus-within:ring-emerald-100 transition-all">
                <textarea
                    x-model="inputTeks"
                    @keydown.enter.prevent="if (!$event.shiftKey) kirimPesan()"
                    :disabled="loading"
                    rows="1"
                    placeholder="Ketik pertanyaan kamu..."
                    class="flex-1 bg-transparent text-sm text-stone-800 placeholder-stone-400 resize-none outline-none max-h-24"
                    style="min-height: 22px; font-family: 'Plus Jakarta Sans', sans-serif;"
                    x-ref="inputArea"
                    @input="autoResize($el)"
                ></textarea>
                <button
                    @click="kirimPesan()"
                    :disabled="loading || !inputTeks.trim()"
                    class="w-8 h-8 rounded-lg bg-emerald-700 flex items-center justify-center flex-shrink-0 transition-all hover:bg-emerald-800 disabled:opacity-40 disabled:cursor-not-allowed"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                </button>
            </div>
            <p class="text-center text-stone-400 text-[10px] mt-1.5" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                Dijawab berdasarkan data Perpustakaan Al-Qalam
            </p>
        </div>
    </div>

    {{-- ── Toggle Button ── --}}
    <button
        @click="buka = !buka"
        class="w-14 h-14 rounded-2xl bg-emerald-800 hover:bg-emerald-700 shadow-lg border border-emerald-700 flex items-center justify-center transition-all duration-200 hover:scale-105 active:scale-95 relative"
        :title="buka ? 'Tutup SIPUS' : 'Tanya SIPUS'"
    >
        {{-- Badge notif saat tutup --}}
        <span
            x-show="!buka && pesanBelumDibaca > 0"
            class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center"
            x-text="pesanBelumDibaca"
        ></span>

        <div x-show="!buka" class="flex flex-col items-center gap-0.5">
            <img src="{{ asset('images/smamda-logo.png') }}" alt="SIPUS" class="w-7 h-7 object-contain">
            <span class="text-white text-[8px] font-semibold tracking-wide leading-none">SIPUS</span>
        </div>

        <svg x-show="buka" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
</div>