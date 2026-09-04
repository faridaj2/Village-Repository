<div>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Data Penduduk</h2>
            <p class="text-sm text-gray-500">Kelola data kependudukan desa</p>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <!-- Toolbar -->
        <div class="p-5 border-b border-gray-100 space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau NIK..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <div class="flex items-center gap-3">
                    <select wire:model.live="filterStatus" class="px-3 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="pindah">Pindah</option>
                        <option value="meninggal">Meninggal</option>
                    </select>
                    <label class="flex items-center gap-2 px-3 py-2.5 bg-gray-50 rounded-xl cursor-pointer">
                        <input type="checkbox" wire:model.live="filterTanpaKk" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-600">Tanpa KK</span>
                    </label>
                    <a href="{{ route('penduduk.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah
                    </a>
                </div>
            </div>

            <!-- RT/RW Filter -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5">
                    <span class="text-xs font-semibold text-gray-500 uppercase">RW</span>
                    <button wire:click="$set('filterRw', '')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $filterRw === '' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Semua</button>
                    @foreach ($rwList as $rw)
                        <button wire:click="$set('filterRw', '{{ $rw->id }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $filterRw == $rw->id ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $rw->nama }}</button>
                    @endforeach
                </div>

                @if ($filterRw && count($rtList) > 0)
                    <div class="w-px h-6 bg-gray-200"></div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-semibold text-gray-500 uppercase">RT</span>
                        <button wire:click="$set('filterRt', '')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $filterRt === '' ? 'bg-purple-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Semua</button>
                        @foreach ($rtList as $rt)
                            <button wire:click="$set('filterRt', '{{ $rt->id }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $filterRt == $rt->id ? 'bg-purple-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $rt->nama }}</button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIK</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelamin</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">RW/RT</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">KK</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-right py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($penduduks as $penduduk)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-5 text-sm font-mono text-gray-700">{{ $penduduk->nik }}</td>
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <span class="text-xs font-semibold text-gray-600">{{ substr($penduduk->nama, 0, 1) }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $penduduk->nama }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 text-sm text-gray-600">{{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="py-3.5 px-5 text-sm text-gray-600">
                                @if ($penduduk->kartuKeluarga?->rumah?->rt)
                                    <span class="font-medium">{{ $penduduk->kartuKeluarga->rumah->rt->rw->nama ?? '-' }}</span>
                                    <span class="text-gray-400">/</span>
                                    <span>{{ $penduduk->kartuKeluarga->rumah->rt->nama ?? '-' }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                @if ($penduduk->kartuKeluarga)
                                    <span class="text-sm text-gray-600">{{ $penduduk->kartuKeluarga->no_kk }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg">Tanpa KK</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                @php
                                    $s = match($penduduk->status) { 'aktif' => ['bg-emerald-50 text-emerald-700', 'Aktif'], 'pindah' => ['bg-amber-50 text-amber-700', 'Pindah'], 'meninggal' => ['bg-gray-100 text-gray-600', 'Meninggal'] };
                                @endphp
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-flex items-center px-2.5 py-1 {{ $s[0] }} text-xs font-medium rounded-lg">{{ $s[1] }}</span>
                                    @if ($penduduk->jenis_penduduk === 'pendatang')
                                        <span class="inline-flex items-center px-2 py-0.5 bg-violet-50 text-violet-700 text-[10px] font-semibold rounded-md">Pendatang</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('penduduk.show', $penduduk) }}" class="p-2 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('penduduk.edit', $penduduk) }}" class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button wire:click="delete({{ $penduduk->id }})" wire:confirm="Yakin ingin menghapus?" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">Belum ada data</p>
                                    <p class="text-sm text-gray-500 mt-1">Mulai tambah data penduduk</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-gray-100">
            {{ $penduduks->links() }}
        </div>
    </div>
</div>
