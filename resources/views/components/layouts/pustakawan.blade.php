<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Panel Pustakawan' }} — Perpustakaan Al-Qalam</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-jakarta antialiased bg-stone-100" x-data="{ sidebarTerbuka: false }">

    <div class="flex h-screen overflow-hidden">
        <x-pustakawan.sidebar />

        <div class="flex-1 flex flex-col overflow-hidden">
            <x-pustakawan.header :title="$title ?? 'Dasbor'" />

            <main class="flex-1 overflow-y-auto p-6">
                <x-shared.alert />
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>