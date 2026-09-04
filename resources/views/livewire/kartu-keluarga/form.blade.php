<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kartu-keluarga.index') }}" class="p-2 rounded-xl text-gray-500 hover:bg-gray-100"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $kartuKeluarga ? 'Edit KK' : 'Tambah KK' }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. KK</label>
                    <input wire:model="no_kk" type="text" placeholder="16 digit No. KK" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('no_kk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kepala Keluarga</label>
                    <select wire:model="kepala_keluarga_id" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Pilih Kepala Keluarga</option>
                        @foreach ($pendudukList as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->nik }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Rumah</label>
                    <select wire:model="rumah_id" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Pilih Rumah</option>
                        @foreach ($rumahList as $r)
                            <option value="{{ $r->id }}">{{ $r->kode_rumah ?? '-' }} - {{ $r->alamat ?? '-' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                    <input wire:model="alamat" type="text" placeholder="Alamat" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('kartu-keluarga.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>
