<div>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Kelola Wilayah</h2>
            <p class="text-sm text-gray-500">Atur RW, RT & batas wilayah</p>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Pengaturan Desa -->
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Batas Desa</p>
                    <p class="text-xs text-gray-500">Outline wilayah desa secara keseluruhan</p>
                </div>
            </div>
            <button wire:click="openDesaForm" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                {{ $desaBoundary ? 'Edit' : 'Atur' }}
            </button>
        </div>

        @if ($showDesaForm)
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Desa</label>
                        <input wire:model="desaNama" type="text" placeholder="Contoh: Desa Waeleman" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Warna Outline</label>
                        <div class="flex items-center gap-3">
                            <input wire:model="desaWarna" type="color" class="w-12 h-10 rounded-lg cursor-pointer border-0">
                            <input wire:model="desaWarna" type="text" class="flex-1 px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm font-mono focus:ring-2 focus:ring-red-500 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Batas Desa (GeoJSON)</label>
                    <p class="text-xs text-gray-500 mb-2">Paste GeoJSON polygon batas wilayah desa</p>
                    <textarea wire:model="desaBoundary" rows="6" placeholder='{"type":"FeatureCollection","features":[...]}' class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl text-xs font-mono focus:ring-2 focus:ring-red-500 focus:bg-white transition-all"></textarea>
                    @error('desaBoundary') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    @if ($desaBoundary && json_decode($desaBoundary))
                        <p class="text-xs text-emerald-600 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            GeoJSON valid
                        </p>
                    @endif
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="$set('showDesaForm', false)" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                    <button wire:click="saveDesa" class="px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-500 to-orange-500 rounded-xl hover:from-red-600 hover:to-orange-600 shadow-lg shadow-red-500/25 transition-all">Simpan</button>
                </div>
            </div>
        @else
            @if ($desaBoundary)
                <div class="px-5 py-3 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
                    <span class="text-sm text-gray-600">Batas desa sudah diatur</span>
                    @if ($desaNama)
                        <span class="text-sm text-gray-400">— {{ $desaNama }}</span>
                    @endif
                </div>
            @else
                <div class="px-5 py-3">
                    <p class="text-sm text-gray-400">Belum ada batas desa. Klik "Atur" untuk menambahkan.</p>
                </div>
            @endif
        @endif
    </div>

    <!-- RW Form -->
    @if ($showRwForm)
        <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">{{ $editRwId ? 'Edit RW' : 'Tambah RW' }}</h3>
            <form wire:submit="saveRw" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama RW</label>
                        <input wire:model="rwNama" type="text" placeholder="Contoh: RW 01" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('rwNama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Warna di Peta</label>
                        <div class="flex items-center gap-3">
                            <input wire:model="rwWarna" type="color" class="w-12 h-10 rounded-lg cursor-pointer border-0">
                            <input wire:model="rwWarna" type="text" class="flex-1 px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Batas Wilayah (GeoJSON)</label>
                    <p class="text-xs text-gray-500 mb-2">Paste GeoJSON dari geojson.io</p>
                    <textarea wire:model="rwBoundary" rows="6" placeholder='{"type":"FeatureCollection","features":[...]}' class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl text-xs font-mono focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"></textarea>
                    @error('rwBoundary') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    @if ($rwBoundary && json_decode($rwBoundary))
                        <p class="text-xs text-emerald-600 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            GeoJSON valid
                        </p>
                    @endif
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="$set('showRwForm', false)" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    @endif

    <!-- RT Form -->
    @if ($showRtForm)
        <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">{{ $editRtId ? 'Edit RT' : 'Tambah RT' }}</h3>
            <form wire:submit="saveRt" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">RW</label>
                        <select wire:model="rtRwId" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">Pilih RW</option>
                            @foreach ($rwList as $rw)
                                <option value="{{ $rw->id }}">{{ $rw->nama }}</option>
                            @endforeach
                        </select>
                        @error('rtRwId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama RT</label>
                        <input wire:model="rtNama" type="text" placeholder="Contoh: RT 01" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('rtNama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Warna di Peta</label>
                        <div class="flex items-center gap-3">
                            <input wire:model="rtWarna" type="color" class="w-12 h-10 rounded-lg cursor-pointer border-0">
                            <input wire:model="rtWarna" type="text" class="flex-1 px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Batas Wilayah (GeoJSON)</label>
                    <p class="text-xs text-gray-500 mb-2">Paste GeoJSON dari geojson.io</p>
                    <textarea wire:model="rtBoundary" rows="6" placeholder='{"type":"FeatureCollection","features":[...]}' class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl text-xs font-mono focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"></textarea>
                    @error('rtBoundary') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    @if ($rtBoundary && json_decode($rtBoundary))
                        <p class="text-xs text-emerald-600 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            GeoJSON valid
                        </p>
                    @endif
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="$set('showRtForm', false)" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl hover:from-purple-600 hover:to-pink-700 shadow-lg shadow-purple-500/25 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    @endif

    <!-- RW List -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ count($rwList) }} RW terdaftar</p>
            @if (!$showRwForm && !$showRtForm)
                <button wire:click="openRwForm" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah RW
                </button>
            @endif
        </div>

        @forelse ($rwList as $rw)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- RW Header -->
                <div class="flex items-center justify-between p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $rw->warna ?? '#6366f1' }}20">
                            <div class="w-4 h-4 rounded-full" style="background: {{ $rw->warna ?? '#6366f1' }}"></div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold text-gray-900">{{ $rw->nama }}</p>
                                @if ($rw->boundary_geojson)
                                    <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-semibold rounded-md">Ada Batas</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">{{ count($rw->rts) }} RT</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button wire:click="openRtForm({{ $rw->id }})" class="p-2 rounded-lg text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" title="Tambah RT">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                        <a href="{{ route('peta.index', ['filterRw' => $rw->id]) }}" class="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Lihat di Peta">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </a>
                        <button wire:click="editRw({{ $rw->id }})" class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit RW">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="deleteRw({{ $rw->id }})" wire:confirm="Yakin ingin menghapus RW dan semua RT di dalamnya?" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus RW">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                <!-- RT List -->
                @if (count($rw->rts) > 0)
                    <div class="border-t border-gray-100 divide-y divide-gray-50">
                        @foreach ($rw->rts as $rt)
                            <div class="flex items-center justify-between px-5 py-3 pl-16 hover:bg-gray-50/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background: {{ $rt->warna ?? '#8b5cf6' }}20">
                                        <div class="w-2.5 h-2.5 rounded-full" style="background: {{ $rt->warna ?? '#8b5cf6' }}"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $rt->nama }}</p>
                                        @if ($rt->boundary_geojson)
                                            <span class="text-[10px] text-emerald-600">Ada batas</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button wire:click="editRt({{ $rt->id }})" class="p-1.5 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="deleteRt({{ $rt->id }})" wire:confirm="Yakin ingin menghapus RT?" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-5 pb-4 pl-16">
                        <p class="text-xs text-gray-400">Belum ada RT. Klik + untuk menambah RT.</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-900">Belum ada wilayah</p>
                <p class="text-sm text-gray-500 mt-1">Mulai tambah RW lalu tambah RT di dalamnya</p>
            </div>
        @endforelse
    </div>
</div>
