    <div x-data="{
        previewHtml: @js($body_html ?? ''),
        copied: '',
        showPreview: false,
        init() {
            this.$watch('$wire.body_html', value => { this.previewHtml = value; });
        },
        insertBlock(code) {
            const ta = this.$refs.editor;
            if (!ta) return;
            const start = ta.selectionStart;
            const end = ta.selectionEnd;
            const current = ta.value;
            const newValue = current.slice(0, start) + code + current.slice(end);
            this.$wire.set('body_html', newValue);
            this.previewHtml = newValue;
            this.$nextTick(() => {
                ta.focus();
                const pos = start + code.length;
                ta.setSelectionRange(pos, pos);
            });
        }
    }">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $templateId ? 'Edit Template' : 'Buat Template Baru' }}</h2>
                <p class="text-sm text-gray-500">Buat dan edit template surat desa</p>
            </div>
            <a href="{{ route('surat.template.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <form wire:submit="save" class="space-y-6">
        {{-- Info Template --}}
        <div class="bg-white/70 backdrop-blur-xl border border-white/70 rounded-3xl shadow-xl shadow-slate-200/50 p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Informasi Template</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Template</label>
                    <input wire:model="nama" type="text" placeholder="Surat Keterangan Domisili" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug</label>
                    <input wire:model="slug" type="text" placeholder="surat-domisili" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Surat</label>
                    <select wire:model="jenis_surat" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="domisili">Domisili</option>
                        <option value="tidak_mampu">Tidak Mampu</option>
                        <option value="usaha">Usaha</option>
                        <option value="pengantar_ktp_kk">Pengantar KTP/KK</option>
                        <option value="kelahiran">Kelahiran</option>
                        <option value="kematian">Kematian</option>
                        <option value="pindah">Pindah</option>
                        <option value="belum_menikah">Belum Menikah</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Format Nomor</label>
                    <input wire:model="default_nomor_format" type="text" placeholder="{nomor}/SRT/{jenis}/{bulan_romawi}/{tahun}" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>
            <div class="mt-4">
                <label class="inline-flex items-center gap-3 px-4 py-2.5 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                    <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-sm font-medium text-gray-900">Template Aktif</span>
                </label>
            </div>
        </div>

        {{-- Editor / Preview dengan toggle --}}
        <div class="bg-white/70 backdrop-blur-xl border border-white/70 rounded-3xl shadow-xl shadow-slate-200/50">
            {{-- Header toggle --}}
            <div class="flex flex-wrap items-center gap-3 px-5 py-4 border-b border-gray-100">
                <div class="inline-flex items-center bg-gray-100 rounded-2xl p-1.5 shadow-inner">
                    <button type="button"
                            @click="showPreview = false"
                            :class="!showPreview ? 'bg-white text-indigo-600 shadow' : 'text-gray-500 hover:text-gray-700'"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editor
                    </button>
                    <button type="button"
                            @click="showPreview = true"
                            :class="showPreview ? 'bg-white text-indigo-600 shadow' : 'text-gray-500 hover:text-gray-700'"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview
                    </button>
                </div>
                <span class="ml-auto text-xs font-medium text-gray-400" x-show="!showPreview">HTML</span>
                <span class="ml-auto text-xs font-medium text-gray-400" x-show="showPreview" x-cloak>A4 (210×297mm)</span>
            </div>

            {{-- Body --}}
            <div class="p-5">
                {{-- Editor --}}
                <div x-show="!showPreview">
                    <textarea
                        x-ref="editor"
                        wire:model="body_html"
                        @input.debounce.300ms="previewHtml = $event.target.value"
                        rows="24"
                        placeholder="Tulis HTML template surat di sini..."
                        class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl text-sm font-mono leading-relaxed focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all resize-y"
                        style="min-height: 520px;"></textarea>
                    @error('body_html') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Preview --}}
                <div x-show="showPreview" x-cloak>
                    <div class="bg-gray-100 rounded-xl p-4 sm:p-6">
                        <div class="bg-white shadow-md mx-auto"
                             style="width: 100%; max-width: 210mm; min-height: 297mm; padding: 20mm; box-sizing: border-box; color: #000; line-height: 1.55; font-family: 'Times New Roman', Times, serif; font-size: 12pt;">
                            <div x-show="previewHtml && previewHtml.trim() !== ''" x-html="previewHtml"></div>
                            <template x-if="!previewHtml || previewHtml.trim() === ''">
                                <p class="text-gray-400 italic text-center" style="font-family: Inter, sans-serif; font-size: 13px;">Preview akan muncul di sini saat Anda mengetik...</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Blok Kode --}}
        @if($blocks->count() > 0)
        <div class="bg-white/70 backdrop-blur-xl border border-white/70 rounded-3xl shadow-xl shadow-slate-200/50 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Blok Kode</h3>
                <a href="{{ route('surat.manual.blok') }}" class="text-[11px] text-indigo-600 hover:underline">Kelola blok</a>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($blocks as $block)
                    <button type="button"
                        @click="insertBlock(@js($block->kode))"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 rounded-lg transition-colors"
                        title="{{ Str::limit($block->kode, 100) }}">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        {{ $block->nama }}
                    </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Variabel --}}
        <div class="bg-white/70 backdrop-blur-xl border border-white/70 rounded-3xl shadow-xl shadow-slate-200/50 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Variabel</h3>
                <span class="text-[11px] text-gray-400">Klik untuk copy</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach($varGroups as $group)
                    <div class="bg-{{ $group['color'] }}-50 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-{{ $group['color'] }}-800 mb-1.5 uppercase tracking-wider">{{ $group['title'] }}</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($group['vars'] as $v)
                                <button type="button"
                                    @click="navigator.clipboard.writeText('{{ '{' . '{' . $v['var'] . '}' . '}' }}'); copied='{{ $v['var'] }}'; setTimeout(()=>copied='', 1200)"
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-{{ $group['color'] }}-100 hover:bg-{{ $group['color'] }}-200 rounded-lg text-[10px] font-mono text-{{ $group['color'] }}-700 transition-colors"
                                    title="{{ $v['example'] }}">
                                    {{ '{' . '{' . $v['var'] . '}' . '}' }}
                                    <span x-show="copied==='{{ $v['var'] }}'" x-transition class="text-emerald-600 font-bold">✓</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('surat.template.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $templateId ? 'Update Template' : 'Simpan Template' }}
            </button>
        </div>
    </form>
</div>