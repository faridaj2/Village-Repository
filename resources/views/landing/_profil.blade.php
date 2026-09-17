{{-- ============ PROFIL DESA ============ --}}
<section id="profil" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-white overflow-hidden">
    <div class="absolute top-40 -left-40 w-96 h-96 bg-brand-50 rounded-full blur-3xl" aria-hidden="true"></div>
    <div class="max-w-7xl mx-auto relative">
        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">

            {{-- Text --}}
            <div class="lg:col-span-6 reveal">
                <div class="ornament mb-6 !justify-start">
                    <span class="text-gold-600 text-xs font-bold tracking-[0.3em] uppercase">Tentang Kami</span>
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-slate-900 leading-tight mb-6">
                    Mengenal <span class="text-brand-900">{{ $desa->nama_desa ?? 'Desa Waeleman' }}</span> Lebih Dekat
                </h2>
                <p class="text-base text-slate-600 leading-relaxed mb-6">
                    Desa Waeleman adalah salah satu desa di Kabupaten Malaka, Provinsi Nusa Tenggara Timur. Kami berkomitmen menghadirkan tata kelola pemerintahan desa yang bersih, transparan, dan berpihak pada kesejahteraan warga melalui pemanfaatan teknologi informasi.
                </p>
                <p class="text-base text-slate-600 leading-relaxed mb-8">
                    Melalui Sistem Informasi Desa (SIDESA), seluruh proses administrasi — mulai dari pencatatan penduduk, kartu keluarga, hingga pelayanan surat-menyurat — kini dapat dilakukan secara cepat, akurat, dan terdokumentasi dengan baik.
                </p>

                <div class="grid grid-cols-2 gap-5 mb-8">
                    <div class="p-5 rounded-2xl bg-brand-50 border border-brand-100">
                        <div class="text-xs font-bold text-brand-700 uppercase tracking-widest mb-2">Visi</div>
                        <p class="text-sm text-brand-900 leading-relaxed font-medium">Terwujudnya Desa Waeleman yang maju, mandiri, dan sejahtera berbasis pelayanan digital.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-gold-50 border border-gold-200">
                        <div class="text-xs font-bold text-gold-700 uppercase tracking-widest mb-2">Misi</div>
                        <p class="text-sm text-gold-900 leading-relaxed font-medium">Meningkatkan kualitas pelayanan publik, transparansi data, dan partisipasi warga desa.</p>
                    </div>
                </div>

                <a href="#struktur" class="inline-flex items-center gap-2 text-sm font-bold text-brand-900 hover:text-gold-600 transition-colors fancy-underline">
                    Lihat Struktur Pemerintahan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            {{-- Visual --}}
            <div class="lg:col-span-6 reveal delay-2">
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-br from-brand-900 to-gold-500 rounded-[2.5rem] opacity-10 blur-2xl" aria-hidden="true"></div>
                    <div class="relative aspect-[4/3] rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white">
                        <img src="{{ $heroSliders[0]['img'] ?? 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&h=900&fit=crop' }}"
                             alt="Panorama Desa Waeleman"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-950/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 inset-x-0 p-6 sm:p-8 text-white">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-2 h-2 rounded-full bg-gold-400"></span>
                                <span class="text-[10px] font-bold tracking-[0.25em] uppercase text-gold-300">Kabupaten Malaka</span>
                            </div>
                            <div class="font-display text-2xl sm:text-3xl">{{ $desa->nama_desa ?? 'Desa Waeleman' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
