<div>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
            <p class="text-sm text-gray-500 mt-1">Ringkasan data kependudukan desa</p>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Penduduk -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/25">
            <div class="relative z-10">
                <p class="text-blue-100 text-sm font-medium">Total Penduduk</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalPenduduk) }}</p>
                <p class="text-blue-200 text-xs mt-2">Jiwa terdaftar</p>
            </div>
            <div class="absolute -bottom-4 -right-4 opacity-10">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
        </div>

        <!-- KK -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-lg shadow-emerald-500/25">
            <div class="relative z-10">
                <p class="text-emerald-100 text-sm font-medium">Kartu Keluarga</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalKk) }}</p>
                <p class="text-emerald-200 text-xs mt-2">KK terdaftar</p>
            </div>
            <div class="absolute -bottom-4 -right-4 opacity-10">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
            </div>
        </div>

        <!-- Rumah -->
        <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-5 text-white shadow-lg shadow-amber-500/25">
            <div class="relative z-10">
                <p class="text-amber-100 text-sm font-medium">Total Rumah</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalRumah) }}</p>
                <p class="text-amber-200 text-xs mt-2">Bangunan terdata</p>
            </div>
            <div class="absolute -bottom-4 -right-4 opacity-10">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 9.3V4h-3v2.6L12 3 2 12h3v8h5v-6h4v6h5v-8h3l-3-2.7zm-9 .7c0-1.1.9-2 2-2s2 .9 2 2h-4z"/>
                </svg>
            </div>
        </div>

        <!-- Surat -->
        <div class="relative overflow-hidden bg-gradient-to-br from-rose-500 to-pink-500 rounded-2xl p-5 text-white shadow-lg shadow-rose-500/25">
            <div class="relative z-10">
                <p class="text-rose-100 text-sm font-medium">Surat Menunggu</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($suratMenunggu) }}</p>
                <p class="text-rose-200 text-xs mt-2">Perlu diproses</p>
            </div>
            <div class="absolute -bottom-4 -right-4 opacity-10">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Gender Distribution -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Penduduk per Jenis Kelamin</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Distribusi laki-laki & perempuan</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
            @php
                $total = array_sum($pendudukPerGender);
                $lakiPct = $total > 0 ? round(($pendudukPerGender['L'] ?? 0) / $total * 100) : 0;
                $perempuanPct = 100 - $lakiPct;
            @endphp
            <div class="flex items-center gap-6">
                <div class="relative w-32 h-32">
                    <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#f3f4f6" stroke-width="12"/>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#3b82f6" stroke-width="12"
                            stroke-dasharray="{{ $lakiPct * 3.14 }} {{ 314 - $lakiPct * 3.14 }}"
                            stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-lg font-bold text-gray-900">{{ $lakiPct }}%</span>
                    </div>
                </div>
                <div class="flex-1 space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                <span class="text-sm text-gray-600">Laki-laki</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($pendudukPerGender['L'] ?? 0) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ $lakiPct }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-pink-500"></div>
                                <span class="text-sm text-gray-600">Perempuan</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($pendudukPerGender['P'] ?? 0) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-pink-500 h-2 rounded-full transition-all duration-500" style="width: {{ $perempuanPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RTLH Distribution -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Kategori Rumah RTLH</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Kelayakan hunian warga</p>
                </div>
                <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
            </div>
            @php
                $totalR = array_sum($rumahPerRtlh);
                $layakPct = $totalR > 0 ? round(($rumahPerRtlh['layak'] ?? 0) / $totalR * 100) : 0;
                $tidakPct = 100 - $layakPct;
            @endphp
            <div class="flex items-center gap-6">
                <div class="relative w-32 h-32">
                    <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#f3f4f6" stroke-width="12"/>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#22c55e" stroke-width="12"
                            stroke-dasharray="{{ $layakPct * 3.14 }} {{ 314 - $layakPct * 3.14 }}"
                            stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-lg font-bold text-gray-900">{{ $layakPct }}%</span>
                    </div>
                </div>
                <div class="flex-1 space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <span class="text-sm text-gray-600">Layak Huni</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($rumahPerRtlh['layak'] ?? 0) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $layakPct }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <span class="text-sm text-gray-600">Tidak Layak</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($rumahPerRtlh['tidak_layak'] ?? 0) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full transition-all duration-500" style="width: {{ $tidakPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Surat Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Status Surat</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Pengajuan surat warga</p>
                </div>
            </div>
            <div class="space-y-3">
                @php
                    $suratStatuses = [
                        'diajukan' => [
                            'bg' => 'bg-blue-50', 'text' => 'text-blue-700',
                            'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                        ],
                        'diproses' => [
                            'bg' => 'bg-amber-50', 'text' => 'text-amber-700',
                            'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                        'selesai' => [
                            'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700',
                            'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                        'ditolak' => [
                            'bg' => 'bg-red-50', 'text' => 'text-red-700',
                            'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                    ];
                @endphp
                @foreach ($suratStatuses as $status => $style)
                    <div class="flex items-center justify-between p-3 {{ $style['bg'] }} rounded-xl">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ $style['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $style['svg'] !!}</svg>
                            <span class="text-sm font-medium {{ $style['text'] }}">{{ ucfirst($status) }}</span>
                        </div>
                        <span class="text-lg font-bold {{ $style['text'] }}">{{ number_format($suratPerStatus[$status] ?? 0) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Status Penduduk -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Status Penduduk</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Data keaktifan warga</p>
                </div>
            </div>
            <div class="space-y-3">
                @php
                    $pendudukStatuses = [
                        'aktif' => [
                            'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700',
                            'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
                        ],
                        'pindah' => [
                            'bg' => 'bg-amber-50', 'text' => 'text-amber-700',
                            'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
                        ],
                        'meninggal' => [
                            'bg' => 'bg-gray-100', 'text' => 'text-gray-600',
                            'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
                        ],
                    ];
                @endphp
                @foreach ($pendudukStatuses as $status => $style)
                    <div class="flex items-center justify-between p-3 {{ $style['bg'] }} rounded-xl">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ $style['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $style['svg'] !!}</svg>
                            <span class="text-sm font-medium {{ $style['text'] }}">{{ ucfirst($status) }}</span>
                        </div>
                        <span class="text-lg font-bold {{ $style['text'] }}">{{ number_format($pendudukPerStatus[$status] ?? 0) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Aksi Cepat</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Shortcut menu</p>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('penduduk.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Tambah Penduduk</p>
                        <p class="text-xs text-gray-500">Input data warga baru</p>
                    </div>
                </a>
                <a href="{{ route('surat.kelola') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Kelola Surat</p>
                        <p class="text-xs text-gray-500">Proses pengajuan surat</p>
                    </div>
                </a>
                <a href="{{ route('peta.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Lihat Peta</p>
                        <p class="text-xs text-gray-500">Peta interaktif desa</p>
                    </div>
                </a>
                <a href="{{ route('penduduk.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                    <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center group-hover:bg-rose-200 transition-colors">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Tambah Penduduk</p>
                        <p class="text-xs text-gray-500">Input data warga baru</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
