<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
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

    public function auth()
    {
        return $this->auth;
    }


    public function getDataAquaviska()
    {
        return $this->getRawWaterQualityData();
    }

    public function getRawWaterQualityData(): array
    {
        $paths = ['KualitasAir', 'water_quality'];

        foreach ($paths as $path) {
            $value = $this->database->getReference($path)->getValue();

            if (is_array($value) && ! empty($value)) {
                return $value;
            }
        }

        return [];
    }

    /**
     * Get latest sensor data from specific area
     */
    public function getLatestSensorData($area = 'Area-1')
    {
        return $this->database->getReference("water_quality/{$area}/latest")->getValue();
    }

    /**
     * Determine status (normal, waspada, bahaya) based on thresholds
     * @param string $key Indicator key
     * @param mixed $value Sensor value
     * @param string $type 'aqua_viska' or 'iot_climate'
     */
    public function getIndicatorStatus($key, $value, $type = 'aqua_viska')
    {
        $indicators = config("indicators.{$type}");
        
        if (!isset($indicators[$key])) {
            return 'unknown';
        }

        $thresholds = $indicators[$key]['thresholds'];

        // Check normal range
        if ($this->isInRange($value, $thresholds['normal'])) {
            return 'normal';
        }

        // Check waspada range
        if (isset($thresholds['waspada'])) {
            $waspadaRanges = is_array($thresholds['waspada'][0] ?? null) 
                ? $thresholds['waspada'] 
                : [$thresholds['waspada']];
            
            foreach ($waspadaRanges as $range) {
                if ($this->isInRange($value, $range)) {
                    return 'waspada';
                }
            }
        }

        // Check bahaya range
        if (isset($thresholds['bahaya'])) {
            $bahayaRanges = is_array($thresholds['bahaya'][0] ?? null) 
                ? $thresholds['bahaya'] 
                : [$thresholds['bahaya']];
            
            foreach ($bahayaRanges as $range) {
                if ($this->isInRange($value, $range)) {
                    return 'bahaya';
                }
            }
        }

        return 'unknown';
    }

    /**
     * Check if value is within range
     */
    private function isInRange($value, $range)
    {
        $min = $range['min'] ?? null;
        $max = $range['max'] ?? null;

        if ($min !== null && $value < $min) {
            return false;
        }

        if ($max !== null && $value > $max) {
            return false;
        }

        return true;
    }

    /**
     * Get all sensor data with status
     */
    public function getAquaviskaIndicators($area = 'Area-1')
    {
        $sensorData = $this->getLatestSensorData($area);
        $indicators = config('indicators.aqua_viska');
        $result = [];

        foreach ($indicators as $key => $config) {
            $value = $sensorData[$key] ?? null;
            $status = $this->getIndicatorStatus($key, $value, 'aqua_viska');

            $result[$key] = [
                'label' => $config['label'],
                'unit' => $config['unit'],
                'description' => $config['description'],
                'value' => $value,
                'status' => $status,
            ];
        }

        // Add timestamp
        $result['timestamp'] = $sensorData['timestamp'] ?? null;

        return $result;
    }

    /**
     * Get latest IoT CLIMATE sensor data from specific device
     */
    public function getLatestIotClimateData($deviceId = 'device_1')
    {
        return $this->database->getReference("weather_station/{$deviceId}/latest")->getValue();
    }

    /**
     * Get all IoT CLIMATE sensor data with status
     */
    public function getIotClimateIndicators($deviceId = 'device_1')
    {
        $sensorData = $this->getLatestIotClimateData($deviceId);
        $indicators = config('indicators.iot_climate');
        $result = [];

        // Mapping Firebase field names to config keys
        $fieldMapping = [
            'suhuUdara' => 'suhu_udara',
            'suhu' => 'suhu_udara',
            'kelembaban' => 'kelembaban',
            'co2' => 'co2',
            'tvoc' => 'tvoc',
            'uvIndex' => 'uv_index',
            'intensitasUV' => 'uv_index',
            'kecepatanAngin' => 'kecepatan_angin',
            'curahHujan' => 'curah_hujan',
            'intensitasHujan' => 'curah_hujan',
        ];

        foreach ($indicators as $key => $config) {
            // Try to find value from Firebase using multiple possible field names
            $value = null;
            
            // Direct key match
            if (isset($sensorData[$key])) {
                $value = $sensorData[$key];
            } else {
                // Try reverse mapping
                foreach ($fieldMapping as $firebaseField => $configKey) {
                    if ($configKey === $key && isset($sensorData[$firebaseField])) {
                        $value = $sensorData[$firebaseField];
                        break;
                    }
                }
            }

            $status = $this->getIndicatorStatus($key, $value, 'iot_climate');

            $result[$key] = [
                'label' => $config['label'],
                'unit' => $config['unit'],
                'description' => $config['description'],
                'value' => $value,
                'status' => $status,
            ];
        }

        // Add timestamp
        $result['timestamp'] = $sensorData['timestamp'] ?? null;

        return $result;
    }

    /**
     * Format threshold description for display
     * @param string $sensorKey Sensor key (temperature, ph, etc)
     * @param string $type 'aqua_viska' or 'iot_climate'
     * @return array Array with status as key and range description as value
     */
    public function getThresholdDescription($sensorKey, $type = 'aqua_viska')
    {
        $indicators = config("indicators.{$type}");
        
        if (!isset($indicators[$sensorKey])) {
            return [];
        }

        $config = $indicators[$sensorKey];
        $thresholds = $config['thresholds'];
        $unit = $config['unit'];
        $descriptions = [];

        // Format Normal range
        if (isset($thresholds['normal'])) {
            $range = $thresholds['normal'];
            $descriptions['normal'] = $this->formatRangeText($range, $unit);
        }

        // Format Waspada ranges
        if (isset($thresholds['waspada'])) {
            $waspadaRanges = is_array($thresholds['waspada'][0] ?? null) 
                ? $thresholds['waspada'] 
                : [$thresholds['waspada']];
            
            $rangeTexts = [];
            foreach ($waspadaRanges as $range) {
                $rangeTexts[] = $this->formatRangeText($range, $unit);
            }
            $descriptions['waspada'] = implode(', ', $rangeTexts);
        }

        // Format Bahaya ranges
        if (isset($thresholds['bahaya'])) {
            $bahayaRanges = is_array($thresholds['bahaya'][0] ?? null) 
                ? $thresholds['bahaya'] 
                : [$thresholds['bahaya']];
            
            $rangeTexts = [];
            foreach ($bahayaRanges as $range) {
                $rangeTexts[] = $this->formatRangeText($range, $unit);
            }
            $descriptions['bahaya'] = implode(', ', $rangeTexts);
        }

        return $descriptions;
    }

    /**
     * Format a single range to readable text
     */
    private function formatRangeText($range, $unit = '')
    {
        $min = $range['min'];
        $max = $range['max'];

        if ($min === null && $max !== null) {
            return "< {$max} {$unit}";
        } elseif ($min !== null && $max === null) {
            return "> {$min} {$unit}";
        } elseif ($min !== null && $max !== null) {
            return "{$min} - {$max} {$unit}";
        }

        return 'N/A';
    }
}
