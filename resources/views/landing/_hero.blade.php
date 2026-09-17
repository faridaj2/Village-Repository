{{-- ============ HERO ============ --}}
<section class="relative min-h-screen flex flex-col overflow-hidden bg-brand-950">
    {{-- Slides --}}
    <div class="absolute inset-0" id="heroSlider">
        @foreach ($heroSliders as $i => $slide)
            <img src="{{ $slide['img'] }}" alt="" aria-hidden="true"
                 class="hero-slide absolute inset-0 w-full h-full object-cover{{ $i === 0 ? ' is-active' : '' }}"
                 style="opacity: {{ $i === 0 ? 1 : 0 }};">
        @endforeach
    </div>

    {{-- Overlays --}}
    <div class="absolute inset-0 bg-gradient-to-br from-brand-950/95 via-brand-950/75 to-brand-900/60"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-brand-950 via-transparent to-transparent"></div>
    <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 32px 32px;"></div>

    {{-- Floating accents --}}
    <div class="absolute top-1/4 -left-32 w-[28rem] h-[28rem] bg-gold-500/15 rounded-full blur-3xl animate-floaty" aria-hidden="true"></div>
    <div class="absolute bottom-1/4 -right-32 w-[28rem] h-[28rem] bg-emerald-500/15 rounded-full blur-3xl animate-floaty" style="animation-delay: 3s" aria-hidden="true"></div>

    {{-- Content --}}
    <div class="relative z-10 flex-1 flex items-center justify-center w-full max-w-7xl mx-auto px-4 sm:px-6 pt-28 pb-20">
        <div class="max-w-3xl text-center mx-auto">

            <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-white/8 backdrop-blur-md border border-white/15 rounded-full mb-8 animate-fade-up">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-500 animate-pulse"></span>
                <span class="text-[11px] font-bold text-white/90 tracking-[0.2em] uppercase">Kabupaten Malaka · NTT</span>
            </div>

            <div class="ornament mb-6 animate-fade-up">
                <span class="text-gold-400 text-xs font-bold tracking-[0.3em] uppercase">Sistem Informasi Desa</span>
            </div>

            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl xl:text-7xl text-white leading-[1.08] mb-8 animate-fade-up delay-100">
                Desa Waeleman<br>
                <span class="gradient-text">Modern, Transparan</span><br>
                &amp; Melayani
            </h1>

            <p class="text-base sm:text-lg text-white/75 leading-relaxed mb-10 max-w-xl mx-auto animate-fade-up delay-200">
                Platform terpadu pengelolaan data kependudukan, kartu keluarga, surat-menyurat, dan informasi publik — untuk pelayanan desa yang lebih cepat, akurat, dan akuntabel.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-up delay-300 max-w-lg sm:max-w-none mx-auto">
                <a href="{{ route('pengumuman.publik') }}" class="btn-gold group inline-flex items-center justify-center gap-2.5 px-8 py-4 text-sm font-bold rounded-xl whitespace-nowrap w-full sm:w-auto">
                    Lihat Pengumuman
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="#profil" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 text-sm font-bold text-white bg-white/8 backdrop-blur-md border border-white/20 rounded-xl hover:bg-white/15 transition-all whitespace-nowrap w-full sm:w-auto">
                    Jelajahi Desa
                </a>
            </div>
        </div>
    </div>

    {{-- Dots + scroll cue --}}
    <div class="relative z-10 pb-20 flex flex-col items-center gap-7">
        <div class="flex gap-2.5" id="heroDots">
            @foreach ($heroSliders as $i => $slide)
                <button onclick="heroGoTo({{ $i }})" aria-label="Slide {{ $i + 1 }}" class="hero-dot{{ $i === 0 ? ' is-active' : '' }}"></button>
            @endforeach
        </div>
        <a href="#profil" class="hidden sm:flex flex-col items-center gap-2 text-white/40 hover:text-gold-400 transition-colors" aria-label="Gulir ke bawah">
            <span class="text-[10px] font-bold tracking-[0.3em] uppercase">Scroll</span>
            <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </a>
    </div>
</section>
