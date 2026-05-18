<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Perpustakaan Digital Al-Qalam' }}</title>
    <meta name="description" content="{{ $description ?? 'Perpustakaan Digital Sekolah Al-Qalam' }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-jakarta antialiased bg-stone-50 text-stone-800">

    <x-publik.navbar />

    <main>
        {{ $slot }}
    </main>

    <x-publik.footer />

    @stack('scripts')
</body>
</html>