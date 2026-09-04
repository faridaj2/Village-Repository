<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('penduduk.index') }}" class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Detail Penduduk</h2>
                <p class="text-sm text-gray-500">{{ $penduduk->nama }}</p>
            </div>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">{{ substr($penduduk->nama, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">{{ $penduduk->nama }}</h3>
                            <p class="text-indigo-100 text-sm font-mono">{{ $penduduk->nik }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach ([
                        ['label' => 'Tempat, Tgl Lahir', 'value' => $penduduk->tempat_lahir . ', ' . ($penduduk->tanggal_lahir?->format('d/m/Y') ?? '-')],
                        ['label' => 'Jenis Kelamin', 'value' => $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'],
                        ['label' => 'Agama', 'value' => $penduduk->agama ?? '-'],
                        ['label' => 'Pendidikan', 'value' => $penduduk->pendidikan_terakhir ?? '-'],
                        ['label' => 'Pekerjaan', 'value' => $penduduk->pekerjaan ?? '-'],
                        ['label' => 'Status Kawin', 'value' => ucfirst(str_replace('_', ' ', $penduduk->status_kawin ?? '-'))],
                    ] as $item)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $item['label'] }}</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $item['value'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Mutasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Riwayat Mutasi</h3>
                @forelse ($penduduk->mutasis as $mutasi)
                    <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($mutasi->jenis_mutasi) }}</p>
                            <p class="text-xs text-gray-500">{{ $mutasi->tanggal_mutasi->format('d/m/Y') }}</p>
                        </div>
                        <p class="text-sm text-gray-600">{{ $mutasi->keterangan ?? '-' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-6">Belum ada riwayat mutasi</p>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Status</h3>
                @php
                    $s = match($penduduk->status) { 'aktif' => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'Aktif'], 'pindah' => ['bg-amber-50 text-amber-700 border-amber-200', 'Pindah'], 'meninggal' => ['bg-gray-100 text-gray-600 border-gray-200', 'Meninggal'] };
                @endphp
                <div class="inline-flex items-center px-3 py-1.5 {{ $s[0] }} border text-sm font-medium rounded-xl">{{ $s[1] }}</div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Jenis Penduduk</p>
                    @if ($penduduk->jenis_penduduk === 'pendatang')
                        <span class="inline-flex items-center px-3 py-1.5 bg-violet-50 text-violet-700 border border-violet-200 text-sm font-medium rounded-xl">Pendatang</span>
                        <p class="text-xs text-gray-500 mt-1">Belum urus pindah KK/KTP</p>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-sm font-medium rounded-xl">Penduduk</span>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Kartu Keluarga</p>
                    @if ($penduduk->kartuKeluarga)
                        <p class="text-sm font-mono text-gray-900">{{ $penduduk->kartuKeluarga->no_kk }}</p>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg">Tanpa KK</span>
                            @can('update', $penduduk)
                                <button wire:click="$set('showAssignKk', true)" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Assign</button>
                            @endcan
                        </div>
                    @endif
                </div>

                @if ($showAssignKk)
                    <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                        <select wire:model="selectedKkId" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm mb-3">
                            <option value="">Pilih KK</option>
                            @foreach ($kkList as $kk)
                                <option value="{{ $kk->id }}">{{ $kk->no_kk }}</option>
                            @endforeach
                        </select>
                        <div class="flex gap-2">
                            <button wire:click="assignKk" class="px-3 py-1.5 bg-indigo-500 text-white text-xs font-medium rounded-lg hover:bg-indigo-600">Simpan</button>
                            <button wire:click="$set('showAssignKk', false)" class="px-3 py-1.5 bg-gray-200 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-300">Batal</button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Rumah Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Rumah</h3>
                @if ($penduduk->rumah)
                    @php $rumah = $penduduk->rumah; @endphp
                    <div class="space-y-3">
                        @if ($rumah->rt)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Lokasi</p>
                                <p class="text-sm text-gray-900 mt-1">RT {{ $rumah->rt->nama }} / RW {{ $rumah->rt->rw->nama ?? '-' }}</p>
                            </div>
                        @endif
                        @if ($rumah->kategori_rumah)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kategori</p>
                                @php
                                    $katLabel = match($rumah->kategori_rumah) {
                                        'permanen' => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'Permanen'],
                                        'semi_permanen' => ['bg-amber-50 text-amber-700 border-amber-200', 'Semi Permanen'],
                                        'darurat' => ['bg-red-50 text-red-700 border-red-200', 'Darurat'],
                                        default => ['bg-gray-50 text-gray-600 border-gray-200', '-'],
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 {{ $katLabel[0] }} border text-xs font-medium rounded-lg mt-1">{{ $katLabel[1] }}</span>
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
                @else
                    <p class="text-sm text-gray-500">Tidak memiliki rumah tercatat</p>
                @endif
            </div>

            <a href="{{ route('penduduk.edit', $penduduk) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Data
            </a>
        </div>
    </div>
</div>
