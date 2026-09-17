{{-- ============ FOOTER ============ --}}
<footer class="bg-brand-950 text-white/70 pt-16 pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12 pb-12 border-b border-white/10">

            <div class="lg:col-span-2">
                <div class="flex items-center gap-3 mb-5">
                    <img src="{{ asset('logo-tagline.png') }}" alt="SIDESA" class="h-11 w-auto">
                </div>
                <p class="text-sm leading-relaxed max-w-md mb-6">
                    Sistem Informasi Desa {{ $desa->nama_desa ?? 'Waeleman' }} — platform terpadu untuk pelayanan administrasi desa yang cepat, transparan, dan akuntabel.
                </p>
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-gold-500"></span>
                    <span class="text-[10px] font-bold tracking-[0.25em] uppercase text-gold-400">Kabupaten Malaka · NTT</span>
                </div>
            </div>

            <div>
                <div class="text-[11px] font-bold text-gold-400 uppercase tracking-widest mb-5">Tautan Cepat</div>
                <ul class="space-y-3 text-sm">
                    <li><a href="#profil" class="hover:text-gold-400 transition-colors fancy-underline">Profil Desa</a></li>
                    <li><a href="#struktur" class="hover:text-gold-400 transition-colors fancy-underline">Pemerintahan</a></li>
                    <li><a href="#fasilitas" class="hover:text-gold-400 transition-colors fancy-underline">Fasilitas Umum</a></li>
                    <li><a href="#peta" class="hover:text-gold-400 transition-colors fancy-underline">Peta Desa</a></li>
                    <li><a href="{{ route('pengumuman.publik') }}" class="hover:text-gold-400 transition-colors fancy-underline">Pengumuman</a></li>
                </ul>
            </div>

            <div>
                <div class="text-[11px] font-bold text-gold-400 uppercase tracking-widest mb-5">Kontak</div>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-gold-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Kantor Desa Waeleman, Malaka, NTT</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-gold-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>pemdes@waeleman.desa.id</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-gold-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Senin–Jumat, 08.00–15.00 WITA</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs">&copy; {{ date('Y') }} Pemerintah Desa {{ $desa->nama_desa ?? 'Waeleman' }}. Hak cipta dilindungi.</p>
            <p class="text-xs">Dibangun dengan <span class="text-gold-500">❤</span> untuk desa yang lebih baik</p>
        </div>
    </div>
</footer>
