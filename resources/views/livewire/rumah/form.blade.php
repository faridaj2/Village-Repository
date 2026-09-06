<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('rumah.index') }}" class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $rumah ? 'Edit Rumah' : 'Tambah Rumah' }}</h2>
                <p class="text-sm text-gray-500">{{ $rumah ? 'Perbarui data rumah' : 'Input data rumah baru' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form wire:submit="save" class="space-y-6">
            <!-- Info Dasar -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    Informasi Rumah
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Rumah</label>
                        <input wire:model="kode_rumah" type="text" placeholder="Contoh: R001" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('kode_rumah') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                        <input wire:model="alamat" type="text" placeholder="Alamat rumah" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">RW</label>
                        <select wire:model.live="rwId" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">Pilih RW</option>
                            @foreach ($rwList as $rw)
                                <option value="{{ $rw->id }}">{{ $rw->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">RT</label>
                        <select wire:model="rtId" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all" {{ !$rwId ? 'disabled' : '' }}>
                            <option value="">{{ $rwId ? 'Pilih RT' : 'Pilih RW dulu' }}</option>
                            @foreach ($rtListForm as $rt)
                                <option value="{{ $rt->id }}">{{ $rt->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Posisi <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input wire:model="posisi" type="text" placeholder="-6.905, 107.605" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <!-- Kategori & Fasilitas -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <div class="w-6 h-6 bg-amber-100 rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    Kategori & Fasilitas
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori Rumah</label>
                        <select wire:model="kategori_rumah" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">Pilih Kategori</option>
                            <option value="permanen">Permanen</option>
                            <option value="semi_permanen">Semi Permanen</option>
                            <option value="darurat">Darurat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori RTLH</label>
                        <select wire:model="kategori_rtlh" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">Pilih Kategori</option>
                            <option value="layak">Layak Huni</option>
                            <option value="tidak_layak">Tidak Layak Huni</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                                <input type="checkbox" wire:model="teraliri_listrik" class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">Teraliri Listrik</span>
                                    <p class="text-xs text-gray-500">Rumah memiliki aliran listrik</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                                <input type="checkbox" wire:model="punya_mck" class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">Punya MCK</span>
                                    <p class="text-xs text-gray-500">Rumah memiliki mandi, cuci, kakus</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('rumah.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>
