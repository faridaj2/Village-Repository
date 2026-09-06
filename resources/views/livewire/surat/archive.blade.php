<div>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Arsip Surat</h2>
            <p class="text-sm text-gray-500">Surat yang sudah selesai diproses</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-xs font-medium text-gray-500">Tahun:</label>
                <select wire:model.live="year" class="px-3 py-2 bg-gray-50 border-0 rounded-xl text-sm">
                    @foreach ($years as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs font-medium text-gray-500">Jenis:</label>
                <select wire:model.live="filterJenis" class="px-3 py-2 bg-gray-50 border-0 rounded-xl text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="domisili">Domisili</option>
                    <option value="tidak_mampu">Tidak Mampu</option>
                    <option value="usaha">Usaha</option>
                    <option value="pengantar_ktp_kk">Pengantar KTP/KK</option>
                    <option value="kelahiran">Kelahiran</option>
                    <option value="kematian">Kematian</option>
                    <option value="pindah">Pindah</option>
                    <option value="belum_menikah">Belum Menikah</option>
                </select>
            </div>
            <span class="text-xs text-gray-400">{{ $totalSurat }} surat</span>
        </div>
    </div>

    @if ($surats->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($surats as $surat)
                <a href="{{ route('surat.detail', $surat->id) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow block">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="text-xs text-gray-400">{{ $surat->created_at->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-sm font-mono font-semibold text-gray-900 mb-1">{{ $surat->nomor_display }}</p>
                    <p class="text-sm text-gray-600 mb-1">{{ $surat->penduduk->nama ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</p>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-medium rounded">Selesai</span>
                        <span class="text-[10px] text-gray-400">{{ $surat->printLogs->count() }}x cetak</span>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-900">Belum ada arsip</p>
            <p class="text-sm text-gray-500 mt-1">Surat yang sudah selesai akan muncul di sini</p>
        </div>
    @endif
</div>
