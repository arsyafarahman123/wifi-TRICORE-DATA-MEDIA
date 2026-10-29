{{-- Pemilih mode peta: ODP_MAP_MODE=mymaps (Google My Maps asli, default) atau leaflet (peta OSM + filter/popup custom). --}}
@if (config('services.odp_map.mode') === 'leaflet')
    @include('partials.odp-map-leaflet')
@else
    @include('partials.odp-map-google')
@endif
