<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Struktur Pemerintahan</h2>
                <p class="text-sm text-gray-500">Kelola struktur organisasi pemerintahan desa</p>
            </div>
        </div>
    </x-slot>

    <x-flash-toast />

    <!-- Form -->
    @if ($showForm)
        <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">{{ $editId ? 'Edit Struktur' : 'Tambah Jabatan' }}</h3>
            <form wire:submit="save" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jabatan</label>
                        <input wire:model="jabatan" type="text" placeholder="Contoh: Kepala Desa" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('jabatan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama</label>
                        <input wire:model="nama" type="text" placeholder="Nama pejabat" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Atasan Langsung</label>
                        <select wire:model="parentId" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">-- Tidak ada (Pimpinan Utama) --</option>
                            @foreach ($strukturList as $item)
                                @if ($item->id !== $editId)
                                    <option value="{{ $item->id }}">{{ $item->jabatan }} - {{ $item->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika sejajar dengan pimpinan utama</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input wire:model="foto" type="text" placeholder="URL foto" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    @endif

    <!-- Bagan Struktur -->
    @if (count($strukturList) > 0)
        @php
            $roots = $strukturList->whereNull('parent_id');
        @endphp
        @if ($roots->count() > 0)
            <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-6 pt-4 pb-3 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Bagan Struktur</h3>
                    <div class="flex items-center gap-1">
                        <button onclick="strukturZoomOut()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors" title="Zoom Out">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <span id="zoomLevel" class="text-xs font-medium text-gray-500 w-12 text-center">100%</span>
                        <button onclick="strukturZoomIn()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors" title="Zoom In">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                        <button onclick="strukturResetZoom()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors ml-1" title="Reset">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                </div>

                <div id="strukturViewport" style="overflow: hidden; cursor: grab; height: 500px; position: relative; background: repeating-conic-gradient(#f9fafb 0% 25%, white 0% 50%) 50% / 20px 20px;">
                    <div id="strukturCanvas" style="transform-origin: 0 0; position: absolute;">
                        <div class="flex flex-col items-center">
                            <div class="flex justify-center gap-4">
                                @foreach ($roots as $item)
                                    @include('livewire.struktur-pemerintahan._node', ['item' => $item, 'depth' => 0])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-2 border-t border-gray-100 flex items-center justify-center">
                    <p class="text-[11px] text-gray-400">Geser: tahan klik lalu seret &bull; Zoom: scroll mouse &bull; Tombol +/− di atas</p>
                </div>
            </div>
        @endif
    @endif

    @push('scripts')
    <script>
        (function() {
            const viewport = document.getElementById('strukturViewport');
            const canvas = document.getElementById('strukturCanvas');
            const zoomLabel = document.getElementById('zoomLevel');
            if (!viewport || !canvas) return;

            let scale = 0.5;
            let panX = 0;
            let panY = 0;
            let isDragging = false;
            let startX = 0;
            let startY = 0;
            let startPanX = 0;
            let startPanY = 0;

            function applyTransform() {
                canvas.style.transform = `translate(${panX}px, ${panY}px) scale(${scale})`;
                zoomLabel.textContent = Math.round(scale * 100) + '%';
            }

            // Center on load
            function centerView() {
                const vw = viewport.clientWidth;
                const cw = canvas.scrollWidth || canvas.offsetWidth;
                if (cw > 0) {
                    panX = (vw - cw * scale) / 2;
                    panY = 20;
                }
                applyTransform();
            }

            // Mouse drag
            viewport.addEventListener('mousedown', (e) => {
                isDragging = true;
                startX = e.clientX;
                startY = e.clientY;
                startPanX = panX;
                startPanY = panY;
                viewport.style.cursor = 'grabbing';
                e.preventDefault();
            });
            window.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                panX = startPanX + (e.clientX - startX);
                panY = startPanY + (e.clientY - startY);
                applyTransform();
            });
            window.addEventListener('mouseup', () => {
                isDragging = false;
                viewport.style.cursor = 'grab';
            });

            // Scroll wheel zoom
            viewport.addEventListener('wheel', (e) => {
                e.preventDefault();
                const delta = e.deltaY > 0 ? -0.1 : 0.1;
                const newScale = Math.min(2, Math.max(0.2, scale + delta));

                // Zoom toward cursor
                const rect = viewport.getBoundingClientRect();
                const cx = e.clientX - rect.left;
                const cy = e.clientY - rect.top;
                const ratio = newScale / scale;
                panX = cx - (cx - panX) * ratio;
                panY = cy - (cy - panY) * ratio;

                scale = newScale;
                applyTransform();
            }, { passive: false });

            // Touch support
            let lastTouchDist = 0;
            let lastTouchX = 0;
            let lastTouchY = 0;
            viewport.addEventListener('touchstart', (e) => {
                if (e.touches.length === 1) {
                    isDragging = true;
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                    startPanX = panX;
                    startPanY = panY;
                } else if (e.touches.length === 2) {
                    isDragging = false;
                    lastTouchDist = Math.hypot(
                        e.touches[0].clientX - e.touches[1].clientX,
                        e.touches[0].clientY - e.touches[1].clientY
                    );
                }
                e.preventDefault();
            }, { passive: false });
            viewport.addEventListener('touchmove', (e) => {
                if (e.touches.length === 1 && isDragging) {
                    panX = startPanX + (e.touches[0].clientX - startX);
                    panY = startPanY + (e.touches[0].clientY - startY);
                    applyTransform();
                } else if (e.touches.length === 2) {
                    const dist = Math.hypot(
                        e.touches[0].clientX - e.touches[1].clientX,
                        e.touches[0].clientY - e.touches[1].clientY
                    );
                    const newScale = Math.min(2, Math.max(0.2, scale * (dist / lastTouchDist)));
                    const midX = (e.touches[0].clientX + e.touches[1].clientX) / 2;
                    const midY = (e.touches[0].clientY + e.touches[1].clientY) / 2;
                    const rect = viewport.getBoundingClientRect();
                    const cx = midX - rect.left;
                    const cy = midY - rect.top;
                    const ratio = newScale / scale;
                    panX = cx - (cx - panX) * ratio;
                    panY = cy - (cy - panY) * ratio;
                    scale = newScale;
                    lastTouchDist = dist;
                    applyTransform();
                }
                e.preventDefault();
            }, { passive: false });
            viewport.addEventListener('touchend', () => { isDragging = false; });

            // Expose zoom controls
            window.strukturZoomIn = () => { scale = Math.min(2, scale + 0.15); applyTransform(); };
            window.strukturZoomOut = () => { scale = Math.max(0.2, scale - 0.15); applyTransform(); };
            window.strukturResetZoom = () => { scale = 0.5; centerView(); };

            // Init with delay to ensure DOM is fully rendered
            centerView();
            setTimeout(centerView, 100);
            setTimeout(centerView, 300);
        })();
    </script>
    @endpush

    <!-- Daftar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ count($strukturList) }} jabatan terdaftar</p>
            @if (!$showForm)
                <button wire:click="openForm" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
            @endif
        </div>

        <div class="divide-y divide-gray-100">
            @forelse ($strukturList as $item)
                <div class="flex items-center justify-between p-5 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if ($item->foto)
                                <img src="{{ $item->foto }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-lg font-bold text-indigo-600">{{ substr($item->nama, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $item->jabatan }}</p>
                            <p class="text-sm text-gray-600">{{ $item->nama }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">

                        <button wire:click="edit({{ $item->id }})" class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus?" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Belum ada data</p>
                    <p class="text-sm text-gray-500 mt-1">Mulai tambah jabatan pemerintahan desa</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
