<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kartu-keluarga.index') }}" class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Detail Kartu Keluarga</h2>
                <p class="text-sm text-gray-500">{{ $kartuKeluarga->no_kk }}</p>
            </div>
        </div>
    </x-slot>

    <x-flash-toast />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">{{ substr($kartuKeluarga->kepalaKeluarga?->nama ?? '?', 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">{{ $kartuKeluarga->kepalaKeluarga?->nama ?? 'Belum ada Kepala Keluarga' }}</h3>
                            <p class="text-emerald-100 text-sm font-mono">{{ $kartuKeluarga->no_kk }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">No. KK</p>
                        <p class="text-sm font-mono font-medium text-gray-900 mt-1">{{ $kartuKeluarga->no_kk }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kepala Keluarga</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $kartuKeluarga->kepalaKeluarga?->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rumah</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $kartuKeluarga->rumah?->kode_rumah ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Alamat</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $kartuKeluarga->alamat ?? '-' }}</p>
                    </div>
                    @if ($kartuKeluarga->rumah)
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Lokasi</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                @if ($kartuKeluarga->rumah->rt)
                                    RT {{ $kartuKeluarga->rumah->rt->nama }} / RW {{ $kartuKeluarga->rumah->rt->rw->nama ?? '-' }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Anggota Keluarga -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Anggota Keluarga ({{ $kartuKeluarga->anggota->count() }} orang)</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/80">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">NIK</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Nama</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Kelamin</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Status KK</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($kartuKeluarga->anggota as $anggota)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-4 text-sm font-mono text-gray-700">{{ $anggota->nik }}</td>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('penduduk.show', $anggota) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">{{ $anggota->nama }}</a>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-600">{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td class="py-3 px-4">
                                        @if ($anggota->status_kk)
                                            @php
                                                $sk = match($anggota->status_kk) {
                                                    'suami' => ['bg-indigo-50 text-indigo-700', 'Suami'],
                                                    'istri' => ['bg-pink-50 text-pink-700', 'Istri'],
                                                    'anak' => ['bg-blue-50 text-blue-700', 'Anak'],
                                                    default => ['bg-gray-100 text-gray-600', '-'],
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 {{ $sk[0] }} text-xs font-medium rounded-lg">{{ $sk[1] }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        @php
                                            $s = match($anggota->status) {
                                                'aktif' => ['bg-emerald-50 text-emerald-700', 'Aktif'],
                                                'pindah' => ['bg-amber-50 text-amber-700', 'Pindah'],
                                                'meninggal' => ['bg-gray-100 text-gray-600', 'Meninggal'],
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 {{ $s[0] }} text-xs font-medium rounded-lg">{{ $s[1] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-sm text-gray-500">Belum ada anggota keluarga</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Info Rumah -->
            @if ($kartuKeluarga->rumah)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Rumah</h3>
                    @php $rumah = $kartuKeluarga->rumah; @endphp
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kode</p>
                            <p class="text-sm font-mono font-medium text-gray-900 mt-1">{{ $rumah->kode_rumah ?? '-' }}</p>
                        </div>
                        @if ($rumah->kategori_rumah)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kategori</p>
                                @php
                                    $kat = match($rumah->kategori_rumah) {
                                        'permanen' => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'Permanen'],
                                        'semi_permanen' => ['bg-amber-50 text-amber-700 border-amber-200', 'Semi Permanen'],
                                        'darurat' => ['bg-red-50 text-red-700 border-red-200', 'Darurat'],
                                        default => ['bg-gray-50 text-gray-600 border-gray-200', '-'],
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 {{ $kat[0] }} border text-xs font-medium rounded-lg mt-1">{{ $kat[1] }}</span>
                            </div>
                        @endif
                        @if ($rumah->kategori_rtlh)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">RTLH</p>
                                @php
                                    $rtlh = match($rumah->kategori_rtlh) {
                                        'layak' => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'Layak Huni'],
                                        'tidak_layak' => ['bg-red-50 text-red-700 border-red-200', 'Tidak Layak Huni'],
                                        default => ['bg-gray-50 text-gray-600 border-gray-200', '-'],
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 {{ $rtlh[0] }} border text-xs font-medium rounded-lg mt-1">{{ $rtlh[1] }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Fasilitas</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-lg {{ $rumah->teraliri_listrik ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-50 text-gray-500 border border-gray-200' }}">
                                    @if ($rumah->teraliri_listrik)
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                    Listrik
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-lg {{ $rumah->punya_mck ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-50 text-gray-500 border border-gray-200' }}">
                                    @if ($rumah->punya_mck)
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                    MCK
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ route('kartu-keluarga.edit', $kartuKeluarga) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-medium rounded-xl hover:from-emerald-600 hover:to-teal-700 shadow-lg shadow-emerald-500/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Data
            </a>
        </div>
    </div>
</div>
