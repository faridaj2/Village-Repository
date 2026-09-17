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

    <header id="siteNav" class="bg-brand-950/95 backdrop-blur-md nav-solid sticky top-0 z-50 shadow-lg shadow-brand-950/10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0" aria-label="Beranda SIDESA">
                <img src="{{ asset('logo-tagline.png') }}" alt="SIDESA" class="h-9 w-auto brightness-0 invert">
            </a>

            <nav class="flex items-center gap-1 sm:gap-5 text-sm" aria-label="Navigasi publik">
                <a href="{{ route('home') }}" class="hidden sm:inline-flex px-3 py-2 text-white/70 hover:text-gold-400 transition-colors font-medium rounded-lg hover:bg-white/5">Beranda</a>
                <a href="{{ route('pengumuman.publik') }}" class="hidden sm:inline-flex px-3 py-2 text-white/70 hover:text-gold-400 transition-colors font-medium rounded-lg hover:bg-white/5">Pengumuman</a>
                <a href="{{ route('cek-data') }}" class="px-3 py-2 text-gold-400 font-bold rounded-lg bg-white/5" aria-current="page">Cek Data</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex ml-2 px-4 py-2 text-xs font-bold text-white bg-brand-900 hover:bg-brand-800 rounded-xl transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex ml-2 px-4 py-2 text-xs font-bold text-white/90 border border-white/20 hover:bg-white/10 rounded-xl transition-all">Masuk</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="bg-brand-950 text-white/70 pt-16 pb-8 px-4 sm:px-6 lg:px-8 mt-16">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12 pb-12 border-b border-white/10">
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-5">
                        <img src="{{ asset('logo-tagline.png') }}" alt="SIDESA" class="h-11 w-auto">
                    </div>
                    <p class="text-sm leading-relaxed max-w-md mb-6">
                        Sistem Informasi Desa Waeleman — platform terpadu untuk pelayanan administrasi desa yang cepat, transparan, dan akuntabel.
                    </p>
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-gold-500"></span>
                        <span class="text-[10px] font-bold tracking-[0.25em] uppercase text-gold-400">Kabupaten Buru · Maluku</span>
                    </div>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-gold-400 uppercase tracking-widest mb-5">Layanan Publik</div>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-gold-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('pengumuman.publik') }}" class="hover:text-gold-400 transition-colors">Pengumuman</a></li>
                        <li><a href="{{ route('cek-data') }}" class="hover:text-gold-400 transition-colors">Cek Data Penduduk</a></li>
                    </ul>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-gold-400 uppercase tracking-widest mb-5">Kontak</div>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-gold-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Kantor Desa Waeleman, Kec. Waelata, Kab. Buru, Maluku</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-gold-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Senin–Jumat, 08.00–15.00 WIT</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs">&copy; {{ date('Y') }} Pemerintah Desa Waeleman. Hak cipta dilindungi.</p>
                <p class="text-xs">Dibangun dengan <span class="text-gold-500">❤</span> untuk desa yang lebih baik</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
