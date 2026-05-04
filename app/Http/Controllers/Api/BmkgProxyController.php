<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BmkgProxyController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'demo' => true,
            'source' => 'demo',
            'summary' => [
                'issue' => now()->timezone('Asia/Jakarta')->format('Y-m-d H:i').' WIB',
                'area' => 'DI Yogyakarta (contoh)',
                'weather' => 'Cerah berawan',
                'temp_min' => '24',
                'temp_max' => '31',
                'humidity_min' => '63',
                'humidity_max' => '88',
            ],
        ]);
    }
}
