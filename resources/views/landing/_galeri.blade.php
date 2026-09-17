{{-- ============ GALERI ============ --}}
<section id="galeri" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-14 reveal">
            <div class="ornament mb-6">
                <span class="text-gold-600 text-xs font-bold tracking-[0.3em] uppercase">Dokumentasi</span>
            </div>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-slate-900 leading-tight mb-5">
                Galeri <span class="text-brand-900">Kegiatan Desa</span>
            </h2>
            <p class="text-base text-slate-500 leading-relaxed">
                Momen dan dokumentasi kegiatan warga dan pemerintahan desa.
            </p>
        </div>

        @if ($galeri->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-5">
                @foreach ($galeri as $i => $m)
                    <a href="{{ $m->url }}" target="_blank" rel="noopener"
                       class="gallery-item reveal delay-{{ $i % 4 }} group aspect-square {{ $i === 0 ? 'md:col-span-2 md:row-span-2' : '' }} focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-700 focus-visible:ring-offset-2">
                        <img src="{{ $m->url }}" alt="{{ $m->original_name }}" loading="lazy" class="w-full h-full object-cover">
                        <div class="absolute bottom-0 inset-x-0 p-4 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="text-[10px] font-bold text-gold-400 uppercase tracking-widest mb-0.5">Dokumentasi Desa</div>
                            <div class="text-sm font-semibold text-white truncate">{{ $m->original_name }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 max-w-md mx-auto bg-slate-50 rounded-3xl border border-slate-100 reveal">
                <div class="w-16 h-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-sm font-bold text-slate-900 mb-1">Belum ada foto galeri</p>
                <p class="text-sm text-slate-500">Foto kegiatan desa akan tampil di sini.</p>
            </div>
        @endif
    </div>
</section>
