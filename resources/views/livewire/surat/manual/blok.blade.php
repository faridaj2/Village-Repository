<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Blok Kode Surat</h2>
                <p class="text-sm text-gray-500">Kelola potongan HTML untuk surat manual</p>
            </div>
            <a href="{{ route('surat.manual.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                ← Kembali
            </a>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Form Blok --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">{{ $blockId ? 'Edit Blok' : 'Buat Blok Baru' }}</h3>
            <form wire:submit="save">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Blok</label>
                        <input wire:model="nama" type="text" placeholder="Contoh: Kop Surat" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode HTML</label>
                        <textarea wire:model.live.debounce.300ms="kode" rows="8" placeholder="<div>...</div>" class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all resize-y"></textarea>
                        @error('kode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                        {{ $blockId ? 'Update Blok' : 'Simpan Blok' }}
                    </button>
                    @if ($blockId)
                        <button type="button" wire:click="$set('blockId', null); $set('nama', ''); $set('kode', '')" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                    @endif
                </div>

                {{-- Preview Blok --}}
                @if ($kode)
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Preview Blok</h3>
                        <div class="border border-gray-200 rounded-xl p-6 bg-white" style="font-family: 'Times New Roman', serif;">
                            {!! $kode !!}
                        </div>
                    </div>
                @endif
            </form>
        </div>

        {{-- Daftar Blok --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Daftar Blok</h3>
            @if ($blocks->count() > 0)
                <div class="space-y-3">
                    @foreach ($blocks as $block)
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $block->nama }}</p>
                                    <pre class="text-xs text-gray-500 mt-1 whitespace-pre-wrap break-words line-clamp-2 overflow-hidden">{{ Str::limit($block->kode, 200) }}</pre>
                                </div>
                                <div class="flex gap-2 flex-shrink-0">
                                    <button wire:click="edit({{ $block->id }})" class="px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">Edit</button>
                                    <button wire:click="delete({{ $block->id }})" wire:confirm="Yakin hapus blok ini?" class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">Hapus</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">Belum ada blok.</p>
            @endif
        </div>
    </div>
</div>
