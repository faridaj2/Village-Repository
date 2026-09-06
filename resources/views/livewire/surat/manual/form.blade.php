<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $manualId ? 'Edit Surat Manual' : 'Buat Surat Manual' }}</h2>
                <p class="text-sm text-gray-500">Editor bebas + blok kode + variabel</p>
            </div>
            <a href="{{ route('surat.manual.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <form @submit.prevent="saveManual()" x-data="suratManualPreview()">
        {{-- Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Surat</label>
                    <input wire:model="nama" type="text" placeholder="Contoh: Surat Undangan" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Ukuran Kertas</label>
                    <select wire:model="paperSize" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="a4">A4</option>
                        <option value="f4">F4</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-1.5 text-sm font-medium text-white rounded-lg shadow-sm transition-opacity hover:opacity-90" style="background-color: #4f46e5;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan Surat
                    </button>
                </div>
            </div>

            {{-- Search Penduduk --}}
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Data Penduduk (untuk variabel)</label>
                @if ($selectedPenduduk)
                    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-xl p-3 mb-2">
                        <p class="text-sm font-medium text-emerald-800 flex-1">{{ $selectedPenduduk->nama }} ({{ $selectedPenduduk->nik }})</p>
                        <button type="button" wire:click="clearSelectedPenduduk" class="px-2 py-1 text-xs text-red-600 bg-red-50 rounded hover:bg-red-100">Hapus</button>
                    </div>
                @else
                    <input wire:model.live.debounce.300ms="pendudukSearch" type="text" placeholder="Ketik NIK atau nama..." class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @if (strlen($pendudukSearch) >= 2 && $penduduks->count() > 0)
                        <div class="mt-2 space-y-1 max-h-40 overflow-y-auto">
                            @foreach ($penduduks as $p)
                                <button type="button" wire:click="selectPenduduk({{ $p->id }})" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg">
                                    <span class="text-sm font-medium text-gray-900">{{ $p->nama }}</span>
                                    <span class="text-xs text-gray-500"> - {{ $p->nik }}</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- Editor / Preview gabungan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 relative w-full max-w-full overflow-x-hidden">
            {{-- Header dengan toggle --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 relative z-10 flex-shrink-0">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">
                    <span x-show="!showPreview">Editor</span>
                    <span x-show="showPreview" x-cloak>Preview</span>
                </h3>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="showPreview" @change="if(showPreview) { syncToServer(true); } else { syncToServer(false); }" class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500">
                        <span class="text-xs font-medium text-gray-700">Tampilkan Preview</span>
                    </label>

                    {{-- Tombol Cetak selalu tampil --}}
                    <button type="button"
                            onclick="window.print()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        🖨 Cetak
                    </button>
                </div>
            </div>

            {{-- Body: Editor atau Preview --}}
            <div class="p-6">
                {{-- Preview --}}
                <div x-show="showPreview" x-cloak>
                    <div class="bg-gray-100 rounded-xl p-4 max-h-[80vh] overflow-auto flex justify-center min-w-0">
                        <div id="printArea" x-ref="preview" @dblclick="startInlineEdit($event)" class="bg-white shadow-lg mx-auto cursor-text" style="
                            width: {{ $paperSize === 'f4' ? '215mm' : '210mm' }};
                            max-width: none;
                            aspect-ratio: {{ $paperSize === 'f4' ? '215 / 330' : '210 / 297' }};
                            padding: {{ $paperSize === 'f4' ? '18mm' : '20mm' }};
                            box-sizing: border-box;
                            color: #000;
                        ">
                            @if ($renderedHtml = $this->renderManualHtml())
                                {!! $renderedHtml !!}
                            @else
                                <p class="text-gray-400 italic">Belum ada konten. Mulai tulis HTML di editor.</p>
                            @endif
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center">Ukuran kertas: {{ $paperSize === 'f4' ? 'F4 (215×330mm)' : 'A4 (210×297mm)' }}</p>
                </div>

                {{-- Editor --}}
                <div x-show="!showPreview">
                    <textarea x-model="htmlLocal" rows="20" placeholder="Tulis HTML surat di sini..." class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all resize-y" style="min-height: 400px;"></textarea>
                </div>
            </div>
        </div>

        {{-- Blok Kode & Variabel --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Blok Kode</h3>
                @if ($blocks->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach ($blocks as $block)
                                                            <button type="button" @click="insertBlock(@js($block->kode))" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 rounded-lg transition-colors">{{ $block->nama }}</button>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Belum ada blok. <a href="{{ route('surat.manual.blok') }}" class="text-indigo-600 hover:underline">Buat blok</a>.</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Variabel</h3>
                <div class="space-y-2">
                    @foreach($varGroups as $group)
                    <div class="bg-{{ $group['color'] }}-50 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-{{ $group['color'] }}-800 mb-1.5 uppercase tracking-wider">{{ $group['title'] }}</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($group['vars'] as $v)
                            <button type="button" @click="insertVariable(@js($v['var']))" class="inline-flex items-center gap-1 px-2 py-1 bg-{{ $group['color'] }}-100 hover:bg-{{ $group['color'] }}-200 rounded-lg text-[10px] font-mono text-{{ $group['color'] }}-700 transition-colors cursor-pointer" title="{{ $v['example'] }}">
                                {{ '{'.'{'.$v['var'].'}'.'}' }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        function suratManualPreview() {
            return {
                showPreview: false,
                htmlLocal: '',

                init() {
                    this.htmlLocal = this.$wire.html || '';
                },

                insertBlock(code) {
                    this.htmlLocal += code;
                },

                insertVariable(varName) {
                    this.htmlLocal += '{' + '{' + varName + '}' + '}';
                },

                syncToServer(refresh = false) {
                    this.$wire.set('html', this.htmlLocal, !refresh);
                },

                async saveManual() {
                    this.syncToServer();
                    await this.$wire.save();
                },

                startInlineEdit(e) {
                    const selection = window.getSelection();
                    const node = selection.anchorNode;
                    let el = (node && node.nodeType === Node.TEXT_NODE) ? node.parentElement : e.target;

                    if (!el || el === this.$refs.preview) return;
                    // Jangan edit seluruh area, hanya elemen yang berisi teks yang diklik
                    if (el.children.length > 0) {
                        // Cari elemen terkecil di bawah kursor yang berisi teks
                        const range = selection.getRangeAt(0);
                        const textNode = range.startContainer;
                        if (textNode && textNode.parentElement && textNode.parentElement !== this.$refs.preview) {
                            el = textNode.parentElement;
                        } else {
                            return;
                        }
                    }

                    el.setAttribute('contenteditable', 'true');
                    el.focus();
                    try {
                        const range = document.createRange();
                        range.selectNodeContents(el);
                        const sel = window.getSelection();
                        sel.removeAllRanges();
                        sel.addRange(range);
                    } catch (err) {}

                    const finish = () => {
                        // Bersihkan <br> kosong yang sering disisipkan browser saat blur
                        el.innerHTML = el.innerHTML.replace(/<br\s*\/?>\s*$/, '');

                        el.removeAttribute('contenteditable');
                        let newHtml = this.$refs.preview.innerHTML;
                        // Bersihkan semua komentar HTML yang ikut tersalin
                        newHtml = newHtml.replace(/<!--[\s\S]*?-->/g, '');
                        newHtml = newHtml.trim();
                        this.htmlLocal = newHtml;
                        this.$wire.set('html', newHtml);
                    };

                    el.addEventListener('blur', finish, { once: true });
                    el.addEventListener('keydown', (ev) => {
                        if (ev.key === 'Escape') {
                            el.blur();
                        }
                    });
                }
            }
        }
    </script>
    <style>
        @media print {
            @page {
                size: {{ $paperSize === 'f4' ? 'F4' : 'A4' }} portrait;
                margin: 0;
            }
            body * {
                visibility: hidden;
            }
            #printArea, #printArea * {
                visibility: visible;
            }
            #printArea {
                position: fixed;
                left: 0;
                top: 0;
                width: {{ $paperSize === 'f4' ? '215mm' : '210mm' }};
                min-height: {{ $paperSize === 'f4' ? '330mm' : '297mm' }};
                max-width: none;
                aspect-ratio: auto;
                box-shadow: none;
                margin: 0;
                padding: {{ $paperSize === 'f4' ? '18mm' : '20mm' }} !important;
                background: white;
                transform: none;
            }
        }
    </style>
    @endpush
</div>
