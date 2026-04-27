<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    /** @var array<int, array<string, mixed>> */
    protected array $catalog = [
        1 => [
            'id' => 1,
            'name' => 'Embung Nglanggeran',
            'address' => 'Patuk, Gunungkidul, DI Yogyakarta',
            'type' => 'AQUAVISKA',
            'status' => 'normal',
            'status_label' => 'Normal',
            'condition_score' => 86,
            'recommendation' => 'Kualitas air dalam batas aman. Lanjutkan pemantauan mingguan dan pastikan sensor permukaan bersih dari biofouling.',
            'sensors' => [
                ['label' => 'pH', 'value' => '7.2', 'unit' => '', 'status' => 'normal', 'pct' => 72],
                ['label' => 'TDS', 'value' => '210', 'unit' => 'ppm', 'status' => 'normal', 'pct' => 45],
                ['label' => 'Suhu Air', 'value' => '26.4', 'unit' => '�C', 'status' => 'normal', 'pct' => 55],
                ['label' => 'Kekeruhan', 'value' => '8.1', 'unit' => 'NTU', 'status' => 'waspada', 'pct' => 62],
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
            'type' => 'IOT Climate',
            'status' => 'waspada',
            'status_label' => 'Waspada',
            'condition_score' => 58,
            'recommendation' => 'Kelembaban tinggi dan suhu mendekati ambang. Ventilasi ruang sensor dan kalibrasi sensor suhu direkomendasikan dalam 48 jam.',
            'sensors' => [
                ['label' => 'Suhu Udara', 'value' => '31.2', 'unit' => '�C', 'status' => 'waspada', 'pct' => 78],
                ['label' => 'Kelembaban', 'value' => '82', 'unit' => '%', 'status' => 'waspada', 'pct' => 82],
                ['label' => 'Tekanan', 'value' => '1009', 'unit' => 'hPa', 'status' => 'normal', 'pct' => 40],
                ['label' => 'Intensitas Cahaya', 'value' => '640', 'unit' => 'lux', 'status' => 'normal', 'pct' => 50],
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
            'type' => 'AQUAVISKA',
            'status' => 'bahaya',
            'status_label' => 'Bahaya / Offline',
            'condition_score' => 24,
            'recommendation' => 'Sensor utama tidak merespons. Periksa koneksi listrik, modul telemetry, dan lakukan restart terkontrol. Eskalasi ke tim lapangan.',
            'sensors' => [
                ['label' => 'pH', 'value' => '�', 'unit' => '', 'status' => 'bahaya', 'pct' => 0],
                ['label' => 'TDS', 'value' => '�', 'unit' => 'ppm', 'status' => 'bahaya', 'pct' => 0],
                ['label' => 'Suhu Air', 'value' => '24.1', 'unit' => '�C', 'status' => 'waspada', 'pct' => 35],
                ['label' => 'Kekeruhan', 'value' => '�', 'unit' => 'NTU', 'status' => 'bahaya', 'pct' => 0],
            ],
            'chart' => [
                '6' => ['labels' => ['-5j', '-4j', '-3j', '-2j', '-1j', 'Sekarang'], 'data' => [7.1, 6.9, 6.4, 5.8, 4.2, 0]],
                '12' => ['labels' => ['-11j', '-9j', '-7j', '-5j', '-3j', 'Sekarang'], 'data' => [7.2, 7.0, 6.8, 6.2, 5.0, 0]],
                '24' => ['labels' => ['-20j', '-16j', '-12j', '-8j', '-4j', 'Sekarang'], 'data' => [7.2, 7.1, 7.0, 6.5, 5.5, 0]],
            ],
        ],
    ];

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
}