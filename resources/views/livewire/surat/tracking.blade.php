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

        <x-card title="Tracking Surat">
            <form wire:submit="cari" class="space-y-4">
                <x-input wire:model="nomorTracking" label="Nomor Tracking" placeholder="Masukkan nomor tracking" />
                <x-button type="submit" class="w-full">Cari</x-button>
            </form>

            @if ($notFound)
                <div class="mt-4 p-4 bg-red-50 rounded-xl">
                    <p class="text-sm text-red-800">Nomor tracking tidak ditemukan.</p>
                </div>
            @endif

            @if ($surat)
                <div class="mt-6 space-y-4">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500">Nomor Tracking</p>
                        <p class="text-sm font-mono font-bold text-gray-900">{{ $surat->nomor_tracking }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500">Jenis Surat</p>
                        <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500">Status</p>
                        @php
                            $statusVariant = match($surat->status) {
                                'diajukan' => 'info',
                                'diproses' => 'warning',
                                'selesai' => 'success',
                                'ditolak' => 'danger',
                                default => 'default',
                            };
                        @endphp
                        <x-badge :variant="$statusVariant">{{ ucfirst($surat->status) }}</x-badge>
                    </div>

                    @if ($surat->catatan_admin)
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-xs text-gray-500">Catatan Admin</p>
                            <p class="text-sm text-gray-900">{{ $surat->catatan_admin }}</p>
                        </div>
                    @endif

                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500">Tanggal Pengajuan</p>
                        <p class="text-sm text-gray-900">{{ $surat->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            @endif
        </x-card>
    </div>
</div>
