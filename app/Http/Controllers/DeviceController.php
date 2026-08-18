<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\FirebaseService;
use App\Services\GroqAIService;

class DeviceController extends Controller
{
    protected FirebaseService $firebaseService;
    protected GroqAIService $aiService;

    public function __construct()
    {
        $this->firebaseService = new FirebaseService();
        $this->aiService = new GroqAIService();
    }

    public function index()
    {
        return view('devices.index');
    }

    public function devices($device)
    {
        if ($device == 'aquaviska') {
            $dataDevices = $this->firebaseService->getDeviceData('aquaviska');
        } else if ($device == 'climeet') {
            $dataDevices = $this->firebaseService->getDeviceData('climeet');
        } else {
            abort(404);
        }

        // Filter: only show approved (is_preview = true) and not deleted
        $filtered = [];
        if (is_array($dataDevices)) {
            foreach($dataDevices as $key => $d) {
                $isPreview = isset($d['is_preview']) && ($d['is_preview'] === true || $d['is_preview'] === 'true');
                $isDeleted = isset($d['is_deleted']) && ($d['is_deleted'] === true || $d['is_deleted'] === 'true');
                
                if ($isPreview && !$isDeleted) {
                    $d['node'] = $key;
                    $filtered[$key] = $d;
                }
            }
        }
        $dataDevices = $filtered;

        return view('devices.device-list', compact('dataDevices', 'device'));
    }

    public function show($device, $device_code)
    {
        [$deviceDataInfo, $deviceDataMonitoring] = $this->resolveDeviceDetail($device, $device_code);

        // Tambahkan AI Recommendations
        try {
            $aiRecommendations = $this->aiService->generateRecommendations($deviceDataInfo, $deviceDataMonitoring);
            $deviceDataMonitoring['ai_recommendations'] = [
                'summary' => $aiRecommendations['summary'] ?? 'Tidak ada ringkasan',
                'recommendations' => $aiRecommendations['recommendations'] ?? [],
                'mitigation_tips' => $aiRecommendations['mitigation_tips'] ?? [],
                'last_analysis' => $aiRecommendations['last_analysis'] ?? now()->format('H:i:s'),
                'status' => $aiRecommendations['status'] ?? 'unknown'
            ];
            // dd($deviceDataMonitoring['ai_recommendations']);
        } catch (\Exception $e) {
            \Log::error('AI Recommendation error: ' . $e->getMessage());
            $deviceDataMonitoring['ai_recommendations'] = [
                'summary' => 'AI tidak tersedia saat ini',
                'recommendations' => [],
                'mitigation_tips' => [],
                'last_analysis' => now()->format('H:i:s'),
                'status' => 'error'
            ];
        }

        return view('devices.show-detail', compact('deviceDataMonitoring', 'deviceDataInfo', 'device'));
    }

