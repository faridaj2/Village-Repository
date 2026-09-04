<div>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Peta Desa</h2>
            <p class="text-sm text-gray-500">Visualisasi lokasi rumah & fasilitas umum</p>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-4 border-b border-gray-100 space-y-3">
            <div class="flex flex-wrap items-center gap-3">
                <!-- RTLH -->
                <select wire:model.live="filterRtlh" class="px-3 py-2 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Kategori</option>
                    <option value="layak">Layak Huni</option>
                    <option value="tidak_layak">Tidak Layak</option>
                </select>

                <div class="w-px h-6 bg-gray-200"></div>

                <!-- RW Buttons -->
                <div class="flex items-center gap-1.5">
                    <span class="text-xs font-semibold text-gray-500 uppercase">RW</span>
                    <button wire:click="$set('filterRw', '')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $filterRw === '' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Semua</button>
                    @foreach ($rwList as $rw)
                        <button wire:click="$set('filterRw', '{{ $rw->id }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $filterRw == $rw->id ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $rw->nama }}</button>
                    @endforeach
                </div>

                <!-- RT Buttons -->
                @if ($filterRw && count($rtList) > 0)
                    <div class="w-px h-6 bg-gray-200"></div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-semibold text-gray-500 uppercase">RT</span>
                        <button wire:click="$set('filterRt', '')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $filterRt === '' ? 'bg-purple-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Semua</button>
                        @foreach ($rtList as $rt)
                            <button wire:click="$set('filterRt', '{{ $rt->id }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $filterRt == $rt->id ? 'bg-purple-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $rt->nama }}</button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span> Rumah Layak</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span> Rumah Tidak Layak</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span> Fasilitas Umum</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-1 bg-red-500 rounded"></span> Batas Desa</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-indigo-500 rounded"></span> Batas RW</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-purple-500 rounded"></span> Batas RT</span>
                <span class="ml-auto text-gray-400">{{ count($rumahs) }} rumah &middot; {{ count($fasilitas) }} fasilitas</span>
            </div>
        </div>

        <!-- Map -->
        <div x-data="petaDesa()" x-init="$nextTick(() => init())" style="height: 600px;">
            <div x-ref="map" class="w-full h-full"></div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function petaDesa() {
            return {
                map: null,
                markers: [],
                boundaryLayers: [],
                initialized: false,

                init() {
                    if (this.initialized) return;
                    this.initialized = true;

                    this.map = L.map(this.$refs.map, {
                        zoomControl: true,
                        scrollWheelZoom: true,
                    }).setView([-3.4, 126.97], 14);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
                        maxZoom: 19,
                    }).addTo(this.map);

                    setTimeout(() => {
                        this.map.invalidateSize();
                        this.renderAll();
                    }, 300);

                    Livewire.hook('commit', ({ component, succeed }) => {
                        succeed(() => {
                            setTimeout(() => this.renderAll(), 100);
                        });
                    });
                },

                renderAll() {
                    this.clearMarkers();
                    this.renderBoundaries();
                    this.renderMarkers();
                },

                clearMarkers() {
                    this.markers.forEach(m => this.map.removeLayer(m));
                    this.markers = [];
                    this.boundaryLayers.forEach(l => this.map.removeLayer(l));
                    this.boundaryLayers = [];
                },

                renderBoundaries() {
                    const boundaries = @json($boundaries);
                    boundaries.forEach(b => {
                        if (!b.geojson) return;
                        try {
                            const geojson = JSON.parse(b.geojson);
                            const isDesa = b.tipe === 'Desa';
                            const isRw = b.tipe === 'RW';
                            const layer = L.geoJSON(geojson, {
                                style: {
                                    color: b.warna || '#6366f1',
                                    weight: isDesa ? 4 : (isRw ? 3 : 2),
                                    opacity: isDesa ? 1 : 0.8,
                                    fillColor: b.warna || '#6366f1',
                                    fillOpacity: isDesa ? 0.03 : (isRw ? 0.06 : 0.1),
                                    dashArray: isDesa ? null : (isRw ? '8, 4' : '4, 4'),
                                }
                            }).addTo(this.map);
                            layer.bindPopup(`<b>${b.tipe}: ${b.nama}</b>`);
                            this.boundaryLayers.push(layer);
                        } catch (e) {}
                    });
                },

                renderMarkers() {
                    const rumahs = @json($rumahs);
                    const fasilitas = @json($fasilitas);

                    rumahs.forEach(r => {
                        if (!r.lat || !r.lng) return;
                        const color = r.rtlh === 'layak' ? '#10b981' : '#ef4444';
                        const marker = L.circleMarker([r.lat, r.lng], {
                            radius: 10,
                            fillColor: color,
                            color: '#fff',
                            weight: 2,
                            fillOpacity: 0.9
                        }).addTo(this.map);
                        marker.bindPopup(`
                            <div style="font-family:Inter,sans-serif;min-width:180px">
                                <b style="font-size:14px">${r.kode}</b><br>
                                <span style="color:#666;font-size:12px">${r.alamat}</span><br>
                                <span style="color:#999;font-size:11px">${r.rw} / ${r.rt}</span>
                                ${r.rtlh ? `<br><span style="display:inline-block;margin-top:4px;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:600;background:${r.rtlh === 'layak' ? '#d1fae5' : '#fee2e2'};color:${r.rtlh === 'layak' ? '#065f46' : '#991b1b'}">${r.rtlh === 'layak' ? 'Layak Huni' : 'Tidak Layak'}</span>` : ''}
                            </div>
                        `);
                        this.markers.push(marker);
                    });

                    fasilitas.forEach(f => {
                        if (!f.lat || !f.lng) return;
                        const marker = L.circleMarker([f.lat, f.lng], {
                            radius: 10,
                            fillColor: '#3b82f6',
                            color: '#fff',
                            weight: 2,
                            fillOpacity: 0.9
                        }).addTo(this.map);
                        marker.bindPopup(`
                            <div style="font-family:Inter,sans-serif;min-width:150px">
                                <b style="font-size:14px">${f.nama}</b><br>
                                <span style="display:inline-block;margin-top:2px;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:500;background:#dbeafe;color:#1e40af">${f.jenis}</span>
                            </div>
                        `);
                        this.markers.push(marker);
                    });

                    const allLayers = [...this.boundaryLayers, ...this.markers];
                    if (allLayers.length > 0) {
                        const group = L.featureGroup(allLayers);
                        this.map.fitBounds(group.getBounds().pad(0.15));
                    }
                }
            }
        }
    </script>
    @endpush
</div>
