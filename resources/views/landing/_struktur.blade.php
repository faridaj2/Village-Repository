{{-- ============ STRUKTUR PEMERINTAHAN ============ --}}
<section id="struktur" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <div class="ornament mb-6">
                <span class="text-gold-600 text-xs font-bold tracking-[0.3em] uppercase">Pemerintahan Desa</span>
            </div>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-slate-900 leading-tight mb-5">
                Struktur <span class="text-brand-900">Pemerintahan</span>
            </h2>
            <p class="text-base text-slate-500 leading-relaxed">
                Perangkat desa yang siap melayani warga {{ $desa->nama_desa ?? 'Desa Waeleman' }} dengan sepenuh hati.
            </p>
        </div>

        @if ($struktur->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($struktur as $i => $orang)
                    @php
                        $initials = collect(explode(' ', $orang->nama))
                            ->filter()
                            ->take(2)
                            ->map(fn($w) => mb_substr($w, 0, 1))
                            ->implode('');
                    @endphp
                    <div class="struct-card reveal delay-{{ $i % 4 }} rounded-2xl overflow-hidden">
                        <div class="aspect-[4/5] bg-gradient-to-br from-brand-900 to-brand-950 flex items-center justify-center relative">
                            @if ($orang->foto)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($orang->foto) }}"
                                     alt="Foto {{ $orang->nama }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gold-500/20 border-2 border-gold-500/40 flex items-center justify-center">
                                    <span class="font-display text-3xl text-gold-400">{{ $initials ?: '?' }}</span>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3 px-2.5 py-1 bg-white/15 backdrop-blur-md border border-white/25 rounded-lg">
                                <span class="text-[10px] font-bold text-white uppercase tracking-widest">Desa</span>
                            </div>
                        </div>
                        <div class="p-5 text-center">
                            <div class="text-[10px] font-bold text-gold-600 uppercase tracking-widest mb-1.5">{{ $orang->jabatan }}</div>
                            <div class="font-bold text-slate-900 leading-tight">{{ $orang->nama }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 max-w-md mx-auto reveal">
                <div class="w-16 h-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-sm font-bold text-slate-900 mb-1">Data struktur belum tersedia</p>
                <p class="text-sm text-slate-500">Data perangkat desa akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>
</section>
