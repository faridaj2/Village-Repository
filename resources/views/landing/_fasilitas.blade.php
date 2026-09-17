{{-- ============ FASILITAS UMUM ============ --}}
<section id="fasilitas" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-14 gap-6 reveal">
            <div>
                <div class="ornament mb-6 !justify-start">
                    <span class="text-gold-600 text-xs font-bold tracking-[0.3em] uppercase">Sarana &amp; Prasarana</span>
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-slate-900 leading-tight">
                    Fasilitas <span class="text-brand-900">Umum Desa</span>
                </h2>
                <p class="text-base text-slate-500 mt-3 max-w-xl">
                    Berbagai fasilitas yang tersedia untuk mendukung aktivitas dan kesejahteraan warga desa.
                </p>
            </div>
        </div>

        @if ($fasilitas->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($fasilitas as $i => $f)
                    <div class="premium-card reveal delay-{{ $i % 4 }} bg-white rounded-2xl p-6 border border-slate-100">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-brand-900 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-slate-900 mb-1 truncate">{{ $f->nama }}</h3>
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    @if ($f->jenis)
                                        <span class="inline-flex items-center px-2.5 py-0.5 bg-gold-50 text-gold-700 text-[10px] font-bold uppercase tracking-wider rounded-md border border-gold-200">{{ $f->jenis }}</span>
                                    @endif
                                    @if ($f->rt)
                                        <span class="inline-flex items-center px-2.5 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded-md">RT {{ $f->rt->nama }}</span>
                                    @endif
                                </div>
                                @if ($f->keterangan)
                                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">{{ $f->keterangan }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 max-w-md mx-auto bg-white rounded-3xl border border-slate-100 reveal">
                <div class="w-16 h-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                </div>
                <p class="text-sm font-bold text-slate-900 mb-1">Belum ada data fasilitas</p>
                <p class="text-sm text-slate-500">Fasilitas umum akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>
</section>
