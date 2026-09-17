{{-- ============ NAVBAR ============ --}}
<header id="siteNav" class="fixed top-0 inset-x-0 z-50 nav-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16 lg:h-20">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0" aria-label="Beranda SIDESA Desa Waeleman">
            <img src="{{ asset('logo-tagline.png') }}" alt="SIDESA" class="h-9 lg:h-11 w-auto nav-brand-logo">
            <div class="hidden sm:flex flex-col leading-tight">
                <span class="nav-brand-text text-sm lg:text-base font-bold tracking-tight">Desa Waeleman</span>
                <span class="nav-brand-sub text-[10px] lg:text-xs font-medium tracking-wide">Sistem Informasi Desa</span>
            </div>
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden lg:flex items-center gap-8" aria-label="Navigasi utama">
            <a href="#profil" class="nav-link text-sm font-semibold">Profil</a>
            <a href="#fitur" class="nav-link text-sm font-semibold">Fitur</a>
            <a href="#struktur" class="nav-link text-sm font-semibold">Pemerintahan</a>
            <a href="#fasilitas" class="nav-link text-sm font-semibold">Fasilitas</a>
            <a href="#pengumuman" class="nav-link text-sm font-semibold">Pengumuman</a>
            <a href="#kontak" class="nav-link text-sm font-semibold">Kontak</a>
        </nav>

        {{-- Actions --}}
        <div class="flex items-center gap-2 lg:gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 text-xs lg:text-sm font-bold text-white bg-brand-900 hover:bg-brand-800 rounded-xl shadow-lg shadow-brand-900/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/></svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center px-4 lg:px-5 py-2.5 text-xs lg:text-sm font-bold rounded-xl border border-white/25 text-white/95 hover:bg-white/10 nav-login-btn transition-all">
                    Masuk
                </a>
            @endauth

            {{-- Mobile menu toggle --}}
            <button id="openDrawer" type="button" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white/10 transition-colors" aria-label="Buka menu">
                <svg class="w-6 h-6 nav-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
</header>

{{-- Mobile drawer --}}
<div id="mobileDrawer" class="drawer-closed fixed inset-y-0 right-0 w-80 max-w-[85vw] bg-white z-[60] shadow-2xl flex flex-col">
    <div class="flex items-center justify-between px-6 h-16 border-b border-slate-100">
        <span class="font-bold text-brand-900">Menu</span>
        <button id="closeDrawer" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-100" aria-label="Tutup menu">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <nav class="flex-1 overflow-y-auto py-4" aria-label="Navigasi mobile">
        <a href="#profil" class="flex items-center gap-3 px-6 py-3.5 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-900 transition-colors">Profil Desa</a>
        <a href="#fitur" class="flex items-center gap-3 px-6 py-3.5 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-900 transition-colors">Fitur</a>
        <a href="#struktur" class="flex items-center gap-3 px-6 py-3.5 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-900 transition-colors">Pemerintahan</a>
        <a href="#fasilitas" class="flex items-center gap-3 px-6 py-3.5 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-900 transition-colors">Fasilitas Umum</a>
        <a href="#peta" class="flex items-center gap-3 px-6 py-3.5 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-900 transition-colors">Peta Desa</a>
        <a href="#pengumuman" class="flex items-center gap-3 px-6 py-3.5 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-900 transition-colors">Pengumuman</a>
        <a href="#galeri" class="flex items-center gap-3 px-6 py-3.5 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-900 transition-colors">Galeri</a>
        <a href="#kontak" class="flex items-center gap-3 px-6 py-3.5 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-900 transition-colors">Kontak</a>
    </nav>
    <div class="p-6 border-t border-slate-100 space-y-3">
        @auth
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-full px-5 py-3 text-sm font-bold text-white bg-brand-900 hover:bg-brand-800 rounded-xl">Buka Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-5 py-3 text-sm font-bold text-white bg-brand-900 hover:bg-brand-800 rounded-xl">Masuk Petugas</a>
        @endauth
    </div>
</div>
