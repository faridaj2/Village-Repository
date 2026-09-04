<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Struktur Pemerintahan</h2>
                <p class="text-sm text-gray-500">Kelola struktur organisasi pemerintahan desa</p>
            </div>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Form -->
    @if ($showForm)
        <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">{{ $editId ? 'Edit Struktur' : 'Tambah Jabatan' }}</h3>
            <form wire:submit="save" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jabatan</label>
                        <input wire:model="jabatan" type="text" placeholder="Contoh: Kepala Desa" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('jabatan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama</label>
                        <input wire:model="nama" type="text" placeholder="Nama pejabat" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Atasan Langsung</label>
                        <select wire:model="parentId" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">-- Tidak ada (Pimpinan Utama) --</option>
                            @foreach ($strukturList as $item)
                                @if ($item->id !== $editId)
                                    <option value="{{ $item->id }}">{{ $item->jabatan }} - {{ $item->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika sejajar dengan pimpinan utama</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Urutan</label>
                        <input wire:model="urutan" type="number" min="0" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <p class="text-xs text-gray-400 mt-1">Urutan horizontal dalam satu level</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input wire:model="foto" type="text" placeholder="URL foto" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    @endif

    <!-- Bagan Struktur -->
    @if (count($strukturList) > 0)
        @php
            $roots = $strukturList->whereNull('parent_id')->sortBy('urutan');
        @endphp
        @if ($roots->count() > 0)
            <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-6">Bagan Struktur</h3>

                <div class="flex flex-col items-center min-w-fit">
                    <!-- Level 0: Root nodes -->
                    <div class="flex flex-wrap justify-center gap-6">
                        @foreach ($roots as $item)
                            @include('livewire.struktur-pemerintahan._node', ['item' => $item, 'depth' => 0])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Daftar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ count($strukturList) }} jabatan terdaftar</p>
            @if (!$showForm)
                <button wire:click="openForm" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
            @endif
        </div>

        <div class="divide-y divide-gray-100">
            @forelse ($strukturList as $item)
                <div class="flex items-center justify-between p-5 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if ($item->foto)
                                <img src="{{ $item->foto }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-lg font-bold text-indigo-600">{{ substr($item->nama, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $item->jabatan }}</p>
                            <p class="text-sm text-gray-600">{{ $item->nama }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="text-xs text-gray-400 mr-2">#{{ $item->urutan }}</span>
                        <button wire:click="edit({{ $item->id }})" class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus?" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Belum ada data</p>
                    <p class="text-sm text-gray-500 mt-1">Mulai tambah jabatan pemerintahan desa</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
