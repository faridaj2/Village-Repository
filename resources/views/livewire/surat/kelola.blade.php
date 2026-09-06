<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Kelola Surat</h2>
                <p class="text-sm text-gray-500">Proses pengajuan surat warga</p>
            </div>
            <a href="{{ route('surat.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Surat
            </a>
        </div>
    </x-slot>

    <x-flash-toast />

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <div class="flex gap-2">
                @foreach (['' => 'Semua', 'diajukan' => 'Diajukan', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $val => $label)
                    <button wire:click="$set('filterStatus', '{{ $val }}')" class="px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $filterStatus === $val ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="p-5 space-y-3">
            @forelse ($surats as $surat)
                <div wire:click="goto({{ $surat->id }})" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100/80 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ match($surat->status) { 'diajukan' => 'bg-blue-100', 'diproses' => 'bg-amber-100', 'selesai' => 'bg-emerald-100', 'ditolak' => 'bg-red-100' } }}">
                            <svg class="w-5 h-5 {{ match($surat->status) { 'diajukan' => 'text-blue-600', 'diproses' => 'text-amber-600', 'selesai' => 'text-emerald-600', 'ditolak' => 'text-red-600' } }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $surat->penduduk->nama }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }} &middot; {{ $surat->created_at->format('d/m/Y') }}</p>
                            @if ($surat->data_tambahan['keperluan'] ?? false)
                                <p class="text-xs text-gray-400 mt-1">{{ $surat->data_tambahan['keperluan'] }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:ml-4">
                        @if ($surat->status === 'diajukan')
                            <button wire:click="proses({{ $surat->id }})" class="px-4 py-2 bg-indigo-500 text-white text-xs font-medium rounded-xl hover:bg-indigo-600 transition-colors">Proses</button>
                            <button wire:click="tolak({{ $surat->id }})" class="px-4 py-2 bg-red-50 text-red-600 text-xs font-medium rounded-xl hover:bg-red-100 transition-colors">Tolak</button>
                        @elseif ($surat->status === 'diproses')
                            <button wire:click="selesai({{ $surat->id }})" class="px-4 py-2 bg-emerald-500 text-white text-xs font-medium rounded-xl hover:bg-emerald-600 transition-colors">Selesai</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-sm text-gray-500">Tidak ada surat</p>
                </div>
            @endforelse
        </div>
        <div class="p-5 border-t border-gray-100">{{ $surats->links() }}</div>
    </div>
</div>
