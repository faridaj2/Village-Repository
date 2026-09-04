<div>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Fasilitas Umum</h2>
            <p class="text-sm text-gray-500">Kelola data fasilitas umum desa</p>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Form -->
    @if ($showForm)
        <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">{{ $editId ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}</h3>
            <form wire:submit="save" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Fasilitas</label>
                        <input wire:model="nama" type="text" placeholder="Contoh: Masjid Al-Ikhlas" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis</label>
                        <select wire:model="jenis" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">Pilih Jenis</option>
                            <option value="Masjid">Masjid</option>
                            <option value="Mushola">Mushola</option>
                            <option value="Gereja">Gereja</option>
                            <option value="Sekolah">Sekolah</option>
                            <option value="Puskesmas">Puskesmas</option>
                            <option value="Posyandu">Posyandu</option>
                            <option value="Balai Desa">Balai Desa</option>
                            <option value="Lapangan">Lapangan</option>
                            <option value="Pasar">Pasar</option>
                            <option value="Kantor Desa">Kantor Desa</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('jenis') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">RW</label>
                        <select wire:model.live="selectedRw" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">Pilih RW</option>
                            @foreach ($rwList as $rw)
                                <option value="{{ $rw->id }}">{{ $rw->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">RT</label>
                        <select wire:model="rt_id" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all" {{ !$selectedRw ? 'disabled' : '' }}>
                            <option value="">{{ $selectedRw ? 'Pilih RT' : 'Pilih RW dulu' }}</option>
                            @foreach ($rtList as $rt)
                                <option value="{{ $rt->id }}">{{ $rt->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Posisi</label>
                        <input wire:model="posisi" type="text" placeholder="-3.4103139, 126.9683868" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('posisi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                        <textarea wire:model="keterangan" rows="2" placeholder="Keterangan tambahan..." class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="cancelForm" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    @endif

    <!-- List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ count($fasilitas) }} fasilitas terdaftar</p>
            @if (!$showForm)
                <button wire:click="openForm" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">RW/RT</th>
                        <th class="text-left py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Koordinat</th>
                        <th class="text-right py-3.5 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($fasilitas as $f)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-5 text-sm font-medium text-gray-900">{{ $f->nama }}</td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg">{{ $f->jenis }}</span>
                            </td>
                            <td class="py-3.5 px-5 text-sm text-gray-600">{{ $f->rt?->rw?->nama ?? '-' }} / {{ $f->rt?->nama ?? '-' }}</td>
                            <td class="py-3.5 px-5 text-sm text-gray-600">
                                @if ($f->posisi)
                                    <span class="font-mono text-xs">{{ $f->posisi }}</span>
                                @else
                                    <span class="text-gray-400">Belum ada</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="edit({{ $f->id }})" class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="delete({{ $f->id }})" wire:confirm="Yakin ingin menghapus?" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-16 text-center text-sm text-gray-500">Belum ada fasilitas umum</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
