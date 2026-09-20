<div>
    <x-slot name="header">
        <span class="text-sm font-medium text-gray-400">Beranda / <span class="text-gray-700">Dashboard</span></span>
    </x-slot>

    @php
        // ── Helper tanggal Indonesia ──
        $hariID = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $bulanID = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        $bulanShort = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
        $tglLengkap = $hariID[now()->format('l')] . ', ' . now()->day . ' ' . $bulanID[now()->format('n')] . ' ' . now()->year;

        // ── Sapaan dinamis ──
        $jam = now()->hour;
        if ($jam >= 4 && $jam < 11) { $sapaan = 'Selamat Pagi'; }
        elseif ($jam >= 11 && $jam < 15) { $sapaan = 'Selamat Siang'; }
        elseif ($jam >= 15 && $jam < 19) { $sapaan = 'Selamat Sore'; }
        else { $sapaan = 'Selamat Malam'; }

        // ── Donut Gender ──
        $totalGender = array_sum($pendudukPerGender);
        $lakiPct = $totalGender > 0 ? round(($pendudukPerGender['L'] ?? 0) / $totalGender * 100) : 0;
        $perempuanPct = 100 - $lakiPct;

        // ── Donut RTLH ──
        $totalRtlh = array_sum($rumahPerRtlh);
        $layakPct = $totalRtlh > 0 ? round(($rumahPerRtlh['layak'] ?? 0) / $totalRtlh * 100) : 0;
        $tidakPct = 100 - $layakPct;

        // ── Area Chart Tren Surat ──
        $vals = array_values($suratPerBulan);
        $maxB = max(1, max($vals));
        $W = 600; $H = 220; $pl = 44; $pr = 16; $pt = 18; $pb = 36;
        $innerW = $W - $pl - $pr; $innerH = $H - $pt - $pb;
        $pts = [];
        $bulanKeys = array_keys($suratPerBulan);
        foreach ($vals as $i => $v) {
            $x = $pl + ($innerW * ($i / max(1, count($vals) - 1)));
            $y = $pt + $innerH * (1 - $v / $maxB);
            $m = $bulanShort[(int) \Carbon\Carbon::createFromFormat('Y-m', $bulanKeys[$i])->format('n')];
            $pts[] = ['x' => $x, 'y' => $y, 'v' => $v, 'm' => $m];
        }
        $line = '';
        foreach ($pts as $i => $p) { $line .= ($i ? ' L ' : 'M ') . round($p['x'], 1) . ' ' . round($p['y'], 1); }
        $area = $line . ' L ' . round($pts[count($pts)-1]['x'], 1) . ' ' . ($pt + $innerH) . ' L ' . $pl . ' ' . ($pt + $innerH) . ' Z';

        // ── Piramida Usia ──
        $maxUsia = 1;
        foreach ($usiaPerGender as $g) { $maxUsia = max($maxUsia, $g['L'], $g['P']); }

        // ── Pekerjaan ──
        $maxPk = max(1, $pendudukPerPekerjaan ? max($pendudukPerPekerjaan) : 1);
        $totalPk = array_sum($pendudukPerPekerjaan);
        $pkBar = ['from-indigo-500 to-violet-400', 'from-cyan-500 to-sky-400', 'from-emerald-500 to-teal-400', 'from-amber-500 to-orange-400', 'from-rose-500 to-pink-400', 'from-slate-400 to-slate-300'];

        // ── Pendidikan ──
        $pendChip = ['bg-indigo-50 text-indigo-700 ring-indigo-200', 'bg-cyan-50 text-cyan-700 ring-cyan-200', 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'bg-amber-50 text-amber-700 ring-amber-200', 'bg-rose-50 text-rose-700 ring-rose-200', 'bg-violet-50 text-violet-700 ring-violet-200', 'bg-sky-50 text-sky-700 ring-sky-200', 'bg-slate-100 text-slate-600 ring-slate-200'];

        // ── Badge status surat ──
        $badgeSurat = ['diajukan' => 'bg-blue-100 text-blue-700', 'diproses' => 'bg-amber-100 text-amber-700', 'selesai' => 'bg-emerald-100 text-emerald-700', 'ditolak' => 'bg-rose-100 text-rose-700', 'draft' => 'bg-gray-100 text-gray-600', 'dibatalkan' => 'bg-slate-100 text-slate-500'];
    @endphp

    {{-- ═══════════ HERO ═══════════ --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-500 p-7 sm:p-9 text-white shadow-2xl shadow-indigo-500/25 mb-7 animate-fade-up">
        <div class="absolute -top-20 -right-16 w-72 h-72 bg-white/15 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 left-1/3 w-80 h-80 bg-cyan-300/20 rounded-full blur-3xl"></div>
        <div class="absolute top-6 right-8 w-24 h-24 border border-white/20 rounded-full"></div>
        <div class="absolute top-12 right-16 w-24 h-24 border border-white/10 rounded-full"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-5">
            <div class="flex-1">
                <p class="text-indigo-100 text-sm font-medium tracking-wide">{{ $sapaan }} 👋</p>
                <h1 class="text-2xl sm:text-3xl font-extrabold mt-1">Selamat datang di Dashboard {{ $namaDesa }}</h1>
                <p class="text-indigo-100/90 text-sm mt-2">{{ $tglLengkap }} — Ringkasan data kependudukan & layanan desa hari ini.</p>
            </div>
            <div class="hidden sm:flex w-20 h-20 bg-white/15 backdrop-blur-md border border-white/25 rounded-2xl items-center justify-center flex-shrink-0">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- ═══════════ STAT CARDS ═══════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        <div class="group relative overflow-hidden bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-5 shadow-xl shadow-slate-200/50 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 animate-fade-up" style="animation-delay: .05s">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Penduduk</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1.5 tracking-tight">{{ number_format($totalPenduduk) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
            </div>
            <p class="mt-3"><span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-600">Jiwa aktif terdaftar</span></p>
        </div>

        <div class="group relative overflow-hidden bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-5 shadow-xl shadow-slate-200/50 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 animate-fade-up" style="animation-delay: .1s">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Kartu Keluarga</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1.5 tracking-tight">{{ number_format($totalKk) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-500 to-sky-500 flex items-center justify-center shadow-lg shadow-cyan-500/30 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm4.125 2.625a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"/></svg>
                </div>
            </div>
            <p class="mt-3"><span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-cyan-50 text-cyan-600">Keluarga terdaftar</span></p>
        </div>

        <div class="group relative overflow-hidden bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-5 shadow-xl shadow-slate-200/50 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 animate-fade-up" style="animation-delay: .15s">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Rumah</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1.5 tracking-tight">{{ number_format($totalRumah) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                </div>
            </div>
            <p class="mt-3"><span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">Bangunan terdata</span></p>
        </div>

        <div class="group relative overflow-hidden bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-5 shadow-xl shadow-slate-200/50 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 animate-fade-up" style="animation-delay: .2s">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Surat Menunggu</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1.5 tracking-tight">{{ number_format($suratMenunggu) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="mt-3"><span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-rose-50 text-rose-600">Perlu diproses</span></p>
        </div>
    </div>

    {{-- ═══════════ ROW: TREN SURAT + GENDER ═══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-6 shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: .25s">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Tren Pengajuan Surat</h3>
                    <p class="text-sm text-gray-500 mt-0.5">6 bulan terakhir</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-600">Total {{ number_format(array_sum($suratPerBulan)) }} surat</span>
            </div>
            <svg viewBox="0 0 600 220" class="w-full h-56" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="gradSuratFill" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#6366f1" stop-opacity="0.35"/>
                        <stop offset="100%" stop-color="#06b6d4" stop-opacity="0.02"/>
                    </linearGradient>
                    <linearGradient id="gradSuratLine" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#6366f1"/>
                        <stop offset="100%" stop-color="#06b6d4"/>
                    </linearGradient>
                </defs>
                @foreach ([0, 0.25, 0.5, 0.75, 1] as $f)
                    @php $gy = $pt + $innerH * (1 - $f); @endphp
                    <line x1="{{ $pl }}" y1="{{ $gy }}" x2="{{ $W - $pr }}" y2="{{ $gy }}" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 5"/>
                    <text x="{{ $pl - 8 }}" y="{{ $gy + 3.5 }}" text-anchor="end" font-size="10" fill="#94a3b8">{{ round($maxB * $f) }}</text>
                @endforeach
                <path d="{{ $area }}" fill="url(#gradSuratFill)"/>
                <path d="{{ $line }}" fill="none" stroke="url(#gradSuratLine)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                @foreach ($pts as $p)
                    <circle cx="{{ round($p['x'], 1) }}" cy="{{ round($p['y'], 1) }}" r="4.5" fill="#ffffff" stroke="#6366f1" stroke-width="2.5"/>
                    <text x="{{ round($p['x'], 1) }}" y="{{ $H - 12 }}" text-anchor="middle" font-size="11" font-weight="600" fill="#64748b">{{ $p['m'] }}</text>
                @endforeach
            </svg>
        </div>

        <div class="bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-6 shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: .3s">
            <h3 class="text-base font-bold text-gray-900">Jenis Kelamin</h3>
            <p class="text-sm text-gray-500 mt-0.5">Komposisi warga aktif</p>
            <div class="flex items-center justify-center mt-4">
                <div class="relative w-36 h-36">
                    <svg class="w-36 h-36 -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#eef2f7" stroke-width="13"/>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="url(#gradGender)" stroke-width="13" stroke-dasharray="{{ $lakiPct * 3.14 }} {{ 314 - $lakiPct * 3.14 }}" stroke-linecap="round"/>
                        <defs><linearGradient id="gradGender" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#6366f1"/><stop offset="100%" stop-color="#06b6d4"/></linearGradient></defs>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-xl font-extrabold text-gray-900">{{ $lakiPct }}%</span>
                        <span class="text-[10px] text-gray-400 font-medium">Laki-laki</span>
                    </div>
                </div>
            </div>
            <div class="mt-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span><span class="text-sm text-gray-600">Laki-laki</span></div>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($pendudukPerGender['L'] ?? 0) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span><span class="text-sm text-gray-600">Perempuan</span></div>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($pendudukPerGender['P'] ?? 0) }}</span>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-gray-100 flex flex-wrap gap-1.5">
                <span class="text-[11px] font-semibold px-2 py-1 rounded-lg bg-emerald-50 text-emerald-600">Aktif {{ number_format($pendudukPerStatus['aktif'] ?? 0) }}</span>
                <span class="text-[11px] font-semibold px-2 py-1 rounded-lg bg-amber-50 text-amber-600">Pindah {{ number_format($pendudukPerStatus['pindah'] ?? 0) }}</span>
                <span class="text-[11px] font-semibold px-2 py-1 rounded-lg bg-gray-100 text-gray-500">Meninggal {{ number_format($pendudukPerStatus['meninggal'] ?? 0) }}</span>
            </div>
        </div>
    </div>

    {{-- ═══════════ ROW: PIRAMIDA USIA + RTLH ═══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-6 shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: .35s">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Piramida Usia</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Struktur umur warga per jenis kelamin</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-medium text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>Laki-laki</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>Perempuan</span>
                </div>
            </div>
            <div class="space-y-2.5">
                @foreach ($usiaPerGender as $kel => $g)
                    <div class="grid grid-cols-[1fr_3.5rem_1fr] items-center gap-2">
                        <div class="flex items-center justify-end gap-2">
                            <span class="text-[11px] font-semibold text-indigo-600 w-7 text-right">{{ $g['L'] > 0 ? number_format($g['L']) : '' }}</span>
                            <div class="flex-1 flex justify-end">
                                <div class="h-6 rounded-l-xl bg-gradient-to-l from-indigo-500 to-violet-400 transition-all duration-700" style="width: {{ $g['L'] / $maxUsia * 100 }}%; min-width: {{ $g['L'] > 0 ? '6px' : '0' }}"></div>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-gray-500 text-center">{{ $kel }}</span>
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <div class="h-6 rounded-r-xl bg-gradient-to-r from-rose-500 to-pink-400 transition-all duration-700" style="width: {{ $g['P'] / $maxUsia * 100 }}%; min-width: {{ $g['P'] > 0 ? '6px' : '0' }}"></div>
                            </div>
                            <span class="text-[11px] font-semibold text-rose-600 w-7">{{ $g['P'] > 0 ? number_format($g['P']) : '' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-6 shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: .4s">
            <h3 class="text-base font-bold text-gray-900">Kelayakan Rumah (RTLH)</h3>
            <p class="text-sm text-gray-500 mt-0.5">Kelayakan hunian warga</p>
            <div class="flex items-center justify-center mt-4">
                <div class="relative w-36 h-36">
                    <svg class="w-36 h-36 -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#eef2f7" stroke-width="13"/>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="url(#gradRtlh)" stroke-width="13" stroke-dasharray="{{ $layakPct * 3.14 }} {{ 314 - $layakPct * 3.14 }}" stroke-linecap="round"/>
                        <defs><linearGradient id="gradRtlh" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#10b981"/><stop offset="100%" stop-color="#14b8a6"/></linearGradient></defs>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-xl font-extrabold text-gray-900">{{ $layakPct }}%</span>
                        <span class="text-[10px] text-gray-400 font-medium">Layak huni</span>
                    </div>
                </div>
            </div>
            <div class="mt-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span><span class="text-sm text-gray-600">Layak Huni</span></div>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($rumahPerRtlh['layak'] ?? 0) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span><span class="text-sm text-gray-600">Tidak Layak</span></div>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($rumahPerRtlh['tidak_layak'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ ROW: PEKERJAAN + PENDIDIKAN + STATUS SURAT ═══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-6 shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: .45s">
            <h3 class="text-base font-bold text-gray-900">Mata Pencaharian</h3>
            <p class="text-sm text-gray-500 mt-0.5">Top 5 pekerjaan warga</p>
            <div class="mt-5 space-y-4">
                @forelse ($pendudukPerPekerjaan as $pekerjaan => $jumlah)
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-medium text-gray-700 truncate pr-2">{{ $pekerjaan }}</span>
                            <span class="text-xs font-bold text-gray-500 flex-shrink-0">{{ $totalPk > 0 ? round($jumlah / $totalPk * 100) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100/80 rounded-full h-2.5">
                            @php $ci = $loop->index % count($pkBar); @endphp
                            <div class="h-2.5 rounded-full bg-gradient-to-r {{ $pkBar[$ci] }} transition-all duration-700" style="width: {{ $jumlah / $maxPk * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada data pekerjaan.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-6 shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: .5s">
            <h3 class="text-base font-bold text-gray-900">Pendidikan Terakhir</h3>
            <p class="text-sm text-gray-500 mt-0.5">Tingkat pendidikan warga aktif</p>
            <div class="mt-5 flex flex-wrap gap-2">
                @forelse ($pendudukPerPendidikan as $pend => $jumlah)
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl ring-1 {{ $pendChip[$loop->index % count($pendChip)] }}">
                        {{ $pend }}
                        <span class="opacity-60">·</span>
                        <span class="font-extrabold">{{ number_format($jumlah) }}</span>
                    </span>
                @empty
                    <p class="text-sm text-gray-400">Belum ada data pendidikan.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-6 shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: .55s">
            <h3 class="text-base font-bold text-gray-900">Status Surat</h3>
            <p class="text-sm text-gray-500 mt-0.5">Seluruh pengajuan</p>
            <div class="mt-5 space-y-2.5">
                @foreach (['diajukan', 'diproses', 'selesai', 'ditolak'] as $st)
                    <div class="flex items-center justify-between px-4 py-3 rounded-2xl {{ $badgeSurat[$st] }} bg-opacity-60">
                        <span class="text-sm font-semibold capitalize">{{ $st }}</span>
                        <span class="text-lg font-extrabold">{{ number_format($suratPerStatus[$st] ?? 0) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══════════ ROW: AKTIVITAS + AKSI CEPAT ═══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-6 shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: .6s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900">Surat Terbaru</h3>
                <a href="{{ route('surat.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat semua →</a>
            </div>
            <div class="space-y-3">
                @forelse ($suratTerbaru as $s)
                    <div class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-white/80 transition-colors">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-100 to-cyan-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $s->penduduk?->nama ?? 'Umum' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $s->jenis_surat }} · {{ $s->created_at->format('d M') }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-lg flex-shrink-0 {{ $badgeSurat[$s->status] ?? 'bg-gray-100 text-gray-500' }}">{{ ucfirst($s->status) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 py-4 text-center">Belum ada pengajuan surat.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-6 shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: .65s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900">Penduduk Terbaru</h3>
                <a href="{{ route('penduduk.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat semua →</a>
            </div>
            <div class="space-y-3">
                @forelse ($pendudukTerbaru as $p)
                    <div class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-white/80 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $p->jenis_kelamin === 'L' ? 'from-indigo-400 to-violet-400' : 'from-rose-400 to-pink-400' }} flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-xs">{{ substr($p->nama, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $p->nama }}</p>
                            <p class="text-xs text-gray-500 truncate">NIK {{ $p->nik }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-lg flex-shrink-0 {{ $p->jenis_kelamin === 'L' ? 'bg-indigo-50 text-indigo-600' : 'bg-rose-50 text-rose-600' }}">{{ $p->jenis_kelamin }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 py-4 text-center">Belum ada data penduduk.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white/60 backdrop-blur-xl border border-white/70 rounded-3xl p-6 shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: .7s">
            <h3 class="text-base font-bold text-gray-900">Aksi Cepat</h3>
            <p class="text-sm text-gray-500 mt-0.5">Shortcut menu utama</p>
            <div class="mt-4 space-y-2">
                <a href="{{ route('penduduk.create') }}" class="group flex items-center gap-3 p-3 rounded-2xl hover:bg-white/80 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center shadow-md shadow-indigo-500/25 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                    </div>
                    <div><p class="text-sm font-semibold text-gray-900">Tambah Penduduk</p><p class="text-xs text-gray-500">Input data warga baru</p></div>
                </a>
                <a href="{{ route('kartu-keluarga.create') }}" class="group flex items-center gap-3 p-3 rounded-2xl hover:bg-white/80 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-md shadow-emerald-500/25 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <div><p class="text-sm font-semibold text-gray-900">Tambah Kartu Keluarga</p><p class="text-xs text-gray-500">Registrasi KK baru</p></div>
                </a>
                <a href="{{ route('surat.kelola') }}" class="group flex items-center gap-3 p-3 rounded-2xl hover:bg-white/80 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-md shadow-amber-500/25 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12"/></svg>
                    </div>
                    <div><p class="text-sm font-semibold text-gray-900">Kelola Surat</p><p class="text-xs text-gray-500">Proses pengajuan warga</p></div>
                </a>
                <a href="{{ route('peta.index') }}" class="group flex items-center gap-3 p-3 rounded-2xl hover:bg-white/80 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-sky-500 flex items-center justify-center shadow-md shadow-cyan-500/25 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v6.75m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg>
                    </div>
                    <div><p class="text-sm font-semibold text-gray-900">Lihat Peta Desa</p><p class="text-xs text-gray-500">Peta interaktif wilayah</p></div>
                </a>
            </div>
        </div>
    </div>
</div>
