{{-- ============ KONTAK ============ --}}
<section id="kontak" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-14 reveal">
            <div class="ornament mb-6">
                <span class="text-gold-600 text-xs font-bold tracking-[0.3em] uppercase">Hubungi Kami</span>
            </div>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-slate-900 leading-tight mb-5">
                Kontak &amp; <span class="text-brand-900">Jam Layanan</span>
            </h2>
            <p class="text-base text-slate-500 leading-relaxed">
                Kantor Desa siap melayani warga pada jam kerja. Silakan datang atau hubungi kami.
            </p>
        </div>

        @php
            $kontak = \App\Models\AdminSetting::get('kontak_desa', []);
            $alamat = $kontak['alamat'] ?? 'Kantor Desa Waeleman, Kabupaten Malaka, Nusa Tenggara Timur';
            $telepon = $kontak['telepon'] ?? '-';
            $email = $kontak['email'] ?? 'pemdes@waeleman.desa.id';
        @endphp

        <div class="grid lg:grid-cols-3 gap-6 lg:gap-7">
            <div class="reveal bg-white rounded-3xl p-7 lg:p-8 border border-slate-100 premium-card">
                <div class="w-12 h-12 rounded-2xl bg-brand-900 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="text-[10px] font-bold text-gold-600 uppercase tracking-widest mb-2">Alamat Kantor</div>
                <p class="text-sm font-semibold text-slate-900 leading-relaxed">{{ $alamat }}</p>
            </div>

            <div class="reveal delay-1 bg-white rounded-3xl p-7 lg:p-8 border border-slate-100 premium-card">
                <div class="w-12 h-12 rounded-2xl bg-brand-900 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div class="text-[10px] font-bold text-gold-600 uppercase tracking-widest mb-2">Telepon / WhatsApp</div>
                <p class="text-sm font-semibold text-slate-900">{{ $telepon }}</p>
                <p class="text-xs text-slate-500 mt-1">Senin–Jumat, 08.00–15.00 WITA</p>
            </div>

            <div class="reveal delay-2 bg-white rounded-3xl p-7 lg:p-8 border border-slate-100 premium-card">
                <div class="w-12 h-12 rounded-2xl bg-brand-900 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div class="text-[10px] font-bold text-gold-600 uppercase tracking-widest mb-2">Email Resmi</div>
                <p class="text-sm font-semibold text-slate-900 break-all">{{ $email }}</p>
                <p class="text-xs text-slate-500 mt-1">Balasan dalam 1×24 jam kerja</p>
            </div>
        </div>
    </div>
</section>
