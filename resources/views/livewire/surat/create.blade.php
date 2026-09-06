<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Buat Surat Baru</h2>
                <p class="text-sm text-gray-500">Pilih template, penduduk, lalu finalisasi</p>
            </div>
            <a href="{{ route('surat.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    {{-- Step Indicator --}}
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-center gap-0">
            @foreach (['Pilih Template', 'Pilih Penduduk', 'Review & Finalisasi'] as $i => $label)
                @php $stepNum = $i + 1; @endphp
                <div class="flex items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-colors {{ $step >= $stepNum ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                            {{ $stepNum }}
                        </div>
                        <span class="text-sm font-medium {{ $step >= $stepNum ? 'text-gray-900' : 'text-gray-400' }}">{{ $label }}</span>
                    </div>
                    @if (!$loop->last)
                        <div class="w-12 sm:w-20 h-0.5 mx-3 {{ $step > $stepNum ? 'bg-indigo-500' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Step 1: Select Template --}}
    @if ($step === 1)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Pilih Template Surat</h3>

            @error('template') <p class="text-xs text-red-500 mb-3">{{ $message }}</p> @enderror

            @if ($templates->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($templates as $template)
                        <button type="button" wire:click="selectTemplate({{ $template->id }})"
                            class="text-left p-4 rounded-xl border-2 transition-all {{ $selectedTemplateId === $template->id ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300 bg-white' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $template->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $template->jenis_surat)) }}</p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">Belum ada template aktif. <a href="{{ route('surat.template.create') }}" class="text-indigo-600 hover:underline">Buat template dulu</a>.</p>
            @endif

            <div class="flex justify-end mt-6">
                <button wire:click="nextStep" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                    Selanjutnya →
                </button>
            </div>
        </div>
    @endif

    {{-- Step 2: Select Penduduk --}}
    @if ($step === 2)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Pilih Penduduk</h3>

            @error('penduduk') <p class="text-xs text-red-500 mb-3">{{ $message }}</p> @enderror

            <div class="mb-4">
                <input wire:model.live.debounce.300ms="nik" type="text" placeholder="Cari berdasarkan NIK atau nama..." class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
            </div>

            @if ($selectedPendudukId && $penduduk)
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl mb-4">
                    <p class="text-sm font-semibold text-emerald-800">✓ Dipilih: {{ $penduduk->nama }} ({{ $penduduk->nik }})</p>
                </div>
            @endif

            @if (strlen($nik) >= 2 && $penduduks->count() > 0)
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @foreach ($penduduks as $p)
                        <button type="button" wire:click="selectPenduduk({{ $p->id }})"
                            class="w-full text-left p-3 rounded-xl border transition-all {{ $selectedPendudukId === $p->id ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:bg-gray-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-bold text-gray-500">{{ substr($p->nama, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $p->nama }}</p>
                                    <p class="text-xs text-gray-500">NIK: {{ $p->nik }}</p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-between mt-6">
                <button wire:click="prevStep" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                    ← Kembali
                </button>
                <button wire:click="nextStep" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                    Selanjutnya →
                </button>
            </div>
        </div>
    @endif

    {{-- Step 3: Review & Finalize --}}
    @if ($step === 3)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Info & Controls --}}
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Info Surat</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nomor Surat</label>
                            <input wire:model="nomorManual" type="text" placeholder="Kosongkan untuk auto-generate" class="w-full px-3 py-2 bg-gray-50 border-0 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <p class="text-[11px] text-gray-400 mt-1">Kosongkan = nomor otomatis</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Surat</label>
                            <input wire:model="tanggalSurat" type="date" class="w-full px-3 py-2 bg-gray-50 border-0 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Ukuran Kertas</label>
                            <select wire:model="paperSize" wire:loading.attr="disabled" class="w-full px-3 py-2 bg-gray-50 border-0 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                                <option value="a4">A4 (210×297mm)</option>
                                <option value="f4">F4 (215×330mm)</option>
                            </select>
                            <p class="text-[11px] text-gray-400 mt-1">Ukuran dipakai untuk PDF surat ini</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex flex-col gap-2">
                        <button wire:click="loadPreview" class="w-full px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                            🔄 Refresh Preview
                        </button>
                        <button wire:click="saveDraft" class="w-full px-4 py-2.5 bg-amber-500 text-white text-sm font-medium rounded-xl hover:bg-amber-600 transition-colors">
                            💾 Simpan Draft
                        </button>
                        <button wire:click="finalize" class="w-full px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                            ✅ Final & Generate
                        </button>
                    </div>
                </div>

                <button wire:click="prevStep" class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                    ← Kembali
                </button>
            </div>

            {{-- Right: Preview --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Preview Surat</h3>
                        <div class="flex items-center gap-3">
                            <span class="text-[11px] text-gray-400">Ukuran: <span>{{ $paperSize === 'f4' ? 'F4 (215×330mm)' : 'A4 (210×297mm)' }}</span></span>
                            @if ($draftHtml)
                            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Cetak
                            </button>
                            @endif
                        </div>
                    </div>

                    <div id="printArea" class="border border-gray-200 rounded-xl p-6 bg-white min-h-[500px] prose prose-sm max-w-none" style="font-family: 'Times New Roman', serif;">
                        {!! $draftHtml ?? '<p class="text-gray-400 italic">Belum ada preview. Klik Finalisasi untuk generate.</p>' !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

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
