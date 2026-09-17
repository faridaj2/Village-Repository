{{-- ============ PETA DESA ============ --}}
@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

<section id="peta" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-14 reveal">
            <div class="ornament mb-6">
                <span class="text-gold-600 text-xs font-bold tracking-[0.3em] uppercase">Peta Interaktif</span>
            </div>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-slate-900 leading-tight mb-5">
                Jelajahi <span class="text-brand-900">Wilayah Desa</span>
            </h2>
            <p class="text-base text-slate-500 leading-relaxed">
                Visualisasi lokasi fasilitas umum dan batas wilayah {{ $desa->nama_desa ?? 'Desa Waeleman' }}.
            </p>
        </div>

        <div class="reveal rounded-3xl overflow-hidden border border-slate-200 shadow-[0_24px_60px_-30px_rgba(6,78,59,0.35)]">
            <div id="desaMap" class="w-full h-[480px] sm:h-[560px] bg-slate-100" data-center-lat="{{ $desa->center_lat ?? -9.5 }}" data-center-lng="{{ $desa->center_lng ?? 124.9 }}" data-zoom="{{ $desa->zoom_level ?? 13 }}" data-markers='@json($markers)'></div>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-5 text-xs text-slate-500 reveal">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-brand-900"></span>
                <span class="font-medium">Fasilitas Umum</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-gold-500"></span>
                <span class="font-medium">Kantor Desa</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-sm border-2 border-brand-700"></span>
                <span class="font-medium">Batas Wilayah</span>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
    (function () {
        const el = document.getElementById('desaMap');
        if (!el || typeof L === 'undefined') return;

        const center = [parseFloat(el.dataset.centerLat) || -9.5, parseFloat(el.dataset.centerLng) || 124.9];
        const zoom = parseInt(el.dataset.zoom) || 13;
        let markers = [];
        try { markers = JSON.parse(el.dataset.markers || '[]'); } catch (e) {}

        const map = L.map(el, { scrollWheelZoom: false }).setView(center, zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19,
        }).addTo(map);

        const icon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="width:32px;height:32px;background:linear-gradient(135deg,#064e3b,#047857);border:3px solid #d4af37;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 8px 20px -6px rgba(6,78,59,0.6);"></div>',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32],
        });

        markers.forEach(m => {
            const popup = '<div style="font-family:Plus Jakarta Sans,sans-serif;min-width:160px;"><div style="font-size:10px;font-weight:700;color:#d4af37;text-transform:uppercase;letter-spacing:0.15em;margin-bottom:4px;">' + (m.jenis || 'Fasilitas') + '</div><div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">' + m.nama + '</div>' + (m.rt ? '<div style="font-size:11px;color:#64748b;">RT ' + m.rt + '</div>' : '') + '</div>';
            L.marker([m.lat, m.lng], { icon: icon }).addTo(map).bindPopup(popup);
        });
    })();
    </script>
@endpush
