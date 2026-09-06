<div x-data="{ paperSize: 'a4', previewHtml: @js($body_html ?? ''), showVariables: false, showModal: false, copied: '' }" x-init="$watch('$wire.body_html', value => { previewHtml = value; });">
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

    <form wire:submit="save">
        {{-- Setting (full width) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Informasi Template</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Template</label>
                    <input wire:model="nama" type="text" placeholder="Contoh: Surat Keterangan Domisili" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
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
                    <input wire:model="default_nomor_format" type="text" placeholder="{nomor}/SRT/{jenis}/{bulan_romawi}/{tahun}" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>
            <div class="mt-4">
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors w-fit">
                    <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-sm font-medium text-gray-900">Template Aktif</span>
                </label>
            </div>
        </div>

        {{-- Editor + Preview side by side --}}
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6 mb-6">
            {{-- Kiri: Editor + Variabel --}}
            <div class="xl:col-span-2">
                    {{-- Body Editor --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Isi Template Surat</h3>
                            <select x-model="paperSize" class="px-3 py-1.5 bg-gray-50 border-0 rounded-lg text-xs font-medium">
                                <option value="a4">A4</option>
                                <option value="f4">F4</option>
                            </select>
                        </div>
                        <textarea wire:model="body_html" wire:keyup="previewHtml = $event.target.value" rows="24" placeholder="Tulis HTML template surat di sini..." class="w-full px-4 py-3 bg-gray-50 border-0 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all resize-y" style="min-height: 400px; font-family: 'JetBrains Mono', 'Fira Code', monospace;"></textarea>
                        @error('body_html') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Variabel --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Variabel</h3>
                        </div>
                        <div class="space-y-2">
                            @foreach($varGroups as $group)
                            <div class="bg-{{ $group['color'] }}-50 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-{{ $group['color'] }}-800 mb-1.5 uppercase tracking-wider">{{ $group['title'] }}</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($group['vars'] as $v)
                                    <button type="button"
                                        @click="navigator.clipboard.writeText('{{ '{' . '{' . $v['var'] . '}' . '}' }}'); copied='{{ $v['var'] }}'; setTimeout(()=>copied='',1500)"
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-{{ $group['color'] }}-100 hover:bg-{{ $group['color'] }}-200 rounded-lg text-[10px] font-mono text-{{ $group['color'] }}-700 transition-colors cursor-pointer">
                                        {{ '{' . '{' . $v['var'] . '}' . '}' }}
                                        <span x-show="copied==='{{ $v['var'] }}'" x-transition class="text-emerald-600">✓</span>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
            </div>
                {{-- Kanan: Preview --}}
            <div class="xl:col-span-3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4" style="position: sticky; top: 96px;">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Preview Surat</h3>
                        </div>
                        <div class="bg-gray-100 rounded-xl p-3 sm:p-4">
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm mx-auto" style="width: 100%; padding: 24px 28px; line-height: 1.5; color: #000; min-height: 560px; font-family: 'Times New Roman', Times, serif; font-size: 12pt;">
                                <div x-html="previewHtml"></div>
                            </div>
                        </div>
                        <p class="mt-2 text-center text-[11px] text-gray-400">Ukuran: <span x-text="paperSize === 'a4' ? 'A4 (210×297mm)' : 'F4 (215×330mm)'"></span></p>
                    </div>
                </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('surat.template.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                {{ $templateId ? 'Update Template' : 'Simpan Template' }}
            </button>
        </div>
    </form>




</div>
