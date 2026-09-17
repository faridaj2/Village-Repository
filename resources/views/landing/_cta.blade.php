{{-- ============ CTA ============ --}}
<section class="py-20 sm:py-24 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="relative bg-gradient-to-br from-brand-950 via-brand-900 to-brand-950 rounded-[2.5rem] overflow-hidden p-10 sm:p-16 lg:p-20 text-center reveal">
            <div class="absolute top-0 -left-32 w-[28rem] h-[28rem] bg-gold-500/15 rounded-full blur-3xl" aria-hidden="true"></div>
            <div class="absolute bottom-0 -right-32 w-[28rem] h-[28rem] bg-emerald-500/15 rounded-full blur-3xl" aria-hidden="true"></div>
            <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 28px 28px;" aria-hidden="true"></div>

            <div class="relative">
                <div class="ornament mb-6">
                    <span class="text-gold-400 text-xs font-bold tracking-[0.3em] uppercase">Pelayanan Digital</span>
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-white leading-tight mb-5 max-w-3xl mx-auto">
                    Siap Melayani Warga<br><span class="gradient-text">Dengan Lebih Baik</span>
                </h2>
                <p class="text-white/70 max-w-xl mx-auto mb-9 leading-relaxed">
                    Bergabunglah dengan sistem informasi desa modern. Kelola data, layani warga, dan wujudkan desa yang transparan dan akuntabel.
                </p>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-gold group inline-flex items-center gap-2.5 px-8 py-4 text-sm font-bold rounded-xl">
                        Buka Dashboard
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-gold group inline-flex items-center gap-2.5 px-8 py-4 text-sm font-bold rounded-xl">
                        Masuk ke Sistem
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>
