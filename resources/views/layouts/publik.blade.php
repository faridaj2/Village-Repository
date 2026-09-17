<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#064e3b">
    <title>{{ $title ?? 'SIDESA — Layanan Publik Desa Waeleman' }}</title>
    <meta name="description" content="Layanan publik digital Desa Waeleman, Kecamatan Waelata, Kabupaten Buru, Maluku.">
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&family=playfair-display:600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 min-h-screen flex flex-col">

    <header class="bg-brand-950 text-white sticky top-0 z-40 shadow-lg shadow-brand-950/20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Beranda">
                <img src="{{ asset('logo-tagline.png') }}" alt="SIDESA" class="h-9 w-auto brightness-0 invert">
            </a>
            <nav class="flex items-center gap-5 text-sm">
                <a href="{{ route('home') }}" class="text-white/70 hover:text-gold-400 transition-colors font-medium hidden sm:inline">Beranda</a>
                <a href="{{ route('pengumuman.publik') }}" class="text-white/70 hover:text-gold-400 transition-colors font-medium hidden sm:inline">Pengumuman</a>
                <a href="{{ route('cek-data') }}" class="text-gold-400 font-bold">Cek Data</a>
            </nav>
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="bg-brand-950 text-white/50 text-center py-6 text-xs mt-12">
        &copy; {{ date('Y') }} Pemerintah Desa Waeleman · Kec. Waelata · Kab. Buru · Maluku
    </footer>

    @livewireScripts
</body>
</html>
