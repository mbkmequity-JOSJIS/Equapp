<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class BmkgProxyController extends Controller
{
    /**
     * Proxy BMKG DigitalForecast agar fetch dari browser (Alpine) tidak terblokir CORS.
     * Data asli berasal dari endpoint BMKG di config/equapp.php.
     */
    public function __invoke(): JsonResponse
    {
        $url = config('equapp.bmkg_forecast_url');

        try {
            $response = Http::timeout(12)->acceptJson()->get($url);

            if ($response->successful()) {
                return response()->json([
                    'ok' => true,
                    'demo' => false,
                    'source' => $url,
                    'data' => $response->json(),
                ]);
            }
        } catch (\Throwable $e) {
            //
        }

        return response()->json([
            'ok' => true,
            'demo' => true,
            'source' => $url,
            'message' => 'Menggunakan ringkasan demo � setel EQUAPP_BMKG_URL atau periksa koneksi ke BMKG.',
            'summary' => [
                'issue' => now()->timezone('Asia/Jakarta')->format('Y-m-d H:i') . ' WIB',
                'area' => 'DI Yogyakarta (contoh)',
                'weather' => 'Cerah berawan',
                'temp_min' => '24',
                'temp_max' => '31',
                'humidity_min' => '65',
                'humidity_max' => '90',
            ],
        ]);
    }
}
