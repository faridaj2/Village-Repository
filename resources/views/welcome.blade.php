<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SIDESA — Sistem Informasi Desa</title>
        <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>html{scroll-behavior:smooth}</style>
    </head>
    <body class="font-sans antialiased bg-white text-gray-900">
        <!-- Navbar -->
        <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-xl border-b border-gray-100 z-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2.5">
                    <img src="{{ asset('logo-tagline.png') }}" alt="SIDESA" class="h-9 w-auto">
                </a>
                <div class="hidden sm:flex items-center gap-6">
                    <a href="#layanan" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Layanan</a>
                    <a href="#pengumuman" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Pengumuman</a>
                    <a href="{{ route('surat.ajukan') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Ajukan Surat</a>
                    <a href="{{ route('surat.tracking') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Tracking</a>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl hover:from-emerald-600 hover:to-teal-700 shadow-lg shadow-emerald-500/25 transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">Login</a>
                @endauth
            </div>
        </nav>

        <!-- Hero Slider -->
        @php
            $heroSliders = \App\Models\AdminSetting::get('hero_sliders', [
                ['img' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1600&h=600&fit=crop', 'title' => '', 'sub' => ''],
                ['img' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1600&h=600&fit=crop', 'title' => '', 'sub' => ''],
                ['img' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1600&h=600&fit=crop', 'title' => '', 'sub' => ''],
            ]);
        @endphp
        <section class="pt-16">
            <div class="relative h-[450px] sm:h-[550px] overflow-hidden bg-gray-900" id="heroSlider">
                @foreach ($heroSliders as $i => $slide)
                    <img src="{{ $slide['img'] }}" alt="Slide {{ $i + 1 }}"
                         class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"
                         style="{{ $i === 0 ? 'opacity:1' : 'opacity:0' }}">
                @endforeach

                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent pointer-events-none"></div>

                <!-- CTA -->
                <div class="absolute bottom-16 left-1/2 -translate-x-1/2 z-20 flex flex-col sm:flex-row items-center gap-3">
                    <a href="{{ route('surat.ajukan') }}" class="px-8 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg transition-all">
                        Ajukan Surat Online
                    </a>
                    <a href="{{ route('surat.tracking') }}" class="px-8 py-3.5 text-sm font-semibold text-white bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl hover:bg-white/30 transition-all">
                        Cek Status Surat
                    </a>
                </div>

                <!-- Dots -->
                <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex gap-2" id="heroDots">
                    @foreach ($heroSliders as $i => $slide)
                        <button onclick="heroGoTo({{ $i }})" class="hero-dot h-2 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-white w-8' : 'bg-white/40 w-2' }}"></button>
                    @endforeach
                </div>

                <!-- Arrows -->
                <button onclick="heroPrev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/30 hover:bg-black/50 rounded-full flex items-center justify-center text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button onclick="heroNext()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/30 hover:bg-black/50 rounded-full flex items-center justify-center text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </section>
        <script>
            (function() {
                let current = 0;
                const slides = document.querySelectorAll('.hero-slide');
                const dots = document.querySelectorAll('.hero-dot');
                const total = slides.length;

                function show(n) {
                    slides.forEach((s, i) => s.style.opacity = i === n ? '1' : '0');
                    dots.forEach((d, i) => {
                        if (i === n) {
                            d.className = 'hero-dot h-2 rounded-full transition-all duration-300 bg-white w-8';
                        } else {
                            d.className = 'hero-dot h-2 rounded-full transition-all duration-300 bg-white/40 w-2';
                        }
                    });
                    current = n;
                }

                window.heroGoTo = function(n) { show(n); };
                window.heroNext = function() { show((current + 1) % total); };
                window.heroPrev = function() { show((current - 1 + total) % total); };

                setInterval(() => show((current + 1) % total), 5000);
            })();
        </script>

        <!-- Layanan -->
        <section id="layanan" class="py-20 px-4 sm:px-6 bg-gray-50">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-3">Layanan Publik</h2>
                    <p class="text-gray-500">Akses layanan desa secara online</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <a href="{{ route('surat.ajukan') }}" class="group bg-white rounded-2xl p-6 border border-gray-100 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-500/5 transition-all">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-100 transition-colors">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Ajukan Surat</h3>
                        <p class="text-sm text-gray-500">Domisili, tidak mampu, usaha, pengantar KTP/KK</p>
                    </a>
                    <a href="{{ route('surat.tracking') }}" class="group bg-white rounded-2xl p-6 border border-gray-100 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-500/5 transition-all">
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Tracking Surat</h3>
                        <p class="text-sm text-gray-500">Pantau status pengajuan surat Anda</p>
                    </a>
                    <a href="{{ route('pengumuman.publik') }}" class="group bg-white rounded-2xl p-6 border border-gray-100 hover:border-amber-200 hover:shadow-lg hover:shadow-amber-500/5 transition-all">
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-100 transition-colors">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Pengumuman</h3>
                        <p class="text-sm text-gray-500">Informasi terbaru dari desa</p>
                    </a>
                </div>
            </div>
        </section>

        <!-- Pengumuman Terbaru -->
        <section id="pengumuman" class="py-20 px-4 sm:px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-3">Pengumuman Terbaru</h2>
                    <p class="text-gray-500">Informasi penting dari desa</p>
                </div>
                @php $pengumumans = \App\Models\Pengumuman::published()->latest('tanggal_publish')->limit(3)->get(); @endphp
                @if ($pengumumans->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach ($pengumumans as $item)
                            <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all">
                                <div class="flex items-center gap-2 mb-3">
                                    @if ($item->kategori)
                                        <span class="inline-flex items-center px-2.5 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-lg">{{ $item->kategori }}</span>
                                    @endif
                                    <span class="text-xs text-gray-400">{{ $item->tanggal_publish->format('d M Y') }}</span>
                                </div>
                                <h3 class="text-base font-semibold mb-2">{{ $item->judul }}</h3>
                                <p class="text-sm text-gray-500 line-clamp-3">{{ $item->isi }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-8">
                        <a href="{{ route('pengumuman.publik') }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                            Lihat Semua Pengumuman
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-2xl">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <p class="text-sm text-gray-500">Belum ada pengumuman</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- Keunggulan -->
        <section class="py-20 px-4 sm:px-6 bg-gray-50">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="text-center p-6">
                        <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Proses Cepat</h3>
                        <p class="text-sm text-gray-500">Pengajuan surat diproses dalam hitungan jam, bukan hari</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Aman & Transparan</h3>
                        <p class="text-sm text-gray-500">Data terlindungi dan Anda bisa pantau setiap proses</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Tanpa Ribet</h3>
                        <p class="text-sm text-gray-500">Ajukan dari rumah, cukup dengan NIK dan HP</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-10 px-4 sm:px-6 border-t border-gray-100">
            <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">SIDESA</span>
                </div>
                <p class="text-xs text-gray-400">Sistem Informasi Desa &copy; {{ date('Y') }}</p>
            </div>
        </footer>
    </body>
</html>
