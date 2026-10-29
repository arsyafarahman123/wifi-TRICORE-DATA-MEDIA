{{-- Google My Maps asli (embed). Pin ODP & layer mengikuti isi peta My Maps; tanpa API key. --}}
<div class="lg:col-span-7 rounded-2xl overflow-hidden glass-panel border border-white/10 shadow-xl relative odp-gmap">
    <iframe
        src="https://www.google.com/maps/d/embed?mid={{ config('services.odp_map.mymaps_id') }}&ll=-7.4367,109.2704&z=14"
        title="Peta titik ODP TRICORE DATA MEDIA"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen></iframe>
    <a href="https://www.google.com/maps/d/viewer?mid={{ config('services.odp_map.mymaps_id') }}&ll=-7.4367,109.2704&z=14" target="_blank" rel="noopener" class="odp-gmap-link">Buka Maps &rarr;</a>
</div>
<style>
    /* CSS biasa (bukan Tailwind) supaya tidak perlu rebuild Vite */
    .odp-gmap { min-height: 420px; }
    /* Bar judul bawaan My Maps (~60px) disembunyikan dengan menggeser iframe ke atas. Ubah 60px jika perlu. */
    .odp-gmap iframe { position: absolute; left: 0; top: -60px; width: 100%; height: calc(100% + 60px); border: 0; }
    .odp-gmap-link { position: absolute; z-index: 5; top: .75rem; right: .75rem; padding: .4rem .8rem; border-radius: 9999px; font-size: .75rem; font-weight: 700;
        color: #22d3ee; background: rgba(2,6,23,.9); border: 1px solid rgba(255,255,255,.12); text-decoration: none; }
    .odp-gmap-link:hover { text-decoration: underline; }
</style>
