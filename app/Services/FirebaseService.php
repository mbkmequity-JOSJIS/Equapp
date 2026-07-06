<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Log;

use Kreait\Firebase\Contract\Auth;
    
class FirebaseService
{
    protected Database $database;
    protected Auth $auth;


    public function __construct()
    {

        $this->database = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
            ->withDatabaseUri('https://equapp-5718f-default-rtdb.firebaseio.com')
            ->createDatabase();

        $this->auth = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
            ->createAuth();
    }

    // Fungsi untuk mendapatkan data mentah dari Firebase berdasarkan jenis device
    public function getRawDataMonitoring($device): array
    {

        $path = match ($device) {
            'aquaviska' => 'water_quality',
            'climeet' => 'weather_station',
            default => null,
        };
        if (!$path) {
            return [];
        }

        try {
            $data = $this->database->getReference($path)->getValue();
            return $data ?? [];
        } catch (\Exception $e) {
            // Log error jika terjadi masalah saat mengambil data
            Log::error('FirebaseService Error: ' . $e->getMessage());
            return [];
        }
    }

    public function getDataAquaviskaByDeviceCode($deviceCode)
    {
        $data = $this->getRawDataMonitoring('aquaviska');
        return $data[$deviceCode] ?? [];
    }

    public function getDataClimeetByDeviceCode($deviceCode)
    {
        $data = $this->getRawDataMonitoring('climeet');
        return $data[$deviceCode] ?? [];
    }



    public function getDeviceData($device)
    {
        $path = match ($device) {
            'aquaviska' => 'water_quality',
            'climeet' => 'weather_station',
            default => null,
        };

        if (!$path) {
            return [];
        }

        try {
            $allData = $this->database->getReference($path)->getValue();
            
            if (!$allData || !isset($allData['devices'])) {
                return [];
            }
            
            $devicesMetadata = $allData['devices'];
            $result = [];
            
            // Cari device metadata yang TIDAK deleted dan match dengan sensor data
            foreach ($devicesMetadata as $metaKey => $metadata) {
                if (!is_array($metadata)) continue;
                
                // Skip jika is_deleted = true (ini pasti dummy)
                if (isset($metadata['is_deleted']) && $metadata['is_deleted']) {
                    continue;
                }
                
                // Skip preview/dummy device (device_01 - kita gunakan sebagai fallback saja)
                if ($metaKey === 'device_01') {
                    continue;
                }
                
                // Skip jika device_code tidak ada
                $deviceCode = $metadata['device_code'] ?? null;
                if (!$deviceCode) {
                    continue;
                }
                
                // Check apakah sensor data ada di root level
                if (!isset($allData[$deviceCode])) {
                    continue;
                }
                
                // Ini adalah device real - merge metadata dengan sensor data
                $sensorData = $allData[$deviceCode];
                
                // Jika koordinat 0,0 (tidak valid), coba gunakan koordinat dari device_01 yang punya device_code sama
                $location = $metadata['location'] ?? [];
                if (
                    (!isset($location['latitude']) || $location['latitude'] == 0) &&
                    (!isset($location['longitude']) || $location['longitude'] == 0) &&
                    isset($devicesMetadata['device_01']) &&
                    isset($devicesMetadata['device_01']['location'])
                ) {
                    $location = array_merge($location, $devicesMetadata['device_01']['location']);
                }
                
                $merged = array_merge($metadata, [
                    'sensor_data' => $sensorData,
                    'location' => $location,  // Update dengan koordinat fallback jika ada
                    '_device_key' => $metaKey  // Simpan key untuk reference
                ]);
                
                $result[$metaKey] = $merged;
            }
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error("FirebaseService Error fetching data for device '{$device}': " . $e->getMessage());
            return [];
        }
    }

    public function setCalibration(string $device, string $deviceCode, array $data)
    {
        $path = match ($device) {
            'aquaviska' => 'water_quality',
            'climeet' => 'weather_station',
            default => null,
        };

        if (! $path) {
            throw new \InvalidArgumentException('Invalid device type.');
        }

        $payload = array_diff($data, [
            'device_code' => $deviceCode,
            'updated_at' => now()->toDateTimeString(),
        ]);

        return $this->database
            ->getReference("{$path}/{$deviceCode}/Calibration/{$data['sensor_type']}")
            ->set($payload);
    }

    public function getHistoryData(string $device, string $deviceCode, string $sensorType): array
    {
        $path = match ($device) {
            'aquaviska' => 'water_quality',
            'climeet' => 'weather_station',
            default => null,
        };

        if (! $path) {
            throw new \InvalidArgumentException('Invalid device type.');
        }

        try {
            $data = $this->database
                ->getReference("{$path}/{$deviceCode}/history")
                ->getValue();

            return $data ? array_values($data) : [];
        } catch (\Exception $e) {
            Log::error("FirebaseService Error fetching history data for device '{$device}', sensor '{$sensorType}': " . $e->getMessage());
            return [];
        }
    }

    // public function getDataClimeet()
    // {
    //     return $this->database->getReference('climeet')->getValue() ?? [];
    // }
}
