<x-layouts.publik title="Bantuan — Perpustakaan Al-Qalam">

    <div class="bg-emerald-800 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-playfair text-4xl font-bold text-white mb-3">Pusat Bantuan</h1>
            <p class="text-emerald-200">Temukan jawaban atas pertanyaan Anda tentang layanan perpustakaan kami.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        {{-- Panduan Penggunaan --}}
        <div class="mb-12">
            <h2 class="font-playfair text-2xl font-bold text-stone-800 mb-6">Panduan Penggunaan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                $panduan = [
                    ['Daftar sebagai Anggota', 'Hubungi pustakawan untuk mendaftarkan diri sebagai anggota perpustakaan. Pastikan membawa kartu identitas sekolah.'],
                    ['Masuk ke Sistem', 'Gunakan email dan kata sandi yang diberikan oleh pustakawan untuk masuk ke area anggota.'],
                    ['Cari Buku', 'Gunakan fitur pencarian untuk menemukan buku berdasarkan judul, penulis, atau kategori.'],
                    ['Kembalikan Tepat Waktu', 'Pastikan mengembalikan buku sebelum batas waktu yang ditentukan untuk menghindari sanksi.'],
                ];
                @endphp
                @foreach($panduan as $i => $item)
                    <div class="flex gap-4 p-5 bg-white rounded-xl border border-stone-100 shadow-sm">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 text-emerald-700 font-bold text-sm">
                            {{ $i + 1 }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-stone-800 mb-1">{{ $item[0] }}</h3>
                            <p class="text-sm text-stone-500 leading-relaxed">{{ $item[1] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tutorial Peminjaman --}}
        <div class="mb-12">
            <h2 class="font-playfair text-2xl font-bold text-stone-800 mb-6">Tutorial Peminjaman</h2>
            <div class="bg-white rounded-xl border border-stone-100 shadow-sm overflow-hidden">
                @php
                $langkah = [
                    ['Masuk ke Akun', 'Login menggunakan email dan kata sandi Anda di halaman masuk anggota.'],
                    ['Cari Buku', 'Gunakan menu Koleksi Buku untuk mencari buku yang ingin dipinjam.'],
                    ['Ajukan Peminjaman', 'Klik tombol "Pinjam Buku Ini" pada halaman detail buku yang tersedia.'],
                    ['Tunggu Konfirmasi', 'Pustakawan akan memproses permintaan Anda. Status peminjaman dapat dilihat di dasbor.'],
                    ['Ambil Buku', 'Setelah disetujui, ambil buku di perpustakaan dan tunjukkan konfirmasi kepada pustakawan.'],
                    ['Kembalikan Buku', 'Kembalikan buku sebelum batas waktu (7 hari sejak disetujui) ke meja pustakawan.'],
                ];
                @endphp
                @foreach($langkah as $i => $item)
                    <div class="flex gap-4 p-5 @if(!$loop->last) border-b border-stone-100 @endif">
                        <div class="w-7 h-7 bg-emerald-700 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold mt-0.5">
                            {{ $i + 1 }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-stone-800 text-sm mb-0.5">{{ $item[0] }}</h3>
                            <p class="text-sm text-stone-500">{{ $item[1] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- FAQ --}}
        <div>
            <h2 class="font-playfair text-2xl font-bold text-stone-800 mb-6">Pertanyaan Umum (FAQ)</h2>
            <div class="space-y-3" x-data="{ buka: null }">
                @php
                $faq = [
                    ['Berapa lama batas peminjaman buku?', 'Batas waktu peminjaman adalah 7 hari kalender sejak buku disetujui oleh pustakawan. Anda akan diberikan notifikasi melalui dasbor anggota.'],
                    ['Apakah bisa meminjam lebih dari satu buku?', 'Saat ini sistem kami menerapkan kebijakan satu peminjaman aktif per anggota. Anda harus mengembalikan buku yang sedang dipinjam sebelum dapat meminjam buku lain.'],
                    ['Apa yang terjadi jika terlambat mengembalikan?', 'Keterlambatan akan dicatat dalam sistem dan dapat mempengaruhi status keanggotaan Anda. Segera hubungi pustakawan jika mengalami kendala pengembalian.'],
                    ['Bagaimana cara mengubah kata sandi?', 'Masuk ke area anggota, kemudian buka menu Profil > Edit Profil. Anda dapat mengubah kata sandi di bagian bawah halaman tersebut.'],
                    ['Buku yang dicari tidak tersedia, apa yang harus dilakukan?', 'Anda dapat menghubungi pustakawan untuk mengajukan usulan pengadaan buku, atau menunggu hingga buku dikembalikan oleh anggota yang sedang meminjam.'],
                ];
                @endphp
                @foreach($faq as $i => $item)
                    <div class="bg-white rounded-xl border border-stone-100 shadow-sm overflow-hidden">
                        <button
                            @click="buka = buka === {{ $i }} ? null : {{ $i }}"
                            class="w-full text-left px-5 py-4 flex items-center justify-between gap-3"
                        >
                            <span class="font-medium text-stone-800 text-sm">{{ $item[0] }}</span>
                            <svg class="w-4 h-4 text-stone-400 flex-shrink-0 transition-transform"
                                 :class="buka === {{ $i }} ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="buka === {{ $i }}" x-collapse class="border-t border-stone-100">
                            <p class="px-5 py-4 text-sm text-stone-600 leading-relaxed">{{ $item[1] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</x-layouts.publik>