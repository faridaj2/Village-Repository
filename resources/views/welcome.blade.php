<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIDESA — Sistem Informasi Desa Waeleman</title>
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        .float-anim { animation: float 6s ease-in-out infinite; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp 0.8s ease-out both; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }

        /* Navbar: transparent di atas hero, solid setelah scroll */
        #mainNav { background: transparent; }
        #mainNav .nav-link { color: rgba(255,255,255,0.9); }
        #mainNav .nav-link:hover { color: #ffffff; }
        #mainNav .nav-logo { transition: filter .3s; }
        #mainNav.is-scrolled {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid #f3f4f6;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        #mainNav.is-scrolled .nav-link { color: #374151; }
        #mainNav.is-scrolled .nav-link:hover { color: #111827; }

        @media (prefers-reduced-motion: reduce) {
            .float-anim, .fade-up { animation: none; }
            html { scroll-behavior: auto; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-white text-gray-900">

    {{-- Navbar --}}
    <nav id="mainNav" class="fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16 sm:h-20">
            <a href="/" class="flex items-center gap-2.5" aria-label="Beranda SIDESA">
                <img src="{{ asset('logo-tagline.png') }}" alt="SIDESA" class="h-9 sm:h-10 w-auto nav-logo">
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="#pengumuman" class="nav-link text-sm font-medium transition-colors">Pengumuman</a>
                <a href="#fitur" class="nav-link text-sm font-medium transition-colors">Fitur</a>
                <a href="#tentang" class="nav-link text-sm font-medium transition-colors">Tentang</a>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gray-900 rounded-xl hover:bg-gray-700 transition-all shadow-lg shadow-gray-900/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    @php
        $heroSliders = \App\Models\AdminSetting::get('hero_sliders', [
            ['img' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1920&h=1080&fit=crop', 'title' => '', 'sub' => ''],
            ['img' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1920&h=1080&fit=crop', 'title' => '', 'sub' => ''],
            ['img' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1920&h=1080&fit=crop', 'title' => '', 'sub' => ''],
        ]);
    @endphp

    <section class="relative min-h-screen flex flex-col overflow-hidden bg-gray-950">
        <div class="absolute inset-0" id="heroSlider">
            @foreach ($heroSliders as $i => $slide)
                <img src="{{ $slide['img'] }}" alt=""
                     class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-[1500ms]"
                     style="{{ $i === 0 ? 'opacity:1' : 'opacity:0' }}">
            @endforeach
        </div>

        <div class="absolute inset-0 bg-gradient-to-br from-gray-950/95 via-gray-950/80 to-indigo-950/70"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-transparent to-transparent"></div>

        <div class="absolute top-1/4 -left-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl float-anim"></div>
        <div class="absolute bottom-1/4 -right-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl float-anim" style="animation-delay: 3s"></div>

        <div class="relative z-10 flex-1 flex items-center justify-center w-full max-w-7xl mx-auto px-4 sm:px-6 pt-28 pb-16">
            <div class="max-w-2xl lg:max-w-3xl text-center mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-10 fade-up">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-medium text-white/90 tracking-wide">Sistem Informasi Desa Digital</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-black text-white leading-[1.15] tracking-tight mb-10 fade-up delay-100 text-balance">
                    Desa Waeleman <span class="gradient-text">Lebih Modern</span> &amp; Transparan
                </h1>

                <p class="text-base sm:text-lg text-white/70 leading-loose mb-12 fade-up delay-200 mx-auto max-w-xl lg:max-w-2xl">
                    Platform terintegrasi untuk pengelolaan data penduduk, kartu keluarga, surat-menyurat, dan informasi publik desa — semua dalam satu sistem yang cepat, akurat, dan mudah digunakan.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-4 fade-up delay-300 max-w-md sm:max-w-none mx-auto">
                    <a href="{{ route('pengumuman.publik') }}" class="group inline-flex items-center justify-center gap-2.5 px-8 py-4 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] transition-all duration-200 whitespace-nowrap">
                        Lihat Pengumuman
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 text-sm font-semibold text-white bg-white/10 backdrop-blur-md border border-white/20 rounded-xl hover:bg-white/20 hover:scale-[1.02] transition-all duration-200 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            Masuk Petugas
                        </a>
                    @endguest
                </div>
            </div>
        </div>

        <div class="relative z-10 pb-24 flex flex-col items-center gap-6">
            <div class="flex gap-2 fade-up delay-300" id="heroDots">
                @foreach ($heroSliders as $i => $slide)
                    <button onclick="heroGoTo({{ $i }})" aria-label="Slide {{ $i + 1 }}" class="hero-dot h-1.5 rounded-full transition-all duration-500 {{ $i === 0 ? 'bg-white w-10' : 'bg-white/30 w-3 hover:bg-white/60' }}"></button>
                @endforeach
            </div>
            <div class="hidden sm:flex flex-col items-center text-white/40" aria-hidden="true">
                <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    @php
        $totalPenduduk = \App\Models\Penduduk::where('status', 'aktif')->count();
        $totalKk = \App\Models\KartuKeluarga::count();
        $totalSurat = \App\Models\Surat::count();
        $totalRumah = \App\Models\Rumah::count();
    @endphp
    <section class="relative -mt-20 z-20 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-3xl shadow-[0_4px_24px_rgba(15,23,42,0.06)] p-6 sm:p-8 grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                @php
                    $stats = [
                        ['label' => 'Penduduk Aktif', 'value' => number_format($totalPenduduk, 0, ',', '.'), 'color' => 'blue', 'icon' => 'users'],
                        ['label' => 'Kartu Keluarga', 'value' => number_format($totalKk, 0, ',', '.'), 'color' => 'emerald', 'icon' => 'home'],
                        ['label' => 'Surat Terbit', 'value' => number_format($totalSurat, 0, ',', '.'), 'color' => 'purple', 'icon' => 'mail', 'empty' => 'Belum ada surat diterbitkan'],
                        ['label' => 'Total Rumah', 'value' => number_format($totalRumah, 0, ',', '.'), 'color' => 'amber', 'icon' => 'building'],
                    ];
                @endphp
                @foreach ($stats as $s)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-{{ $s['color'] }}-50 flex items-center justify-center flex-shrink-0">
                            @if($s['icon'] === 'users')
                                <svg class="w-6 h-6 text-{{ $s['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @elseif($s['icon'] === 'home')
                                <svg class="w-6 h-6 text-{{ $s['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            @elseif($s['icon'] === 'mail')
                                <svg class="w-6 h-6 text-{{ $s['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            @else
                                <svg class="w-6 h-6 text-{{ $s['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight">{{ $s['value'] }}</p>
                            <p class="text-xs sm:text-sm text-gray-500 truncate">{{ $s['label'] }}</p>
                            @if(!empty($s['empty']) && (int) preg_replace('/\D/', '', $s['value']) === 0)
                                <p class="text-[11px] text-gray-400 mt-1.5 leading-snug">{{ $s['empty'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Fitur --}}
    <section id="fitur" class="py-32 sm:py-44 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Fitur Unggulan</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black mt-4 mb-4 tracking-tight leading-tight">Semua Kebutuhan<br>Administrasi Desa</h2>
                <p class="text-base text-gray-500 leading-relaxed">Satu platform untuk mengelola seluruh data dan layanan administrasi desa dengan mudah.</p>
            </div>

            @php
                $fiturs = [
                    ['title' => 'Data Penduduk', 'desc' => 'Kelola data penduduk lengkap dengan kartu keluarga, rumah, dan wilayah.', 'color' => 'blue', 'icon' => 'users'],
                    ['title' => 'Surat Menyurat', 'desc' => 'Buat, kelola, dan arsipkan surat desa dengan template otomatis dan preview.', 'color' => 'purple', 'icon' => 'mail'],
                    ['title' => 'Peta Desa', 'desc' => 'Visualisasi lokasi rumah, fasilitas umum, dan batas wilayah desa.', 'color' => 'emerald', 'icon' => 'map'],
                    ['title' => 'Kartu Keluarga', 'desc' => 'Kelola kartu keluarga dan hubungan antar anggota keluarga.', 'color' => 'amber', 'icon' => 'home'],
                    ['title' => 'Pengumuman', 'desc' => 'Sampaikan informasi penting kepada warga desa secara real-time.', 'color' => 'rose', 'icon' => 'bell'],
                    ['title' => 'Media & Dokumen', 'desc' => 'Simpan dan kelola file serta dokumen penting desa dengan aman.', 'color' => 'slate', 'icon' => 'folder'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7">
                @foreach ($fiturs as $f)
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                       class="group relative block bg-white rounded-3xl p-7 sm:p-8 shadow-[0_1px_3px_rgba(0,0,0,0.06)] hover:shadow-xl hover:shadow-{{ $f['color'] }}-500/15 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                        <div class="w-14 h-14 rounded-2xl bg-{{ $f['color'] }}-50 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform" aria-hidden="true">
                            @if($f['icon'] === 'users')
                                <svg class="w-7 h-7 text-{{ $f['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @elseif($f['icon'] === 'mail')
                                <svg class="w-7 h-7 text-{{ $f['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            @elseif($f['icon'] === 'map')
                                <svg class="w-7 h-7 text-{{ $f['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            @elseif($f['icon'] === 'home')
                                <svg class="w-7 h-7 text-{{ $f['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            @elseif($f['icon'] === 'bell')
                                <svg class="w-7 h-7 text-{{ $f['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @else
                                <svg class="w-7 h-7 text-{{ $f['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold mb-2 text-gray-900">{{ $f['title'] }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $f['desc'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pengumuman --}}
    <section id="pengumuman" class="pt-20 sm:pt-24 pb-20 px-4 sm:px-6 bg-gray-50/70">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Informasi Publik</span>
                    <h2 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight leading-tight">Pengumuman Terbaru</h2>
                    <p class="text-base text-gray-500 mt-2">Informasi dan berita terbaru dari Desa Waeleman</p>
                </div>
                <a href="{{ route('pengumuman.publik') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors group whitespace-nowrap shrink-0">
                    Lihat Semua
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            @php $pengumumans = \App\Models\Pengumuman::published()->latest('tanggal_publish')->limit(3)->get(); @endphp
            @if ($pengumumans->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-7">
                    @foreach ($pengumumans as $i => $item)
                        <a href="{{ route('pengumuman.publik') }}#pengumuman-{{ $item->id }}"
                           class="group relative bg-white rounded-3xl p-7 sm:p-8 shadow-[0_1px_3px_rgba(0,0,0,0.06)] hover:shadow-xl hover:shadow-indigo-500/15 hover:-translate-y-0.5 transition-all duration-200 block focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                            <div class="flex items-center gap-2 mb-4">
                                @if ($item->kategori)
                                    <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider rounded-lg">{{ $item->kategori }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wider rounded-lg">Umum</span>
                                @endif
                                <span class="text-xs text-gray-400">{{ $item->tanggal_publish->translatedFormat('d M Y') }}</span>
                            </div>
                            <h3 class="text-lg font-bold mb-2 line-clamp-2 text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $item->judul }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">{{ strip_tags($item->isi) }}</p>
                            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-xs font-semibold text-indigo-600 group-hover:underline">Baca selengkapnya</span>
                                <svg class="w-4 h-4 text-indigo-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-3xl shadow-[0_1px_3px_rgba(0,0,0,0.06)]">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 mb-1">Belum ada pengumuman terbaru</p>
                    <p class="text-sm text-gray-500">Pengumuman akan muncul di sini</p>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section id="tentang" class="py-20 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
            <div class="relative bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 rounded-[2.5rem] overflow-hidden p-10 sm:p-16 lg:p-20 text-center">
                <div class="absolute top-0 -left-24 w-96 h-96 bg-indigo-500/30 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 -right-24 w-96 h-96 bg-purple-500/30 rounded-full blur-3xl"></div>
                <div class="relative">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight mb-4 leading-tight">Siap Melayani Warga<br>Dengan Lebih Baik</h2>
                    <p class="text-white/70 max-w-xl mx-auto mb-8 leading-relaxed">Bergabunglah dengan sistem informasi desa modern. Kelola data, layani warga, dan wujudkan desa yang transparan.</p>
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-8 py-4 text-sm font-bold text-gray-900 bg-white rounded-xl hover:bg-gray-100 transition-all shadow-2xl">
                            Buka Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-4 text-sm font-bold text-gray-900 bg-white rounded-xl hover:bg-gray-100 transition-all shadow-2xl">
                            Masuk ke Sistem
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 py-12 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo-tagline.png') }}" alt="SIDESA" class="h-9 w-auto">
            </div>
            <p class="text-xs text-gray-400 text-center sm:text-right">
                &copy; {{ date('Y') }} Sistem Informasi Desa Waeleman<br class="sm:hidden">
                <span class="hidden sm:inline"> &middot; </span>
                Dibuat dengan ❤️ untuk desa yang lebih baik
            </p>
        </div>
    </footer>

    <script>
        // Navbar: transparent di atas, solid setelah scroll
        (function() {
            const nav = document.getElementById('mainNav');
            if (!nav) return;
            const toggle = () => {
                if (window.scrollY > 20) nav.classList.add('is-scrolled');
                else nav.classList.remove('is-scrolled');
            };
            toggle();
            window.addEventListener('scroll', toggle, { passive: true });
        })();

        // Hero slider
        (function() {
            let current = 0;
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');
            const total = slides.length;
            if (!total) return;
            let timer;

            function show(n) {
                slides.forEach((s, i) => s.style.opacity = i === n ? '1' : '0');
                dots.forEach((d, i) => {
                    d.className = i === n
                        ? 'hero-dot h-1.5 rounded-full transition-all duration-500 bg-white w-10'
                        : 'hero-dot h-1.5 rounded-full transition-all duration-500 bg-white/30 w-3 hover:bg-white/60';
                });
                current = n;
            }

            window.heroGoTo = function(n) { show(n); restart(); };
            function restart() {
                clearInterval(timer);
                timer = setInterval(() => show((current + 1) % total), 6000);
            }
            restart();
        })();
    </script>
</body>
</html>