    public function getDetail($device, $device_code): JsonResponse
    {
        [$deviceDataInfo, $deviceDataMonitoring] = $this->resolveDeviceDetail($device, $device_code);

        if (empty($deviceDataInfo)) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        // Tambahkan AI recommendations ke response API untuk real-time update
        try {
            $aiRecommendations = $this->aiService->generateRecommendations($deviceDataInfo, $deviceDataMonitoring);
        } catch (\Exception $e) {
            \Log::error('AI Recommendation API error: ' . $e->getMessage());
            $aiRecommendations = [
                'summary' => 'AI tidak tersedia',
                'recommendations' => [],
                'mitigation_tips' => [],
                'last_analysis' => now()->format('H:i:s'),
                'status' => 'error'
            ];
        }

        return response()
            ->json([
                'device_code' => $device_code,
                'device_name' => $deviceDataInfo['device_name'] ?? '',
                'type' => $deviceDataInfo['type'] ?? '',
                'location' => $deviceDataInfo['location'] ?? [],
                'status' => $deviceDataInfo['status'] ?? '',
                'status_label' => $deviceDataInfo['status'] ?? '',
                'condition_score' => $deviceDataInfo['condition_score'] ?? 0,
                'recommendation' => $deviceDataInfo['recommendation'] ?? '',
                'sensors' => $deviceDataMonitoring['sensors'] ?? [],
                'ai_recommendations' => $aiRecommendations,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function storeCalibration(Request $request, $device, $device_code): JsonResponse
    {
        $calibrationData = $request->validate([
            'sensor_label' => 'required|string|max:120',
            'sensor_type' => 'required|string|max:50',
            'current_value' => 'nullable|string|max:100',
            'reference_value' => 'required|numeric',
            'offset_value' => 'nullable|numeric',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->firebaseService->setCalibration($device, $device_code, [
                'sensor_type' => $calibrationData['sensor_type'],
                'current_value' => $calibrationData['current_value'] ?? null,
                'reference_value' => (float) $calibrationData['reference_value'],
                'offset_value' => isset($calibrationData['offset_value']) ? (float) $calibrationData['offset_value'] : null,
                'notes' => $calibrationData['notes'] ?? null,
                'calibrated_at' => now()->toIso8601String(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kalibrasi berhasil dikirim ke Firebase.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update calibration data',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCalibrationField(Request $request, $device, $device_code): JsonResponse
    {
        $request->validate([
            'field' => 'required|string|max:100',
            'value' => 'required|numeric',
        ]);

        try {
            $this->firebaseService->updateCalibrationField($device, $device_code, $request->field, (float) $request->value);

            return response()->json([
                'success' => true,
                'message' => 'Nilai kalibrasi berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal memperbarui nilai kalibrasi',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCalibrationFields(Request $request, $device, $device_code): JsonResponse
    {
        $request->validate([
            'fields' => 'required|array',
        ]);

        try {
            $formattedFields = [];
            foreach ($request->fields as $key => $value) {
                $formattedFields[$key] = (float) $value;
            }

            $this->firebaseService->updateCalibrationFields($device, $device_code, $formattedFields);

            return response()->json([
                'success' => true,
                'message' => 'Nilai kalibrasi berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal memperbarui nilai kalibrasi',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    private function resolveDeviceDetail($device, $device_code): array
    {
        if ($device == 'aquaviska') {
            $deviceDataInfo = $this->firebaseService->getDeviceData('aquaviska');
            $deviceDataMonitoring = $this->firebaseService->getDataAquaviskaByDeviceCode($device_code);
        } else if ($device == 'climeet') {
            $deviceDataInfo = $this->firebaseService->getDeviceData('climeet');
            $deviceDataMonitoring = $this->firebaseService->getDataClimeetByDeviceCode($device_code);
        } else {
            abort(404);
        }

        $deviceDataInfo = collect($deviceDataInfo)->firstWhere('device_code', $device_code) ?? [];
        $deviceDataMonitoring = $this->prepareDeviceMonitoring($deviceDataMonitoring);
        $deviceDataMonitoring['type'] = $deviceDataInfo['type'] ?? ($device === 'aquaviska' ? 'AQUAVISKA' : 'CLIMEET');

        // Ambil data history untuk chart
        $deviceHistory = $this->firebaseService->getHistoryData($device, $device_code, $device);
        $deviceDataMonitoring['chart'] = $this->buildHistoryChartSeries($deviceHistory, $deviceDataMonitoring['type']);

        return [$deviceDataInfo, $deviceDataMonitoring];
    }

    private function prepareDeviceMonitoring(array $deviceDataMonitoring): array
    {
        $latest = $deviceDataMonitoring['latest'] ?? [];
        $rawVoltage = $latest['raw_voltage'] ?? [];
        $sensors = [];

        foreach ($latest as $sensor => $value) {
            if ($sensor === 'timestamp' || $sensor === 'condition_score' || $sensor === 'status' || is_array($value)) {
                continue;
            }

            $status = $this->getSensorStatus($sensor, (float) $value);
            $label = $this->getSensorLabel($sensor);
            $unit = $this->getSensorUnit($sensor);

            $sensors[] = [
                'key' => $sensor,
                'label' => $label,
                'value' => $value,
                'unit' => $unit,
                'status' => $status,
                'pct' => $this->getSensorPct($sensor, (float) $value, $status),
                'raw_voltage' => $rawVoltage[$sensor] ?? null,
            ];
        }

        $deviceDataMonitoring['sensors'] = $sensors;

        // Hitung condition score dari status sensor
        $statusBySensor = array_column($sensors, 'status');
        $deviceDataMonitoring['calculated_score'] = $this->calculateConditionScore($statusBySensor);

        return $deviceDataMonitoring;
    }

    private function getSensorUnit(string $sensor): string
    {
        $sensorLower = strtolower($sensor);

        return match (true) {
            str_contains($sensorLower, 'temperature') || str_contains($sensorLower, 'suhu') => '°C',
            str_contains($sensorLower, 'ph') => 'pH',
            str_contains($sensorLower, 'turbidity') || str_contains($sensorLower, 'kekeruhan') => 'NTU',
            str_contains($sensorLower, 'do') => 'mg/L',
            str_contains($sensorLower, 'tds') => 'ppm',
            str_contains($sensorLower, 'humidity') || str_contains($sensorLower, 'kelembapan') => '%',
            str_contains($sensorLower, 'tvoc') => 'ppb',
            str_contains($sensorLower, 'co2') || str_contains($sensorLower, 'co₂') => 'ppm',
            str_contains($sensorLower, 'uv') => 'index',
            str_contains($sensorLower, 'angin') => 'm/s',
            str_contains($sensorLower, 'curah') => 'mm',
            default => '',
        };
    }

    private function getSensorPct(string $sensor, float $value, string $status): int
    {
        $sensorLower = strtolower($sensor);

        if ($status === 'bahaya' || $value <= 0) {
            return 0;
        }

        return match (true) {
            str_contains($sensorLower, 'ph') => (int) round(min(max(($value / 14) * 100, 0), 100)),
            str_contains($sensorLower, 'temperature') || str_contains($sensorLower, 'suhu') => (int) round(min(max((($value + 10) / 50) * 100, 0), 100)),
            str_contains($sensorLower, 'do') => (int) round(min(max(($value / 10) * 100, 0), 100)),
            str_contains($sensorLower, 'tds') => (int) round(min(max(($value / 1500) * 100, 0), 100)),
            str_contains($sensorLower, 'turbidity') => (int) round(min(max(($value / 100) * 100, 0), 100)),
            str_contains($sensorLower, 'humidity') => (int) round(min(max($value, 0), 100)),
            default => (int) round(min(max($value, 0), 100)),
        };
    }

    private function buildHistoryChartSeries(array $historyData, string $deviceType): array
    {
        if (empty($historyData)) {
            return $this->buildFallbackChartSeries($deviceType);
        }

        $normalized = [];
        $allSensorKeys = [];

        foreach ($historyData as $row) {
            if (!is_array($row)) {
                continue;
            }

            $timestamp = $this->extractHistoryTimestamp($row);
            if (!$timestamp) {
                continue;
            }

            $values = $this->extractHistoryValues($row);
            $allSensorKeys = array_merge($allSensorKeys, array_keys($values));

            $normalized[] = [
                'timestamp' => $timestamp,
                'label' => $this->formatHistoryLabel($timestamp),
                'values' => $values,
            ];
        }

        if (empty($normalized)) {
            return $this->buildFallbackChartSeries($deviceType);
        }

        usort($normalized, fn($a, $b) => $a['timestamp']->timestamp - $b['timestamp']->timestamp);

        $sensorKeys = array_values(array_unique($allSensorKeys));

        // Pilih sensor utama untuk chart
        $prioritySensors = ['ph', 'do', 'temperature', 'tds', 'turbidity'];
        $primarySensor = null;
        foreach ($prioritySensors as $priority) {
            if (in_array($priority, $sensorKeys, true)) {
                $primarySensor = $priority;
                break;
            }
        }

        if (!$primarySensor && !empty($sensorKeys)) {
            $primarySensor = $sensorKeys[0];
        }

        $ranges = ['6', '12', '24'];
        $result = [];
        $chartSeries = [];

        foreach ($ranges as $range) {
            $cutoff = now()->subHours((int) $range);
            $window = array_values(array_filter($normalized, function ($row) use ($cutoff) {
                return $row['timestamp']->greaterThanOrEqualTo($cutoff);
            }));

            if (empty($window)) {
                $window = array_slice($normalized, -min(count($normalized), 6));
            }

            $labels = array_map(fn($row) => $row['label'], $window);

            $data = [];
            foreach ($window as $row) {
                $data[] = isset($row['values'][$primarySensor]) ? round($row['values'][$primarySensor], 2) : null;
            }

            $result[$range] = [
                'labels' => $labels,
                'datasets' => [[
                    'label' => $this->getSensorLabel($primarySensor),
                    'data' => $data,
                    'unit' => $this->getSensorUnit($primarySensor),
                ]],
            ];

            foreach ($sensorKeys as $sensorKey) {
                $seriesData = [];
                foreach ($window as $row) {
                    $seriesData[] = isset($row['values'][$sensorKey]) ? round($row['values'][$sensorKey], 2) : null;
                }

                $color = $this->getSensorChartColor($sensorKey);
                $chartSeries[$range][$sensorKey] = [
                    'label' => $this->getSensorLabel($sensorKey),
                    'data' => $seriesData,
                    'unit' => $this->getSensorUnit($sensorKey),
                    'borderColor' => $color['border'],
                    'backgroundColor' => $color['background'],
                ];
            }
        }

        return array_merge($result, [
            'sensor_keys' => $sensorKeys,
            'chart_series' => $chartSeries,
        ]);
    }

    private function getSensorChartColor(string $sensor): array
    {
        $sensorLower = strtolower($sensor);

        return match (true) {
            str_contains($sensorLower, 'ph') => ['border' => '#ec4899', 'background' => 'rgba(236, 72, 153, 0.12)'],
            str_contains($sensorLower, 'do') => ['border' => '#0ea5e9', 'background' => 'rgba(14, 165, 233, 0.14)'],
            str_contains($sensorLower, 'temperature') || str_contains($sensorLower, 'suhu') => ['border' => '#f97316', 'background' => 'rgba(249, 115, 22, 0.13)'],
            str_contains($sensorLower, 'tds') => ['border' => '#14b8a6', 'background' => 'rgba(20, 184, 166, 0.14)'],
            str_contains($sensorLower, 'turbidity') || str_contains($sensorLower, 'kekeruhan') => ['border' => '#8b5cf6', 'background' => 'rgba(139, 92, 246, 0.14)'],
            str_contains($sensorLower, 'humidity') || str_contains($sensorLower, 'kelembab') => ['border' => '#22c55e', 'background' => 'rgba(34, 197, 94, 0.14)'],
            str_contains($sensorLower, 'pm25') || str_contains($sensorLower, 'pm 25') => ['border' => '#64748b', 'background' => 'rgba(100, 116, 139, 0.14)'],
            str_contains($sensorLower, 'uv') => ['border' => '#facc15', 'background' => 'rgba(250, 204, 21, 0.14)'],
            str_contains($sensorLower, 'co2') => ['border' => '#a3e635', 'background' => 'rgba(163, 230, 53, 0.14)'],
            str_contains($sensorLower, 'angin') || str_contains($sensorLower, 'wind') => ['border' => '#38bdf8', 'background' => 'rgba(56, 189, 248, 0.14)'],
            str_contains($sensorLower, 'curah') || str_contains($sensorLower, 'rain') => ['border' => '#0d9488', 'background' => 'rgba(13, 148, 136, 0.14)'],
            default => ['border' => '#0ea5e9', 'background' => 'rgba(14, 165, 233, 0.12)'],
        };
    }

    private function buildFallbackChartSeries(string $deviceType): array
    {
        if ($deviceType === 'AQUAVISKA') {
            $sensors = ['ph', 'do', 'tds', 'turbidity', 'temperature'];
            $sampleData = [
                'ph' => [7.0, 7.1, 7.1, 7.2, 7.2, 7.3, 7.2],
                'do' => [5.2, 5.3, 5.4, 5.5, 5.5, 5.6, 5.5],
                'tds' => [250, 260, 270, 280, 290, 300, 295],
                'turbidity' => [5, 6, 7, 8, 9, 10, 9],
                'temperature' => [28.0, 28.2, 28.4, 28.5, 28.6, 28.7, 28.5],
            ];
        } else {
            $sensors = ['temperature', 'humidity', 'pm25', 'uv'];
            $sampleData = [
                'temperature' => [28.0, 28.4, 28.8, 29.1, 29.4, 29.7, 29.5],
                'humidity' => [65, 68, 70, 72, 74, 76, 75],
                'pm25' => [45, 48, 50, 52, 55, 58, 56],
                'uv' => [5.2, 5.5, 5.8, 6.1, 6.4, 6.7, 6.5],
            ];
        }

        $labels = ['-6j', '-5j', '-4j', '-3j', '-2j', '-1j', 'Sekarang'];
        $ranges = ['6', '12', '24'];
        $result = [];
        $chartSeries = [];

        foreach ($ranges as $range) {
            foreach ($sensors as $sensor) {
                $color = $this->getSensorChartColor($sensor);
                $fallbackSeries = [
                    'label' => $this->getSensorLabel($sensor),
                    'data' => $sampleData[$sensor] ?? [0, 0, 0, 0, 0, 0, 0],
                    'unit' => $this->getSensorUnit($sensor),
                    'borderColor' => $color['border'],
                    'backgroundColor' => $color['background'],
                ];
                $chartSeries[$range][$sensor] = $fallbackSeries;
            }
        }

        return [
            '6' => ['labels' => $labels, 'datasets' => []],
            '12' => ['labels' => ['-12j', '-10j', '-8j', '-6j', '-4j', '-2j', 'Sekarang'], 'datasets' => []],
            '24' => ['labels' => ['-24j', '-20j', '-16j', '-12j', '-8j', '-4j', 'Sekarang'], 'datasets' => []],
            'sensor_keys' => $sensors,
            'chart_series' => $chartSeries,
        ];
    }

    private function extractHistoryTimestamp(array $row): ?\Carbon\Carbon
    {
        foreach (['timestamp', 'created_at', 'time', 'date'] as $key) {
            if (!empty($row[$key])) {
                try {
                    return \Carbon\Carbon::parse($row[$key]);
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        return null;
    }

    private function formatHistoryLabel(\Carbon\Carbon $timestamp): string
    {
        return $timestamp->format('H:i');
    }

    private function extractHistoryValues(array $row): array
    {
        $values = [];
        $excludeKeys = ['timestamp', 'created_at', 'time', 'date', 'id', 'device_code', 'condition_score', 'status'];

        foreach ($row as $key => $value) {
            if (in_array($key, $excludeKeys, true)) {
                continue;
            }
            if (is_numeric($value)) {
                $values[$key] = (float) $value;
            }
        }
        return $values;
    }

    private function getSensorStatus(string $sensor, float $value): string
    {
        $sensorLower = strtolower($sensor);

        return match (true) {
            str_contains($sensorLower, 'ph') => match (true) {
                $value < 5.5 || $value > 9.0 => 'bahaya',
                $value < 6.5 || $value > 8.5 => 'waspada',
                default => 'normal'
            },
            str_contains($sensorLower, 'do') => match (true) {
                $value < 3 => 'bahaya',
                $value < 5 => 'waspada',
                default => 'normal'
            },
            str_contains($sensorLower, 'temperature') || str_contains($sensorLower, 'suhu') => match (true) {
                $value < 20 || $value > 33 => 'bahaya',
                $value < 25 || $value > 30 => 'waspada',
                default => 'normal'
            },
            str_contains($sensorLower, 'tds') => match (true) {
                $value > 1000 => 'bahaya',
                $value >= 500 => 'waspada',
                default => 'normal'
            },
            str_contains($sensorLower, 'turbidity') => match (true) {
                $value > 50 => 'bahaya',
                $value >= 25 => 'waspada',
                default => 'normal'
            },
            str_contains($sensorLower, 'pm25') => match (true) {
                $value > 150 => 'bahaya',
                $value > 75 => 'waspada',
                default => 'normal'
            },
            str_contains($sensorLower, 'uv') => match (true) {
                $value > 10 => 'bahaya',
                $value > 7 => 'waspada',
                default => 'normal'
            },
            default => 'normal'
        };
    }

    private function calculateConditionScore(array $statusBySensor): int
    {
        $bahayaCount = count(array_filter($statusBySensor, fn($v) => $v === 'bahaya'));
        $waspadaCount = count(array_filter($statusBySensor, fn($v) => $v === 'waspada'));
        $totalSensors = count($statusBySensor);

        if ($totalSensors === 0) {
            return 0;
        }

        $score = 100;
        $score -= ($bahayaCount * 25);
        $score -= ($waspadaCount * 10);

        if ($bahayaCount === 0 && $waspadaCount === 0) {
            $score = min(100, $score + 5);
        }

        return max(0, min(100, $score));
    }

    private function getSensorLabel(string $sensor): string
    {
        $sensorLower = strtolower($sensor);

        return match (true) {
            str_contains($sensorLower, 'ph') => 'pH',
            str_contains($sensorLower, 'do') => 'Dissolved Oxygen',
            str_contains($sensorLower, 'tds') => 'TDS',
            str_contains($sensorLower, 'temperature') || str_contains($sensorLower, 'suhu') => 'Temperature',
            str_contains($sensorLower, 'turbidity') || str_contains($sensorLower, 'kekeruhan') => 'Turbidity',
            str_contains($sensorLower, 'humidity') || str_contains($sensorLower, 'kelembapan') => 'Humidity',
            str_contains($sensorLower, 'tvoc') => 'TVOC',
            str_contains($sensorLower, 'co2') || str_contains($sensorLower, 'co₂') => 'CO₂',
            str_contains($sensorLower, 'uv') => 'UV Index',
            str_contains($sensorLower, 'angin') => 'Wind Speed',
            str_contains($sensorLower, 'curah') => 'Rainfall',
            default => ucfirst($sensor)
        };
    }

    public function getHistory($device, $device_code): JsonResponse
    {
        try {
            if ($device == 'aquaviska') {
                $dataHistory = $this->firebaseService->getHistoryData('aquaviska', $device_code, 'aquaviska');
            } else if ($device == 'climeet') {
                $dataHistory = $this->firebaseService->getHistoryData('climeet', $device_code, 'climeet');
            } else {
                abort(404);
            }

            $formattedHistory = $this->buildHistoryChartSeries($dataHistory, $device);

            if ($device == 'aquaviska') {
                $latestData = $this->firebaseService->getDataAquaviskaByDeviceCode($device_code);
            } else {
                $latestData = $this->firebaseService->getDataClimeetByDeviceCode($device_code);
            }

            $latestSensors = $this->prepareDeviceMonitoring($latestData)['sensors'] ?? [];

            return response()->json([
                'success' => true,
                'data' => $formattedHistory,
                'latest' => $latestSensors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch history data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
