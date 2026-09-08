<div>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Data Rumah</h2>
            <p class="text-sm text-gray-500">Kelola data rumah penduduk desa</p>
        </div>
    </x-slot>

    <x-flash-toast />

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <!-- Toolbar -->
        <div class="p-5 border-b border-gray-100 space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kode rumah atau alamat..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <div class="flex items-center gap-3">
                    <select wire:model.live="filterKategori" class="px-3 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Kategori</option>
                        <option value="permanen">Permanen</option>
                        <option value="semi_permanen">Semi Permanen</option>
                        <option value="darurat">Darurat</option>
                    </select>
                    <a href="{{ route('rumah.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
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
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">RW/RT</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fasilitas</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Milik</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Penghuni</th>
                        <th class="text-right py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($rumahs as $rumah)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-5">
                                <span class="text-sm font-mono font-medium text-gray-900">{{ $rumah->kode_rumah ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-5 text-sm text-gray-600">
                                @if ($rumah->rt)
                                    <span class="font-medium">{{ $rumah->rt->rw->nama ?? '-' }}</span>
                                    <span class="text-gray-400">/</span>
                                    <span>{{ $rumah->rt->nama }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                @if ($rumah->kategori_rumah)
                                    @php
                                        $kat = match($rumah->kategori_rumah) {
                                            'permanen' => ['bg-emerald-50 text-emerald-700', 'Permanen'],
                                            'semi_permanen' => ['bg-amber-50 text-amber-700', 'Semi Permanen'],
                                            'darurat' => ['bg-red-50 text-red-700', 'Darurat'],
                                            default => ['bg-gray-100 text-gray-600', '-'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 {{ $kat[0] }} text-xs font-medium rounded-lg">{{ $kat[1] }}</span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-[11px] font-medium rounded-md {{ $rumah->teraliri_listrik ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        Listrik
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-[11px] font-medium rounded-md {{ $rumah->punya_mck ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        MCK
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 text-sm text-gray-600">
                                @php
                                    $kkCount = $rumah->kartuKeluargas()->count();
                                    $jiwaFromKk = \App\Models\Penduduk::whereIn('kartu_keluarga_id', $rumah->kartuKeluargas()->pluck('id'))->count();
                                    $jiwaIndividu = $rumah->penduduks()->count();
                                    $totalJiwa = $jiwaFromKk + $jiwaIndividu;
                                @endphp
                                <span>{{ $kkCount }} KK</span>
                                <span class="text-gray-400 mx-1">/</span>
                                <span>{{ $totalJiwa }} jiwa</span>
                            </td>
                                                       <td class="py-3.5 px-5 text-sm">
                                                           @php
                                                               $milikLabels = [];
                                                               foreach ($rumah->kartuKeluargas as $kk) {
                                                                   $anggota = $kk->anggota;
                                                                   foreach ($anggota as $p) {
                                                                       $isKepala = ($p->id === $kk->kepala_keluarga_id);
                                                                       $milikLabels[] = [
                                                                           'name' => $p->nama,
                                                                           'type' => $isKepala ? 'Kepala KK' : 'Anggota KK'
                                                                       ];
                                                                   }
                                                               }
                                                           @endphp
                                                           @forelse ($milikLabels as $milik)
                                                               <div class="flex items-center gap-1.5 mb-1 last:mb-0">
                                                                   <span class="text-gray-900 font-medium">{{ $milik['name'] }}</span>
                                                                   <span class="inline-flex items-center px-1.5 py-0.5 {{ $milik['type'] === 'Kepala KK' ? 'bg-indigo-50 text-indigo-700' : 'bg-emerald-50 text-emerald-700' }} text-[10px] font-semibold rounded">{{ $milik['type'] }}</span>
                                                               </div>
                                                           @empty
                                                               <span class="text-gray-400">-</span>
                                                           @endforelse
                                                       </td>
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('rumah.edit', $rumah) }}" class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button wire:click="delete({{ $rumah->id }})" wire:confirm="Yakin ingin menghapus rumah ini?" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
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
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">Belum ada data</p>
                                    <p class="text-sm text-gray-500 mt-1">Data rumah muncul dari form penduduk</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-gray-100">
            {{ $rumahs->links() }}
        </div>
    </div>
</div>
