{{-- Peta titik ODP (Leaflet). Data dari route odp.kml (Google My Maps / public/data/odp.kml). --}}
<div class="lg:col-span-7 rounded-2xl overflow-hidden glass-panel border border-white/10 shadow-xl relative odp-wrap">
    <div id="odp-map" role="region" aria-label="Peta titik ODP TRICORE DATA MEDIA"></div>

    {{-- Filter per wilayah (layer My Maps) --}}
    <div id="odp-filters"></div>

    <div id="odp-status">
        Memuat titik ODP&hellip;
    </div>

    <div id="odp-card">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span class="text-slate-200"><strong id="odp-count">&ndash;</strong> titik ODP &middot; Purwokerto Timur &amp; Sokaraja</span>
        </div>
        <a href="https://www.google.com/maps/d/viewer?mid={{ config('services.odp_map.mymaps_id') }}" target="_blank" rel="noopener" class="text-cyan-400 font-bold hover:underline whitespace-nowrap">Buka Maps &rarr;</a>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}">
<style>
    /* CSS biasa (bukan Tailwind) supaya tidak perlu rebuild Vite */
    .odp-wrap { min-height: 420px; isolation: isolate; }
    #odp-map { position: absolute; inset: 0; background: #0b1220; }
    #odp-filters { position: absolute; z-index: 1000; top: .75rem; left: 3.5rem; right: .75rem; display: flex; gap: .5rem; overflow-x: auto; padding-bottom: .25rem; font-size: .75rem; }
    #odp-status { position: absolute; inset: 0; z-index: 900; display: flex; align-items: center; justify-content: center; font-size: .875rem; color: #cbd5e1; background: rgba(2,6,23,.6); pointer-events: none; }
    #odp-card { position: absolute; z-index: 1000; left: .75rem; right: .75rem; bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: .75rem;
        padding: .75rem; font-size: .75rem; border-radius: .75rem; background: rgba(2,6,23,.92); border: 1px solid rgba(255,255,255,.1); backdrop-filter: blur(8px); }
    /* Gelapkan peta dasar OSM (hanya layer tile, pin tetap berwarna normal) */
    #odp-map .leaflet-tile-pane { filter: invert(1) hue-rotate(180deg) brightness(.85) contrast(.9) saturate(.7); }
    .odp-pin { background: none; border: 0; }
    .odp-pin svg { display: block; filter: drop-shadow(0 2px 3px rgba(0,0,0,.6)); transition: transform .15s; transform-origin: 50% 100%; }
    .odp-pin:hover svg { transform: scale(1.2); }
    .odp-chip { flex: none; display: inline-flex; align-items: center; gap: .4rem; padding: .3rem .7rem; border-radius: 9999px;
        background: rgba(2,6,23,.9); border: 1px solid rgba(255,255,255,.15); color: #e2e8f0; font-weight: 600; cursor: pointer; white-space: nowrap; }
    .odp-chip[aria-pressed="false"] { opacity: .45; }
    .odp-chip i { width: .6rem; height: .6rem; border-radius: 9999px; display: inline-block; }
    .odp-popup .leaflet-popup-content-wrapper { background: #0f172a; color: #e2e8f0; border: 1px solid rgba(255,255,255,.12); border-radius: .75rem; }
    .odp-popup .leaflet-popup-tip { background: #0f172a; }
    .odp-popup .leaflet-popup-content { margin: .75rem .9rem; font-size: .8rem; line-height: 1.4; }
    .odp-popup a { color: #22d3ee; font-weight: 700; }
</style>
<script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
<script>
(function () {
    const KML_URL = @json(route('odp.kml', [], false));
    const COLORS = ['#22d3ee', '#34d399', '#fbbf24', '#f472b6', '#a78bfa', '#fb923c'];

    const mapEl = document.getElementById('odp-map');
    const statusEl = document.getElementById('odp-status');
    const countEl = document.getElementById('odp-count');
    const filtersEl = document.getElementById('odp-filters');
    if (!mapEl || typeof L === 'undefined') return;

    const map = L.map(mapEl, { zoomControl: true, scrollWheelZoom: false }).setView([-7.4237, 109.2700], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Scroll mouse baru memperbesar peta setelah peta diklik (supaya tidak "menyandera" scroll halaman).
    map.on('click', () => map.scrollWheelZoom.enable());
    map.on('mouseout', () => map.scrollWheelZoom.disable());

    function pinIcon(color) {
        const svg = '<svg width="26" height="34" viewBox="0 0 26 34" xmlns="http://www.w3.org/2000/svg">'
            + '<path d="M13 1C6.4 1 1 6.3 1 12.8 1 21.6 13 33 13 33s12-11.4 12-20.2C25 6.3 19.6 1 13 1z" fill="' + color + '" stroke="#020617" stroke-width="1.5"/>'
            + '<circle cx="13" cy="12.5" r="4.5" fill="#020617"/></svg>';
        return L.divIcon({ className: 'odp-pin', html: svg, iconSize: [26, 34], iconAnchor: [13, 33], popupAnchor: [0, -30] });
    }

    function textOf(node, tag) {
        const el = node.getElementsByTagName(tag)[0];
        return el ? el.textContent.trim() : '';
    }

    // Deskripsi My Maps bisa berisi HTML -> ambil teks polosnya saja.
    function plainText(html) {
        const doc = new DOMParser().parseFromString(html.replace(/<br\s*\/?>/gi, '\n'), 'text/html');
        return (doc.body.textContent || '').trim();
    }

    function parseKml(xmlText) {
        const xml = new DOMParser().parseFromString(xmlText, 'application/xml');
        if (xml.getElementsByTagName('parsererror').length) throw new Error('KML tidak valid');

        const layers = [];
        const byName = new Map();
        const layerFor = (name) => {
            if (!byName.has(name)) {
                const layer = { name, points: [] };
                byName.set(name, layer);
                layers.push(layer);
            }
            return byName.get(name);
        };

        Array.from(xml.getElementsByTagName('Placemark')).forEach((pm) => {
            const point = pm.getElementsByTagName('Point')[0];
            if (!point) return; // abaikan garis/area, hanya titik ODP
            const parts = textOf(point, 'coordinates').split(/[\s,]+/).map(Number);
            const [lng, lat] = parts;
            if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

            const folder = pm.parentNode && pm.parentNode.nodeName === 'Folder' ? textOf(pm.parentNode, 'name') : '';
            layerFor(folder || 'ODP').points.push({
                name: textOf(pm, 'name') || 'ODP',
                desc: plainText(textOf(pm, 'description')),
                lat, lng
            });
        });
        return layers;
    }

    function popupFor(point, layerName) {
        const box = document.createElement('div');
        const title = document.createElement('div');
        title.style.cssText = 'font-weight:700;color:#fff;font-size:.9rem';
        title.textContent = point.name;
        const area = document.createElement('div');
        area.style.cssText = 'color:#94a3b8;margin-bottom:.35rem';
        area.textContent = layerName;
        box.append(title, area);
        if (point.desc) {
            const d = document.createElement('div');
            d.style.cssText = 'margin-bottom:.4rem;white-space:pre-line';
            d.textContent = point.desc.length > 160 ? point.desc.slice(0, 160) + '…' : point.desc;
            box.append(d);
        }
        const link = document.createElement('a');
        link.href = 'https://www.google.com/maps/dir/?api=1&destination=' + point.lat + ',' + point.lng;
        link.target = '_blank';
        link.rel = 'noopener';
        link.textContent = 'Petunjuk arah →';
        box.append(link);
        return box;
    }

    function shortName(name) {
        return name.split('|').pop().trim();
    }

    function render(layers) {
        const all = [];
        let total = 0;

        layers.forEach((layer, i) => {
            const color = COLORS[i % COLORS.length];
            const group = L.layerGroup();
            const icon = pinIcon(color);

            layer.points.forEach((p) => {
                L.marker([p.lat, p.lng], { icon, title: p.name, keyboard: true })
                    .bindPopup(popupFor(p, shortName(layer.name)), { className: 'odp-popup', maxWidth: 240 })
                    .addTo(group);
                all.push([p.lat, p.lng]);
            });
            group.addTo(map);
            total += layer.points.length;

            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'odp-chip';
            chip.setAttribute('aria-pressed', 'true');
            const dot = document.createElement('i');
            dot.style.background = color;
            chip.append(dot, document.createTextNode(shortName(layer.name) + ' (' + layer.points.length + ')'));
            chip.addEventListener('click', () => {
                const on = chip.getAttribute('aria-pressed') !== 'true';
                chip.setAttribute('aria-pressed', String(on));
                on ? group.addTo(map) : map.removeLayer(group);
            });
            filtersEl.append(chip);
        });

        countEl.textContent = total;
        if (all.length) map.fitBounds(all, { paddingTopLeft: [30, 50], paddingBottomRight: [30, 130], maxZoom: 16 });
        return total;
    }

    fetch(KML_URL, { headers: { 'Accept': 'application/xml, text/xml, */*' } })
        .then((r) => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.text(); })
        .then((text) => {
            const total = render(parseKml(text));
            if (total) statusEl.remove();
            else statusEl.textContent = 'Belum ada titik ODP.';
        })
        .catch(() => {
            countEl.textContent = '0';
            statusEl.textContent = 'Titik ODP belum bisa dimuat.';
        });
})();
</script>
@endpush
