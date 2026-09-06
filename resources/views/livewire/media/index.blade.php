<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">File / Media</h2>
                <p class="text-sm text-gray-500">Kelola gambar — otomatis dikompres & di-resize saat upload</p>
            </div>
        </div>
    </x-slot>

    <x-flash-toast />

    {{-- Upload --}}
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="mediaUploader()" x-on:media-uploaded.window="previews = []; $refs.fileInput.value = ''">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Upload Gambar</h3>
                <p class="text-xs text-gray-500">JPG, PNG, WebP, GIF · maks 10 MB/file · kompresi otomatis (resize max 1600px)</p>
            </div>
        </div>

        {{-- Drop Zone --}}
        <div x-on:dragover.prevent="dragging = true"
             x-on:dragleave.prevent="dragging = false"
             x-on:drop.prevent="handleDrop($event)"
             x-on:click="$refs.fileInput.click()"
             :class="dragging ? 'border-indigo-500 bg-indigo-50 scale-[1.01]' : 'border-gray-300 bg-gray-50'"
             class="border-2 border-dashed rounded-2xl p-10 sm:p-14 text-center cursor-pointer transition-all duration-150 group">
            <div class="mx-auto w-14 h-14 rounded-2xl bg-white shadow-sm border border-gray-200 flex items-center justify-center group-hover:bg-indigo-50 group-hover:border-indigo-200 transition-colors mb-4">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-700">Seret & letakkan gambar di sini</p>
            <p class="text-sm text-gray-400 mt-1">atau <span class="text-indigo-600 font-semibold underline decoration-indigo-200 underline-offset-2">klik untuk memilih file</span></p>
            <p class="text-xs text-gray-400 mt-3">JPG, PNG, WebP, GIF · maks 10 MB per file · multi-file</p>
            <input x-ref="fileInput" type="file" wire:model="uploadFiles" multiple accept="image/jpeg,image/png,image/webp,image/gif"
                   class="hidden" x-on:change="buildPreviews($event)">
        </div>

        @error('uploadFiles.*') <p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror
        @error('uploadFiles') <p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror

        {{-- Preview --}}
        <div x-show="previews.length > 0" x-transition class="mt-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2" x-text="previews.length + ' file dipilih'"></p>
            <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-6 gap-3">
                <template x-for="(p, i) in previews" :key="i">
                    <div class="relative rounded-xl overflow-hidden border border-gray-200 bg-gray-100 group/preview" style="aspect-ratio: 1 / 1">
                        <img :src="p.url" class="w-full h-full object-cover" alt="Preview">
                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent px-2 pt-4 pb-1">
                            <p class="text-[10px] text-white truncate" x-text="p.name"></p>
                        </div>
                        <button type="button" x-on:click="removePreview(i)"
                                class="absolute top-1 right-1 w-5 h-5 rounded-full bg-black/50 text-white text-xs flex items-center justify-center opacity-0 group-hover/preview:opacity-100 transition-opacity hover:bg-red-500">×</button>
                    </div>
                </template>
            </div>
            <div class="flex items-center justify-between mt-4">
                <div wire:loading wire:target="uploadFiles" class="flex items-center gap-2 text-sm text-indigo-600">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Mengunggah file...
                </div>
                <div wire:loading wire:target="saveUploads" class="flex items-center gap-2 text-sm text-amber-600">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Mengompres gambar...
                </div>
                <div class="flex-1"></div>
                <button wire:click="saveUploads" wire:loading.attr="disabled"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all disabled:opacity-50">
                    Upload & Kompres
                </button>
            </div>
        </div>
    </div>

    <script>
        function mediaUploader() {
            return {
                dragging: false,
                previews: [],
                buildPreviews(event) {
                    this.previews = [];
                    this.previewUrls().forEach(url => URL.revokeObjectURL(url));
                    for (const file of event.target.files) {
                        this.previews.push({ name: file.name, url: URL.createObjectURL(file) });
                    }
                },
                removePreview(index) {
                    const removed = this.previews.splice(index, 1)[0];
                    if (removed) URL.revokeObjectURL(removed.url);
                    if (this.previews.length === 0) this.$refs.fileInput.value = '';
                },
                previewUrls() {
                    return this.previews.map(p => p.url);
                },
                handleDrop(event) {
                    this.dragging = false;
                    const dt = new DataTransfer();
                    for (const file of event.dataTransfer.files) {
                        dt.items.add(file);
                    }
                    const input = this.$refs.fileInput;
                    input.files = dt.files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }
    </script>

    {{-- Grid Gambar --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse ($files as $file)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-100" style="aspect-ratio: 1 / 1">
                    <img src="{{ $file->url }}" alt="{{ $file->original_name }}" class="w-full h-full object-cover" loading="lazy">
                </div>
                <div class="p-3">
                    <p class="text-xs font-medium text-gray-900 truncate" title="{{ $file->original_name }}">{{ $file->original_name }}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">{{ $file->size_label }} · {{ $file->created_at->format('d/m/Y') }}</p>
                    <div class="flex gap-1.5 mt-2">
                        <button onclick="copyMediaUrl(this, '{{ $file->url }}')" class="flex-1 px-2 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">📋 Copy URL</button>
                        <button wire:click="delete({{ $file->id }})" wire:confirm="Yakin ingin menghapus gambar ini?" title="Hapus" class="px-2.5 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">🗑️</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center">
                <p class="text-sm text-gray-500">Belum ada gambar. Upload gambar pertama Anda.</p>
            </div>
        @endforelse
    </div>

    <script>
        function copyMediaUrl(btn, url) {
            navigator.clipboard.writeText(url).then(() => {
                const original = btn.textContent;
                btn.textContent = '✓ Tersalin';
                btn.classList.remove('text-indigo-600', 'bg-indigo-50');
                btn.classList.add('text-emerald-600', 'bg-emerald-50');
                setTimeout(() => {
                    btn.textContent = original;
                    btn.classList.remove('text-emerald-600', 'bg-emerald-50');
                    btn.classList.add('text-indigo-600', 'bg-indigo-50');
                }, 1500);
            }).catch(() => {
                prompt('Salin URL secara manual:', url);
            });
        }
    </script>
</div>