<div>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Data Surat</h2>
            <p class="text-sm text-gray-500">Riwayat pengajuan surat warga</p>
        </div>
    </x-slot>

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
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Tracking</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemohon</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($surats as $surat)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-5 text-sm font-mono text-gray-700">{{ $surat->nomor_tracking }}</td>
                            <td class="py-3.5 px-5 text-sm font-medium text-gray-900">{{ $surat->penduduk->nama }}</td>
                            <td class="py-3.5 px-5 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</td>
                            <td class="py-3.5 px-5">
                                @php
                                    $s = match($surat->status) { 'diajukan' => 'bg-blue-50 text-blue-700', 'diproses' => 'bg-amber-50 text-amber-700', 'selesai' => 'bg-emerald-50 text-emerald-700', 'ditolak' => 'bg-red-50 text-red-700' };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 {{ $s }} text-xs font-medium rounded-lg">{{ ucfirst($surat->status) }}</span>
                            </td>
                            <td class="py-3.5 px-5 text-sm text-gray-600">{{ $surat->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-16 text-center text-sm text-gray-500">Belum ada data surat</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t border-gray-100">{{ $surats->links() }}</div>
    </div>
</div>
