{{-- ============ FITUR ============ --}}
<section id="fitur" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <div class="ornament mb-6">
                <span class="text-gold-600 text-xs font-bold tracking-[0.3em] uppercase">Fitur Unggulan</span>
            </div>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-slate-900 leading-tight mb-5">
                Satu Platform untuk<br><span class="text-brand-900">Seluruh Layanan Desa</span>
            </h2>
            <p class="text-base text-slate-500 leading-relaxed">
                Dirancang khusus untuk memenuhi kebutuhan administrasi dan pelayanan publik tingkat desa dengan alur kerja yang efisien.
            </p>
        </div>

        @php
            $fiturs = [
                ['title' => 'Data Penduduk', 'desc' => 'Kelola data penduduk lengkap dengan kartu keluarga, rumah, dan wilayah administratif.', 'icon' => 'users'],
                ['title' => 'Surat Menyurat', 'desc' => 'Buat, kelola, dan arsipkan surat desa dengan template otomatis serta preview PDF.', 'icon' => 'mail'],
                ['title' => 'Peta Desa', 'desc' => 'Visualisasi interaktif lokasi rumah, fasilitas umum, dan batas wilayah desa.', 'icon' => 'map'],
                ['title' => 'Kartu Keluarga', 'desc' => 'Kelola data kartu keluarga beserta hubungan antar anggota keluarga secara rapi.', 'icon' => 'home'],
                ['title' => 'Pengumuman', 'desc' => 'Sampaikan informasi penting kepada seluruh warga desa secara real-time dan terpusat.', 'icon' => 'bell'],
                ['title' => 'Media & Dokumen', 'desc' => 'Simpan, kelola, dan akses kembali dokumen serta foto kegiatan desa dengan aman.', 'icon' => 'folder'],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-7">
            @foreach ($fiturs as $i => $f)
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                   class="premium-card reveal delay-{{ $i % 4 }} group relative bg-white rounded-3xl p-7 lg:p-8 border border-slate-100 block focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-700 focus-visible:ring-offset-2">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-900 to-brand-700 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        @if($f['icon'] === 'users')
                            <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        @elseif($f['icon'] === 'mail')
                            <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        @elseif($f['icon'] === 'map')
                            <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        @elseif($f['icon'] === 'home')
                            <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        @elseif($f['icon'] === 'bell')
                            <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @else
                            <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-brand-900 transition-colors">{{ $f['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $f['desc'] }}</p>
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-bold text-brand-900 uppercase tracking-wider">Selengkapnya</span>
                        <svg class="w-4 h-4 text-gold-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
