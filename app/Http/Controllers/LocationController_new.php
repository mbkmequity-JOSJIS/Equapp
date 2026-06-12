<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\FirebaseService;

class LocationController extends Controller
{
    protected FirebaseService $firebase;

    /** @var array<int, array<string, mixed>> */
    protected array $catalog = [];

    // Dependency Injection untuk FirebaseService
    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;

        $this->catalog = $this->buildCatalog($this->firebase->getRawWaterQualityData());
    }

    /**
     * Build lokasi catalog from latest Firebase snapshot.
     *
     * @param array<string, mixed> $aquaviskaData
     * @return array<int, array<string, mixed>>
     */
    protected function buildCatalog(array $aquaviskaData): array
    {
        return [
            1 => [
                'id' => 1,
                'name' => 'Embung Nglanggeran',
                'address' => 'Patuk, Gunungkidul, DI Yogyakarta',
                'lat' => -7.897071,
                'lng' => 110.370529,
                'type' => 'AQUAVISKA',
                'image' => 'dummy_loc (1).jpg',
                'status' => 'normal',
                'status_label' => 'Normal',
                'condition_score' => 86,
                'recommendation' => 'Kualitas air dalam batas aman. Lanjutkan pemantauan mingguan dan pastikan sensor permukaan bersih dari biofouling.',
                'sensors' => [
                    ['label' => 'Suhu Air', 'value' => $aquaviskaData['Area-1']['latest']['temperature'] ?? null, 'unit' => '°C', 'status' => 'normal', 'pct' => 55],
                    ['label' => 'pH', 'value' => $aquaviskaData['Area-1']['latest']['ph'] ?? null, 'unit' => '', 'status' => 'normal', 'pct' => 72],
                    ['label' => 'Kekeruhan', 'value' => $aquaviskaData['Area-1']['latest']['turbidity'] ?? null, 'unit' => 'NTU', 'status' => 'waspada', 'pct' => 62],
                    ['label' => 'Dissolved Oxygen (DO)', 'value' => $aquaviskaData['Area-1']['latest']['do'] ?? null, 'unit' => 'mg/L', 'status' => 'normal', 'pct' => 50],
                    ['label' => 'Total Dissolved Solids (TDS)', 'value' => $aquaviskaData['Area-1']['latest']['tds'] ?? null, 'unit' => 'ppm', 'status' => 'normal', 'pct' => 45],
                ],
                'chart' => [
                    '6' => ['labels' => ['-5j', '-4j', '-3j', '-2j', '-1j', 'Sekarang'], 'data' => [7.0, 7.1, 7.15, 7.2, 7.18, 7.2]],
                    '12' => ['labels' => ['-11j', '-9j', '-7j', '-5j', '-3j', 'Sekarang'], 'data' => [6.95, 7.0, 7.05, 7.1, 7.15, 7.2]],
                    '24' => ['labels' => ['-20j', '-16j', '-12j', '-8j', '-4j', 'Sekarang'], 'data' => [6.9, 6.95, 7.0, 7.05, 7.1, 7.2]],
                ],
            ],
            2 => [
                'id' => 2,
                'name' => 'Stasiun Klimatologi Kampus',
                'address' => 'Kampus Utara, Semarang',
                'lat' => -7.042214,
                'lng' => 110.402611,
                'type' => 'IOT Climate',
                'image' => 'dummy_loc (3).jpg',
                'status' => 'waspada',
                'status_label' => 'Waspada',
                'condition_score' => 58,
                'recommendation' => 'Kelembaban tinggi dan suhu mendekati ambang. Ventilasi ruang sensor dan kalibrasi sensor suhu direkomendasikan dalam 48 jam.',
                'sensors' => [
                    ['label' => 'Suhu Udara', 'value' => '31.2', 'unit' => '°C', 'status' => 'waspada', 'pct' => 78],
                    ['label' => 'Kelembapan', 'value' => '82', 'unit' => '% RH', 'status' => 'waspada', 'pct' => 82],
                    ['label' => 'TVOC', 'value' => '0.34', 'unit' => 'mg/m³', 'status' => 'waspada', 'pct' => 68],
                    ['label' => 'CO₂', 'value' => '560', 'unit' => 'ppm', 'status' => 'waspada', 'pct' => 72],
                    ['label' => 'UV Index', 'value' => '6', 'unit' => '', 'status' => 'waspada', 'pct' => 60],
                    ['label' => 'Kecepatan Angin', 'value' => '4.2', 'unit' => 'm/s', 'status' => 'normal', 'pct' => 42],
                    ['label' => 'Curah Hujan', 'value' => '1.8', 'unit' => 'mm', 'status' => 'normal', 'pct' => 18],
                ],
                'chart' => [
                    '6' => ['labels' => ['-5j', '-4j', '-3j', '-2j', '-1j', 'Sekarang'], 'data' => [29.5, 30.0, 30.4, 30.8, 31.0, 31.2]],
                    '12' => ['labels' => ['-11j', '-9j', '-7j', '-5j', '-3j', 'Sekarang'], 'data' => [28.8, 29.2, 29.6, 30.0, 30.6, 31.2]],
                    '24' => ['labels' => ['-20j', '-16j', '-12j', '-8j', '-4j', 'Sekarang'], 'data' => [27.5, 28.2, 29.0, 29.8, 30.5, 31.2]],
                ],
            ],
            3 => [
                'id' => 3,
                'name' => 'Daerah Irigasi Tegal',
                'address' => 'Saluran primer D.I. Tegal',
                'lat' => -6.855194,
                'lng' => 108.774361,
                'type' => 'AQUAVISKA',
                'image' => 'dummy_loc (2).jpg',
                'status' => 'bahaya',
                'status_label' => 'Bahaya / Offline',
                'condition_score' => 24,
                'recommendation' => 'Sensor utama tidak merespons. Periksa koneksi listrik, modul telemetry, dan lakukan restart terkontrol. Eskalasi ke tim lapangan.',
                'sensors' => [
                    ['label' => 'Suhu Air', 'value' => '24.1', 'unit' => '°C', 'status' => 'waspada', 'pct' => 35],
                    ['label' => 'pH', 'value' => '', 'unit' => '', 'status' => 'bahaya', 'pct' => 0],
                    ['label' => 'Kekeruhan', 'value' => '–', 'unit' => 'NTU', 'status' => 'bahaya', 'pct' => 0],
                    ['label' => 'Dissolved Oxygen (DO)', 'value' => '–', 'unit' => 'mg/L', 'status' => 'bahaya', 'pct' => 0],
                    ['label' => 'Total Dissolved Solids (TDS)', 'value' => '–', 'unit' => 'ppm', 'status' => 'bahaya', 'pct' => 0],
                ],
                'chart' => [
                    '6' => ['labels' => ['-5j', '-4j', '-3j', '-2j', '-1j', 'Sekarang'], 'data' => [7.1, 6.9, 6.4, 5.8, 4.2, 0]],
                    '12' => ['labels' => ['-11j', '-9j', '-7j', '-5j', '-3j', 'Sekarang'], 'data' => [7.2, 7.0, 6.8, 6.2, 5.0, 0]],
                    '24' => ['labels' => ['-20j', '-16j', '-12j', '-8j', '-4j', 'Sekarang'], 'data' => [7.2, 7.1, 7.0, 6.5, 5.5, 0]],
                ],
            ],
        ];
    }

    public function index(): View
    {
        $locations = array_values($this->catalog);

        return view('locations.index', compact('locations'));
    }

    public function show(int $id): View|RedirectResponse
    {
        if (! isset($this->catalog[$id])) {
            return redirect()->route('locations.index')->with('error', 'Lokasi tidak ditemukan.');
        }

        $location = $this->catalog[$id];

        return view('locations.show', compact('location'));
    }

    /**
     * API endpoint: Ambil data sensor terkini untuk partial refresh
     */
    public function getSensorData()
    {
        $catalog = $this->buildCatalog($this->firebase->getRawWaterQualityData());
        $data = [];

        foreach ($catalog as $loc) {
            $data[] = [
                'id' => $loc['id'],
                'name' => $loc['name'],
                'condition_score' => $loc['condition_score'],
                'status' => $loc['status'],
                'status_label' => $loc['status_label'],
                'sensors' => $loc['sensors'] ?? [],
            ];
        }

        return response()
            ->json($data)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * API endpoint: Ambil detail lokasi spesifik dengan data Firebase terkini
     */
    public function getLocationDetail(int $id)
    {
        if (! isset($this->catalog[$id])) {
            return response()->json(['error' => 'Location not found'], 404);
        }

        $catalog = $this->buildCatalog($this->firebase->getRawWaterQualityData());

        if (! isset($catalog[$id])) {
            return response()->json(['error' => 'Location not found'], 404);
        }

        $loc = $catalog[$id];

        return response()
            ->json([
                'id' => $loc['id'],
                'name' => $loc['name'],
                'condition_score' => $loc['condition_score'],
                'status' => $loc['status'],
                'status_label' => $loc['status_label'],
                'sensors' => $loc['sensors'] ?? [],
                'recommendation' => $loc['recommendation'] ?? '',
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }
}
