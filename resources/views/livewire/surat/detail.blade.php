<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Detail Surat</h2>
                <p class="text-sm text-gray-500">{{ $surat->nomor_display }}</p>
            </div>
            <a href="{{ route('surat.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <x-flash-toast />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Info & Actions --}}
        <div class="lg:col-span-1 space-y-4">
            {{-- Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Informasi Surat</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Nomor Surat</p>
                        <p class="text-sm font-mono font-semibold text-gray-900">{{ $surat->nomor_display }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Nomor Tracking</p>
                        <p class="text-sm font-mono text-gray-700">{{ $surat->nomor_tracking }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-700',
                                'diajukan' => 'bg-blue-50 text-blue-700',
                                'diproses' => 'bg-amber-50 text-amber-700',
                                'selesai' => 'bg-emerald-50 text-emerald-700',
                                'ditolak' => 'bg-red-50 text-red-700',
                                'dibatalkan' => 'bg-gray-100 text-gray-500 line-through',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 {{ $statusColors[$surat->status] ?? '' }} text-xs font-medium rounded-lg">
                            {{ ucfirst($surat->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Pemohon</p>
                        <p class="text-sm font-medium text-gray-900">{{ $surat->penduduk->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $surat->penduduk->nik ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Jenis Surat</p>
                        <p class="text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Template</p>
                        <p class="text-sm text-gray-700">{{ $surat->template?->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Dibuat</p>
                        <p class="text-sm text-gray-700">{{ $surat->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Oleh</p>
                        <p class="text-sm text-gray-700">{{ $surat->creator?->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Aksi</h3>
                <div class="space-y-2">
                    @if ($surat->status === 'draft')
                        <button wire:click="setProses" class="w-full px-4 py-2.5 bg-amber-500 text-white text-sm font-medium rounded-xl hover:bg-amber-600 transition-colors">
                            Proses Surat
                        </button>
                    @endif

                    @if (in_array($surat->status, ['draft', 'diproses']))
                        <button wire:click="setSelesai" class="w-full px-4 py-2.5 bg-emerald-500 text-white text-sm font-medium rounded-xl hover:bg-emerald-600 transition-colors">
                            ✅ Finalisasi
                        </button>
                    @endif

                    @if ($surat->status === 'selesai')
                        <button wire:click="downloadPdf" class="w-full px-4 py-2.5 bg-indigo-500 text-white text-sm font-medium rounded-xl hover:bg-indigo-600 transition-colors">
                            📥 Download PDF
                        </button>
                        <button wire:click="logPrint" class="w-full px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                            🖨️ Cetak (Log)
                        </button>
                    @endif

                    @if (!in_array($surat->status, ['dibatalkan', 'selesai']))
                        <button wire:click="setDraft" class="w-full px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                            ↩️ Kembali ke Draft
                        </button>
                        <button wire:click="batalkan" wire:confirm="Yakin ingin membatalkan surat ini?" class="w-full px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-xl hover:bg-red-100 transition-colors">
                            ❌ Batalkan
                        </button>
                    @endif

                    <button wire:click="delete" wire:confirm="Yakin ingin menghapus surat ini? Print log dan file PDF terkait juga akan dihapus." class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                        🗑️ Hapus Surat
                    </button>
                </div>
            </div>

            {{-- Print Log --}}
            @if ($surat->printLogs->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Riwayat Cetak</h3>
                    <div class="space-y-2">
                        @foreach ($surat->printLogs->take(10) as $log)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600">{{ $log->user?->name ?? '-' }}</span>
                                <span class="text-gray-400">{{ $log->printed_at->format('d/m/Y H:i') }}</span>
                                <span class="px-2 py-0.5 {{ $log->format === 'pdf' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-600' }} rounded text-[10px] font-medium">{{ strtoupper($log->format) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right: Preview --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Preview Surat</h3>
                    @if ($surat->draft_html)
                    <a href="{{ route('surat.pdf', $surat) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak PDF
                    </a>
                    @endif
                </div>
                <div id="printArea" class="border border-gray-200 rounded-xl p-6 bg-white min-h-[500px] prose prose-sm max-w-none" style="font-family: 'Times New Roman', serif;">
                    {!! $surat->draft_html ?? '<p class="text-gray-400 italic">Belum ada preview. Klik Finalisasi untuk generate.</p>' !!}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }
            body * {
                visibility: hidden;
            }
            #printArea, #printArea * {
                visibility: visible;
            }
            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 210mm;
                border: none;
                margin: 0;
                font-family: 'Times New Roman', serif;
                font-size: 12pt;
                line-height: 1.5;
                color: #000;
                background: white;
            }
            #printArea table {
                page-break-inside: avoid;
            }
            #printArea p {
                orphans: 3;
                widows: 3;
            }
        }
    </style>
    @endpush
</div>
