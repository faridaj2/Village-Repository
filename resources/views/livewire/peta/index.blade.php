<div>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Peta Desa</h2>
            <p class="text-sm text-gray-500">Visualisasi lokasi rumah & fasilitas umum</p>
        </div>
    </x-slot>

    <x-flash-toast />

    <div class="bg-white/70 backdrop-blur-xl border border-white/70 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden">
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
        <script>
            window.petaData = {
                rumahs: @js($rumahs),
                fasilitas: @js($fasilitas),
                boundaries: @js($boundaries)
            };
        </script>
        <div wire:ignore x-data="petaDesa()" x-init="$nextTick(() => init())" x-on:map-data.window="window.petaData = $event.detail; $nextTick(() => renderAll())" class="relative" style="height: 600px;">
            <div x-ref="map" class="w-full h-full"></div>

            {{-- Custom Base Layer Switcher (Glass) --}}
            <div class="absolute top-4 right-4 z-[1000] flex flex-col gap-1.5 p-2 bg-white/80 backdrop-blur-xl border border-white/70 rounded-2xl shadow-xl shadow-slate-200/50">
                <button @click="setBase('osm')" class="group flex items-center gap-2.5 px-3 py-2 rounded-xl text-left transition-all duration-200" :class="currentBase === 'osm' ? 'bg-gradient-to-r from-indigo-500 to-cyan-500 text-white shadow-lg shadow-indigo-500/30' : 'hover:bg-indigo-50 text-gray-600'">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v6.75m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg>
                    <span class="text-xs font-semibold whitespace-nowrap">Peta Jalan</span>
                </button>
                <button @click="setBase('satelit')" class="group flex items-center gap-2.5 px-3 py-2 rounded-xl text-left transition-all duration-200" :class="currentBase === 'satelit' ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-lg shadow-emerald-500/30' : 'hover:bg-emerald-50 text-gray-600'">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                    <span class="text-xs font-semibold whitespace-nowrap">Satelit</span>
                </button>
                <button @click="setBase('topo')" class="group flex items-center gap-2.5 px-3 py-2 rounded-xl text-left transition-all duration-200" :class="currentBase === 'topo' ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg shadow-amber-500/30' : 'hover:bg-amber-50 text-gray-600'">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                    <span class="text-xs font-semibold whitespace-nowrap">Topografi</span>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-popup-content-wrapper { border-radius: 16px; box-shadow: 0 12px 32px -8px rgba(15,23,42,.28); font-family: 'Plus Jakarta Sans', sans-serif; }
        .leaflet-popup-content { margin: 14px 18px; line-height: 1.5; }
        .leaflet-container { font-family: 'Plus Jakarta Sans', sans-serif; }
        .leaflet-bar a { border-radius: 10px !important; }
    </style>
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

                    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
                        maxZoom: 19,
                    });

                    const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics',
                        maxZoom: 19,
                    });

                    const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                        attribution: 'Map data: &copy; OpenStreetMap contributors, SRTM | Style: &copy; OpenTopoMap',
                        maxZoom: 17,
                    });

                    const labels = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
                        maxZoom: 20,
                        opacity: 0.9,
                    });

                    // Base layers untuk custom switcher
                    // Satellite + labels overlay (label agar nama jalan tetap terlihat)
                    this._base = {
                        osm: osm,
                        satelit: L.layerGroup([satellite, labels]),
                        topo: topo,
                    };
                    this.currentBase = 'osm';
                    osm.addTo(this.map);

                    setTimeout(() => {
                        this.map.invalidateSize();
                        this.renderAll();
                    }, 300);


                },

                setBase(key) {
                    if (!this.map || this.currentBase === key || !this._base || !this._base[key]) return;
                    this.map.removeLayer(this._base[this.currentBase]);
                    this._base[key].addTo(this.map);
                    this.currentBase = key;
                    setTimeout(() => this.map.invalidateSize(), 100);
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
                    const boundaries = window.petaData.boundaries;
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
                    const rumahs = window.petaData.rumahs;
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
                            <div style="font-family:'Plus Jakarta Sans',sans-serif;min-width:180px">
                                <b style="font-size:14px">${r.kode}</b><br>
                                <span style="color:#666;font-size:12px">${r.alamat}</span><br>
                                <span style="color:#999;font-size:11px">${r.rw} / ${r.rt}</span>
                                ${r.rtlh ? `<br><span style="display:inline-block;margin-top:4px;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:600;background:${r.rtlh === 'layak' ? '#d1fae5' : '#fee2e2'};color:${r.rtlh === 'layak' ? '#065f46' : '#991b1b'}">${r.rtlh === 'layak' ? 'Layak Huni' : 'Tidak Layak'}</span>` : ''}
                            </div>
                        `);
                        this.markers.push(marker);
                    });

                    const fasilitas = window.petaData.fasilitas;
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
                            <div style="font-family:'Plus Jakarta Sans',sans-serif;min-width:150px">
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
