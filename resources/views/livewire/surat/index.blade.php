<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Data Surat</h2>
                <p class="text-sm text-gray-500">Riwayat pengajuan surat warga</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('surat.archive') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Arsip
                </a>
                <a href="{{ route('surat.template.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Template
                </a>
                <a href="{{ route('surat.manual.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    Surat Manual
                </a>
                <a href="{{ route('surat.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Surat
                </a>
            </div>
        </div>
    </x-slot>

    <x-flash-toast />

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari tracking atau nama..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <div class="flex items-center gap-3">
                    <select wire:model.live="filterStatus" class="px-3 py-2.5 bg-gray-50 border-0 rounded-xl text-sm">
                        <option value="">Semua Status</option>
                        <option value="diajukan">Diajukan</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <select wire:model.live="filterJenis" class="px-3 py-2.5 bg-gray-50 border-0 rounded-xl text-sm">
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
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Surat</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemohon</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($surats as $surat)
                        <tr wire:click="goto({{ $surat->id }})" class="hover:bg-gray-50/50 transition-colors cursor-pointer">
                            <td class="py-3.5 px-5 text-sm font-mono font-semibold text-gray-900">{{ $surat->nomor_surat ?? $surat->nomor_tracking }}</td>
                            <td class="py-3.5 px-5 text-sm font-medium text-gray-900">{{ $surat->penduduk->nama }}</td>
                            <td class="py-3.5 px-5 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</td>
                            <td class="py-3.5 px-5">
                                @php
                                    $s = match($surat->status) { 'draft' => 'bg-gray-100 text-gray-700', 'diajukan' => 'bg-blue-50 text-blue-700', 'diproses' => 'bg-amber-50 text-amber-700', 'selesai' => 'bg-emerald-50 text-emerald-700', 'ditolak' => 'bg-red-50 text-red-700', 'dibatalkan' => 'bg-gray-100 text-gray-500 line-through', default => 'bg-gray-100 text-gray-700' };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 {{ $s }} text-xs font-medium rounded-lg">{{ ucfirst($surat->status) }}</span>
                            </td>
                            <td class="py-3.5 px-5 text-sm text-gray-600">{{ $surat->created_at->format('d/m/Y') }}</td>
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-1.5" @click.stop>
                                    <button wire:click="goto({{ $surat->id }})" title="Lihat" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <select wire:change="ubahStatus({{ $surat->id }}, $event.target.value)" title="Ubah status" class="px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 focus:ring-2 focus:ring-indigo-500">
                                        @foreach (['draft', 'diajukan', 'diproses', 'selesai', 'ditolak', 'dibatalkan'] as $status)
                                            <option value="{{ $status }}" @selected($surat->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    <button wire:click="delete({{ $surat->id }})" wire:confirm="Yakin ingin menghapus surat ini?" title="Hapus" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-16 text-center text-sm text-gray-500">Belum ada data surat</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t border-gray-100">{{ $surats->links() }}</div>
    </div>
</div>
