<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\FirebaseService;
use Psy\Command\WhereamiCommand;

class DeviceController extends Controller
{
    protected FirebaseService $firebaseService;

    public function __construct()
    {
        $this->firebaseService = new FirebaseService();
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


        return view('devices.device-list', compact('dataDevices', 'device'));
    }

    public function show($device, $device_code)
    {
        [$deviceDataInfo, $deviceDataMonitoring] = $this->resolveDeviceDetail($device, $device_code);

        return view('devices.show-detail', compact('deviceDataMonitoring', 'deviceDataInfo', 'device'));
    }

    public function getDetail($device, $device_code): JsonResponse
    {
        [$deviceDataInfo, $deviceDataMonitoring] = $this->resolveDeviceDetail($device, $device_code);

        if (empty($deviceDataInfo)) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        return response()
            ->json([
                'device_code' => $device_code,
                'device_name' => $deviceDataInfo['device_name'] ?? '',
                'type' => $deviceDataInfo['type'] ?? '',
                'location' => $deviceDataInfo['location'] ?? [],
                'condition_score' => $deviceDataInfo['condition_score'] ?? 0,
                'status' => $deviceDataInfo['status'] ?? '',
                'status_label' => $deviceDataInfo['status'] ?? '',
                'recommendation' => $deviceDataInfo['recommendation'] ?? '',
                'sensors' => $deviceDataMonitoring['sensors'] ?? [],
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
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
        $deviceDataMonitoring['chart'] = $this->buildDeviceChartSeries($deviceDataMonitoring);

        return [$deviceDataInfo, $deviceDataMonitoring];
    }

    private function prepareDeviceMonitoring(array $deviceDataMonitoring): array
    {
        $latest = $deviceDataMonitoring['latest'] ?? [];
        $sensors = [];

        foreach ($latest as $sensor => $value) {
            $status = $this->getSensorStatus($sensor, (float) $value);

            $label = match (true) {
                str_contains($sensor, 'temperature') || str_contains($sensor, 'suhu') => 'Temperature',
                str_contains($sensor, 'pH') => 'pH',
                str_contains($sensor, 'turbidity') || str_contains($sensor, 'kekeruhan') => 'Kekeruhan',
                str_contains($sensor, 'do') => 'Dissolved Oxygen',
                str_contains($sensor, 'tds') => 'Total Dissolved Solids',
                str_contains($sensor, 'humidity') || str_contains($sensor, 'kelembapan') => 'Humidity',
                str_contains($sensor, 'TVOC') => 'TVOC',
                str_contains($sensor, 'CO2') || str_contains($sensor, 'CO₂') => 'CO₂',
                str_contains($sensor, 'UV') => 'UV Index',
                str_contains($sensor, 'Angin') => 'Wind Speed',
                str_contains($sensor, 'Curah') => 'Rainfall',
                default => ucfirst($sensor),
            };
            
            $sensors[] = [
                'label' => $label,
                'value' => $value,
                'unit' => $this->getSensorUnit($sensor),
                'status' => $status,
                'pct' => $this->getSensorPct($sensor, (float) $value, $status),
            ];

            $deviceDataMonitoring[$sensor]['status'] = $status;
        }

        $deviceDataMonitoring['sensors'] = $sensors;

        return $deviceDataMonitoring;
    }

    private function getSensorUnit(string $sensor): string
    {
        return match (true) {
            str_contains($sensor, 'temperature') || str_contains($sensor, 'suhu') => '°C',
            str_contains($sensor, 'pH') => '',
            str_contains($sensor, 'turbidity') || str_contains($sensor, 'kekeruhan') => 'NTU',
            str_contains($sensor, 'do') => 'mg/L',
            str_contains($sensor, 'tds') => 'ppm',
            str_contains($sensor, 'humidity') || str_contains($sensor, 'kelembapan') => '%',
            str_contains($sensor, 'TVOC') => '',
            str_contains($sensor, 'CO2') || str_contains($sensor, 'CO₂') => '',
            str_contains($sensor, 'UV') => '',
            str_contains($sensor, 'Angin') => '',
            str_contains($sensor, 'Curah') => '',
            default => '',
        };
    }

    private function getSensorPct(string $sensor, float $value, string $status): int
    {
        if ($status === 'bahaya' || $value <= 0) {
            return 0;
        }

        return match (true) {
            str_contains($sensor, 'pH') => (int) round(min(max(($value / 14) * 100, 0), 100)),
            str_contains($sensor, 'temperature') || str_contains($sensor, 'suhu') => (int) round(min(max((($value + 10) / 50) * 100, 0), 100)),
            str_contains($sensor, 'do') => (int) round(min(max(($value / 10) * 100, 0), 100)),
            str_contains($sensor, 'tds') => (int) round(min(max(($value / 1500) * 100, 0), 100)),
            str_contains($sensor, 'turbidity') => (int) round(min(max(($value / 100) * 100, 0), 100)),
            str_contains($sensor, 'humidity') => (int) round(min(max($value, 0), 100)),
            default => (int) round(min(max($value, 0), 100)),
        };
    }

    private function buildDeviceChartSeries(array $deviceDataMonitoring): array
    {
        $primaryValue = 0.0;

        foreach (($deviceDataMonitoring['sensors'] ?? []) as $sensor) {
            if (! is_numeric($sensor['value'] ?? null)) {
                continue;
            }

            $primaryValue = (float) $sensor['value'];
            if ($primaryValue > 0) {
                break;
            }
        }

        $labelsMap = [
            '6' => ['-5j', '-4j', '-3j', '-2j', '-1j', 'Sekarang'],
            '12' => ['-11j', '-9j', '-7j', '-5j', '-3j', 'Sekarang'],
            '24' => ['-20j', '-16j', '-12j', '-8j', '-4j', 'Sekarang'],
        ];

        $series = [];

        foreach ($labelsMap as $range => $labels) {
            $values = [];
            $spread = max(abs($primaryValue) * 0.08, 0.2);
            $steps = count($labels) - 1;

            for ($index = 0; $index < count($labels); $index++) {
                $progress = $steps > 0 ? $index / $steps : 0;
                $offset = ($progress - 0.5) * 2 * $spread;
                $values[] = round(max($primaryValue + $offset, 0), 2);
            }

            $series[$range] = [
                'labels' => $labels,
                'data' => $values,
            ];
        }

        return $series;
    }

    private function getSensorStatus(string $sensor, float $value): string
    {
        return match ($sensor) {
            'pH' => match (true) {
                $value < 5.5 || $value > 9.0 => 'bahaya',
                $value < 6.5 || $value > 8.5 => 'waspada',
                default => 'normal'
            },

            'do' => match (true) {
                $value < 3 => 'bahaya',
                $value < 5 => 'waspada',
                default => 'normal'
            },

            'temperature' => match (true) {
                $value < 20 || $value > 33 => 'bahaya',
                $value < 25 || $value > 30 => 'waspada',
                default => 'normal'
            },

            'tds' => match (true) {
                $value > 1000 => 'bahaya',
                $value >= 500 => 'waspada',
                default => 'normal'
            },

            'turbidity' => match (true) {
                $value > 50 => 'bahaya',
                $value >= 25 => 'waspada',
                default => 'normal'
            },

            default => 'normal'
        };
    }
}
