<div class="min-h-screen flex items-center justify-center bg-[#F2F2F7]">
    <div class="w-full max-w-md px-4">
        <div class="mb-6 text-center">
            <a href="/" class="inline-flex items-center space-x-2">
                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <span class="text-2xl font-semibold text-gray-900">SIDESA</span>
            </a>
        </div>

        <x-card title="Ajukan Surat">
            @if ($submitted)
                <div class="text-center py-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pengajuan Berhasil!</h3>
                    <p class="text-sm text-gray-600 mb-4">Nomor tracking Anda:</p>
                    <p class="text-xl font-mono font-bold text-blue-600 bg-blue-50 py-3 px-4 rounded-xl">{{ $nomorTracking }}</p>
                    <p class="text-xs text-gray-500 mt-4">Simpan nomor ini untuk melacak status surat Anda.</p>
                    <a href="{{ route('surat.tracking') }}" class="inline-block mt-4 text-sm text-blue-500 hover:text-blue-700">Cek Status Surat</a>
                </div>
            @else
                <form wire:submit="submit" class="space-y-4">
                    <x-input wire:model.blur="nik" label="NIK" placeholder="Masukkan 16 digit NIK" :error="$errors->first('nik')" />

                    @if ($penduduk)
                        <div class="p-3 bg-green-50 rounded-xl">
                            <p class="text-sm text-green-800">Ditemukan: {{ $penduduk->nama }}</p>
                        </div>
                    @endif

                    <x-select wire:model="jenis_surat" label="Jenis Surat" :error="$errors->first('jenis_surat')">
                        <option value="">Pilih Jenis Surat</option>
                        <option value="domisili">Surat Domisili</option>
                        <option value="tidak_mampu">Surat Tidak Mampu</option>
                        <option value="usaha">Surat Usaha</option>
                        <option value="pengantar_ktp_kk">Pengantar KTP/KK</option>
                    </x-select>

                    <x-textarea wire:model="keperluan" label="Keperluan (opsional)" placeholder="Jelaskan keperluan Anda" rows="3" />

                    <x-button type="submit" class="w-full">Ajukan Surat</x-button>
                </form>
            @endif
        </x-card>
    </div>
</div>
