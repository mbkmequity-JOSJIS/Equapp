<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;

class FirebaseService
{
    protected Database $database;

    public function __construct()
    {
        $this->database = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
            ->withDatabaseUri('https://equapp-5718f-default-rtdb.firebaseio.com')
            ->createDatabase();
    }

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
            \Log::error('FirebaseService Error: ' . $e->getMessage());
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
            $data = $this->database->getReference($path)->getValue();
            $dataDevices = $data['Device'] ?? [];
            return $dataDevices;
        } catch (\Exception $e) {
            \Log::error("FirebaseService Error fetching data for device '{$device}': " . $e->getMessage());
            return [];
        }
    }

    // public function getDataClimeet()
    // {
    //     return $this->database->getReference('climeet')->getValue() ?? [];
    // }
}