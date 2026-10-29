<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class OdpMapController extends Controller
{
    /**
     * Sajikan data titik ODP (KML) untuk peta di landing page.
     *
     * Urutan sumber data:
     *  1. Google My Maps (sinkron otomatis, di-cache supaya tidak membebani Google)
     *  2. File cadangan public/data/odp.kml (hasil Export KML dari My Maps)
     */
    public function kml(): Response
    {
        $kml = Cache::get('odp_kml');

        if ($kml === null) {
            $kml = $this->fetchFromMyMaps();

            // Sukses di-cache 6 jam; gagal di-cache 10 menit agar halaman tidak lambat.
            Cache::put('odp_kml', $kml ?? '', $kml ? now()->addHours(6) : now()->addMinutes(10));
        }

        if (! $kml) {
            $fallback = public_path('data/odp.kml');
            $kml = File::exists($fallback) ? File::get($fallback) : null;
        }

        if (! $kml) {
            return response('Data ODP belum tersedia.', 404);
        }

        return response($kml, 200, [
            'Content-Type' => 'application/vnd.google-earth.kml+xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=900',
        ]);
    }

    private function fetchFromMyMaps(): ?string
    {
        $mapId = config('services.odp_map.mymaps_id');

        if (! $mapId) {
            return null;
        }

        try {
            $response = Http::timeout(6)->get('https://www.google.com/maps/d/kml', [
                'mid' => $mapId,
                'forcekml' => 1,
            ]);
        } catch (\Throwable) {
            return null;
        }

        // Pastikan benar-benar KML yang berisi titik, bukan halaman login/error.
        if ($response->successful() && str_contains($response->body(), '<Placemark')) {
            return $response->body();
        }

        return null;
    }
}
