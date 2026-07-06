<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Services\FirebaseService;

class LocationController extends Controller
{
    protected array $devices = [];
    
    public function __construct(protected FirebaseService $firebase)
    {
        $this->loadDevices();
    }
    
    protected function loadDevices(): void
    {
        try {
            // Ambil data real dari Firebase
            $aquaviska = $this->firebase->getDeviceData('aquaviska') ?? [];
            $climeet = $this->firebase->getDeviceData('climeet') ?? [];
            
            // Format aquaviska devices dengan type aquaviska
            $formattedAquaviska = $this->formatDevicesForMap($aquaviska, 'aquaviska');
            
            // Format climeet devices dengan type climeet
            $formattedClimeet = $this->formatDevicesForMap($climeet, 'climeet');
            
            // Gabungkan hasil
            $this->devices = array_merge($formattedAquaviska, $formattedClimeet);
            
        } catch (\Exception $e) {
            \Log::error('Failed to load devices: ' . $e->getMessage());
            $this->devices = [];
        }
    }
    
    protected function formatDevicesForMap(array $devices, string $deviceType = 'aquaviska'): array
    {
        $formatted = [];

        foreach ($devices as $deviceKey => $device) {
            // Skip jika bukan array
            if (!is_array($device)) {
                continue;
            }

            // Ekstrak location dari metadata device
            $loc = $device['location'] ?? [];

            // Ekstrak koordinat dari berbagai kemungkinan key
            $lat = $loc['latitude'] ?? $loc['lat'] ?? null;
            $lng = $loc['longitude'] ?? $loc['lon'] ?? null;

            // Convert string ke float dan check valid
            $lat = $lat ? (float) $lat : null;
            $lng = $lng ? (float) $lng : null;

            // Skip jika tidak ada koordinat atau koordinat 0
            if ($lat === null || $lng === null || ($lat === 0.0 && $lng === 0.0)) {
                continue;
            }

            // Ekstrak sensor data
            $sensorData = $device['sensor_data'] ?? [];
            
            // Ekstrak latest readings
            $latest = [];
            if (isset($sensorData['latest']) && is_array($sensorData['latest'])) {
                $latest = $sensorData['latest'];
            } elseif (isset($sensorData['history']) && is_array($sensorData['history'])) {
                // Ambil entry paling baru dari history
                $mostRecent = null;
                foreach ($sensorData['history'] as $dateEntry) {
                    if (!is_array($dateEntry)) continue;
                    foreach ($dateEntry as $timeEntry) {
                        if (!is_array($timeEntry)) continue;
                        if ($mostRecent === null) {
                            $mostRecent = $timeEntry;
                        } elseif (isset($timeEntry['timestamp']) && isset($mostRecent['timestamp'])) {
                            if (strtotime($timeEntry['timestamp']) > strtotime($mostRecent['timestamp'])) {
                                $mostRecent = $timeEntry;
                            }
                        }
                    }
                }
                if ($mostRecent) $latest = $mostRecent;
            }

            // Format data device
            $formatted[] = [
                'id' => $device['id'] ?? $deviceKey,
                'device_code' => $device['device_code'] ?? $deviceKey,
                'device_name' => $device['device_name'] ?? $device['name'] ?? 'Unknown',
                'type' => $deviceType,
                'status' => $device['status'] ?? 'offline',
                'condition_score' => $device['condition_score'] ?? $device['score'] ?? 0,
                'location' => [
                    'name' => $loc['name'] ?? '-',
                    'address' => $loc['address'] ?? '-',
                    'city' => $loc['city'] ?? '-',
                    'province' => $loc['province'] ?? '-',
                    'latitude' => $lat,
                    'longitude' => $lng,
                ],
                'latest_data' => is_array($latest) ? $latest : [],
            ];
        }

        return $formatted;
    }
    
    public function index(): View
    {
        return view('locations.index', [
            'devices' => $this->devices
        ]);
    }
    
    /**
     * Get device locations as JSON for API
     */
    public function getLocations(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'devices' => $this->devices,
            'total' => count($this->devices)
        ]);
    }
    
    /**
     * Get device location by ID
     */
    public function getLocation($id): \Illuminate\Http\JsonResponse
    {
        $device = collect($this->devices)->firstWhere('device_code', $id);
        
        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'device' => $device
        ]);
    }

    /**
     * Debug: Get raw Firebase data (untuk verifikasi)
     */
    public function debugFirebaseData(): \Illuminate\Http\JsonResponse
    {
        try {
            $aquaviska = $this->firebase->getDeviceData('aquaviska') ?? [];
            $climeet = $this->firebase->getDeviceData('climeet') ?? [];
            
            return response()->json([
                'success' => true,
                'raw_aquaviska' => $aquaviska,
                'raw_climeet' => $climeet,
                'total_aquaviska' => count($aquaviska),
                'total_climeet' => count($climeet),
                'devices_processed' => $this->devices,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}