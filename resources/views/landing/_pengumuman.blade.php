{{-- ============ PENGUMUMAN ============ --}}
<section id="pengumuman" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-14 gap-6 reveal">
            <div>
                <div class="ornament mb-6 !justify-start">
                    <span class="text-gold-600 text-xs font-bold tracking-[0.3em] uppercase">Informasi Publik</span>
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-slate-900 leading-tight">
                    Pengumuman <span class="text-brand-900">Terbaru</span>
                </h2>
                <p class="text-base text-slate-500 mt-3 max-w-xl">
                    Informasi, kegiatan, dan berita terbaru dari {{ $desa->nama_desa ?? 'Desa Waeleman' }}.
                </p>
            </div>
            <a href="{{ route('pengumuman.publik') }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand-900 hover:text-gold-600 transition-colors group whitespace-nowrap shrink-0 fancy-underline">
                Lihat Semua
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        @if ($pengumumans->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-7">
                @foreach ($pengumumans as $i => $item)
                    <a href="{{ route('pengumuman.publik') }}#pengumuman-{{ $item->id }}"
                       class="premium-card reveal delay-{{ $i }} group relative bg-white rounded-3xl p-7 lg:p-8 border border-slate-100 block focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-700 focus-visible:ring-offset-2">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="inline-flex items-center px-2.5 py-1 {{ $item->kategori ? 'bg-brand-50 text-brand-800 border border-brand-100' : 'bg-slate-100 text-slate-600' }} text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                {{ $item->kategori ?: 'Umum' }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">{{ $item->tanggal_publish->translatedFormat('d M Y') }}</span>
                        </div>
                        <h3 class="text-lg font-bold mb-2.5 line-clamp-2 text-slate-900 group-hover:text-brand-900 transition-colors leading-snug">{{ $item->judul }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-3 leading-relaxed">{{ strip_tags($item->isi) }}</p>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-brand-900 uppercase tracking-wider">Baca Selengkapnya</span>
                            <svg class="w-4 h-4 text-gold-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 max-w-md mx-auto bg-white rounded-3xl border border-slate-100 reveal">
                <div class="w-16 h-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <p class="text-sm font-bold text-slate-900 mb-1">Belum ada pengumuman</p>
                <p class="text-sm text-slate-500">Pengumuman terbaru akan tampil di sini.</p>
            </div>
        @endif
    </div>
</section>
