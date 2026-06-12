@extends('layouts.app')
{{-- resources/views/locations/show.blade.php --}}
@section('title', 'Detail Lokasi - ' . $location['name'])
@section('style')
    {{-- style additional --}}
    <style>
        /* CONTENT */

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            color: #64748b;
            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: #6ee89a;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .bc-sep {
            color: #cbd5e1;
        }

        /* Detail Hero */
        .detail-hero {
            margin-bottom: 40px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .detail-hero-image {
            position: relative;
            height: 300px;
            background-size: cover;
            background-position: center;
        }

        .detail-hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.1));
        }

        .detail-hero-info {
            position: absolute;
            bottom: 30px;
            left: 30px;
            color: white;
            z-index: 2;
        }

        .detail-hero-info h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .detail-hero-info p {
            font-size: 18px;
            opacity: 0.9;
        }

        .device-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.9);
            color: #1e293b;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .condition-score-card {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .score-label {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .score-value {
            font-size: 36px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .score-max {
            font-size: 18px;
            color: #64748b;
        }

        .score-status {
            font-size: 16px;
            font-weight: 600;
            color: {{ $location['status'] === 'normal' ? '#22c55e' : ($location['status'] === 'waspada' ? '#f59e0b' : '#ef4444') }};
        }

        /* Sensor Section */
        .sensor-section {
            margin-bottom: 40px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .live-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #dcfce7;
            color: #166534;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .pulse {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .sensor-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
            transition: all 0.2s;
        }

        .sensor-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .sensor-icon-wrap {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 20px;
        }

        .sensor-icon-wrap.ph {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .sensor-icon-wrap.tds {
            background: #fef3c7;
            color: #d97706;
        }

        .sensor-icon-wrap.suhuair {
            background: #fee2e2;
            color: #dc2626;
        }

        .sensor-icon-wrap.kekeruhan {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .sensor-icon-wrap.suhudara {
            background: #ecfdf5;
            color: #059669;
        }

        .sensor-icon-wrap.kelembaban {
            background: #f0f9ff;
            color: #0284c7;
        }

        .sensor-icon-wrap.tekanan {
            background: #fefce8;
            color: #ca8a04;
        }

        .sensor-icon-wrap.intensitasc {
            background: #fff7ed;
            color: #ea580c;
        }

        .sensor-info {
            text-align: center;
        }

        .sensor-label {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .sensor-value {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .sensor-unit {
            font-size: 16px;
            color: #6b7280;
            margin-left: 4px;
        }

        .sensor-bar-wrap {
            width: 100%;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .sensor-bar {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .sensor-bar.good {
            background: #22c55e;
        }

        .sensor-bar.medium {
            background: #f59e0b;
        }

        .sensor-bar.bad {
            background: #ef4444;
        }

        .sensor-status {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
            text-transform: uppercase;
        }

        .sensor-status.good {
            background: #dcfce7;
            color: #166534;
        }

        .sensor-status.medium {
            background: #fef3c7;
            color: #92400e;
        }

        .sensor-status.bad {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Chart Section */
        .chart-section {
            margin-bottom: 40px;
        }

        .chart-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .chart-tab {
            padding: 10px 20px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #475569;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
        }

        .chart-tab.active {
            background: #6ee89a;
            color: white;
            border-color: #6ee89a;
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
            min-height: 360px;
        }

        .chart-container canvas {
            display: block;
            width: 100% !important;
            height: 320px !important;
        }

        /* Bottom Grid */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .recommendation-card,
        .report-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        .rec-header,
        .rep-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .rec-header h3,
        .rep-header h3 {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
        }

        .rec-header small,
        .rep-header small {
            font-size: 12px;
            color: #64748b;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 12px;
        }

        .rec-items {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .rec-item {
            display: flex;
            gap: 12px;
            padding: 15px;
            border-radius: 8px;
            align-items: flex-start;
        }

        .rec-item.good {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
        }

        .rec-item.medium {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
        }

        .rec-item.warn {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
        }

        .rec-icon {
            color: #6b7280;
            margin-top: 2px;
        }

        .rec-content strong {
            display: block;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .rec-content p {
            color: #4b5563;
            font-size: 14px;
        }

        .rep-content {
            color: #4b5563;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .rep-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 14px;
        }

        .wa-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #25d366;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .wa-button:hover {
            background: #128c7e;
            transform: translateY(-1px);
        }

        .calibration-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            border: none;
            background: #0ea5e9;
            color: white;
            padding: 12px 14px;
            border-radius: 10px;
            font-weight: 600;
            margin-top: 16px;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .calibration-button:hover {
            background: #0284c7;
            transform: translateY(-1px);
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            display: grid;
            place-items: center;
            padding: 20px;
            z-index: 100;
        }

        .modal-overlay.hidden {
            display: none;
        }

        .modal {
            width: min(1100px, 100%);
            max-height: 95vh;
            overflow-y: auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.18);
            padding: 28px;
            position: relative;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .modal-header h2 {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .modal-header p {
            color: #475569;
            font-size: 14px;
        }

        .modal-close {
            border: none;
            border-radius: 999px;
            background: #f1f5f9;
            color: #0f172a;
            width: 44px;
            height: 44px;
            cursor: pointer;
            display: grid;
            place-items: center;
            font-size: 16px;
        }

        .modal-section {
            margin-bottom: 24px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #f8fafc;
        }

        .modal-section h3 {
            margin-bottom: 16px;
            font-size: 18px;
            color: #1e293b;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }

        .form-grid label {
            display: flex;
            flex-direction: column;
            font-size: 14px;
            color: #334155;
            gap: 8px;
        }

        .form-grid input,
        .form-grid select,
        .form-grid textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-grid input:focus,
        .form-grid select:focus,
        .form-grid textarea:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .button {
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .button-primary {
            background: #16a34a;
            color: white;
        }

        .button-secondary {
            background: #e2e8f0;
            color: #334155;
        }
    </style>
@endsection

@section('content')
    <!-- MAIN CONTENT -->
    <main class="content">
        <div class="main-content">
            <div class="page-header">
                <div class="breadcrumb">
                    <a href="{{ route('lokasi') }}"><i class="fas fa-arrow-left"></i> Kembali ke Lokasi</a>
                    <span class="bc-sep">/</span>
                    <span>{{ $location['name'] }}</span>
                </div>
            </div>

            <div class="detail-hero">
                <div class="detail-hero-image"
                    style="background-image: url('{{ asset('storage/img_loc/' . $location['image']) }}');">
                    <div class="detail-hero-overlay"></div>
                    <div class="detail-hero-info">
                        <div class="device-badge {{ strtolower(str_replace(' ', '_', $location['type'])) }}">
                            <i class="fas fa-{{ $location['type'] === 'AQUAVISKA' ? 'water' : 'cloud-sun' }}"></i>
                            {{ $location['type'] }}
                        </div>
                        <h1>{{ $location['name'] }}</h1>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $location['address'] }}</p>
                    </div>
                    <div class="condition-score-card">
                        <p class="score-label">Skor Kondisi</p>
                        <p class="score-value">{{ $location['condition_score'] }}<span class="score-max">/100</span></p>
                        <p class="score-status">{{ $location['status_label'] }}</p>
                    </div>
                </div>
            </div>

            <div class="sensor-section">
                <div class="section-header">
                    <h2><i class="fas fa-tachometer-alt"></i> Sensor & Perangkat</h2>
                    <div class="live-badge">
                        <div class="pulse"></div>
                        Live Data
                    </div>
                </div>
                <div class="sensor-grid">
                    @foreach ($location['sensors'] as $sensor)
                        <div class="sensor-card">
                            <div
                                class="sensor-icon-wrap {{ strtolower(str_replace([' ', '/', '(', ')'], ['', '', '', ''], $sensor['label'])) }}">
                                @php
                                    $label = $sensor['label'];
                                    $icon = 'question';
                                    if (str_contains($label, 'Suhu')) {
                                        $icon = 'thermometer-half';
                                    } elseif (str_contains($label, 'pH')) {
                                        $icon = 'flask';
                                    } elseif (str_contains($label, 'Kekeruhan')) {
                                        $icon = 'eye';
                                    } elseif (str_contains($label, 'Dissolved Oxygen')) {
                                        $icon = 'wind';
                                    } elseif (str_contains($label, 'TDS')) {
                                        $icon = 'tint';
                                    } elseif (str_contains($label, 'Kelembapan')) {
                                        $icon = 'droplet';
                                    } elseif (str_contains($label, 'TVOC')) {
                                        $icon = 'cloud';
                                    } elseif (str_contains($label, 'CO2') || str_contains($label, 'CO₂')) {
                                        $icon = 'cloud';
                                    } elseif (str_contains($label, 'UV')) {
                                        $icon = 'sun';
                                    } elseif (str_contains($label, 'Angin')) {
                                        $icon = 'wind';
                                    } elseif (str_contains($label, 'Curah')) {
                                        $icon = 'cloud-showers-heavy';
                                    }
                                @endphp
                                <i class="fas fa-{{ $icon }}"></i>
                            </div>
                            <div class="sensor-info">
                                <p class="sensor-label">{{ $sensor['label'] }}</p>
                                <p class="sensor-value">{{ $sensor['value'] }}<span
                                        class="sensor-unit">{{ $sensor['unit'] }}</span></p>
                                <div class="sensor-bar-wrap">
                                    <div class="sensor-bar {{ $sensor['status'] === 'normal' ? 'good' : ($sensor['status'] === 'waspada' ? 'medium' : 'bad') }}"
                                        style="width: {{ (int) $sensor['pct'] }}%"></div>
                                </div>
                                <span
                                    class="sensor-status {{ $sensor['status'] === 'normal' ? 'good' : ($sensor['status'] === 'waspada' ? 'medium' : 'bad') }}">{{ ucfirst($sensor['status']) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="calibrationFormModal" class="modal-overlay hidden">
                <div class="modal">
                    <div class="modal-header">
                        <div>
                            <h2><i class="fas fa-flask"></i> Form Kalibrasi Sensor</h2>
                            <p>Pilih sensor yang ingin dikalibrasi, lalu isi data kalibrasi sesuai sensor.</p>
                        </div>
                        <button type="button" class="modal-close" onclick="closeCalibrationForm()"><i class="fas fa-times"></i></button>
                    </div>
                    <form id="calibrationForm" action="#" method="post" onsubmit="event.preventDefault(); submitCalibrationForm();">
                        <div class="modal-section">
                            <h3>Pilih Sensor</h3>
                            <div class="form-grid">
                                <label>Sensor
                                    <select name="sensor_select" id="sensor_select" onchange="showCalibrationFields()" required>
                                        <option value="">-- Pilih Sensor --</option>
                                        <option value="ph">pH</option>
                                        <option value="do">Dissolved Oxygen (DO)</option>
                                        <option value="tds">TDS</option>
                                        <option value="temperature">Suhu</option>
                                        <option value="turbidity">Turbidity</option>
                                    </select>
                                </label>
                            </div>
                            <h3>Parameter Kalibrasi</h3>
                            <div class="form-grid">
                                <div id="ph_fields" style="display:none">
                                    <label>pH - Voltase PH 4<input type="number" name="ph_4" step="0.001" placeholder="Nilai voltase PH 4"></label>
                                    <label>pH - Voltase PH 6<input type="number" name="ph_6" step="0.001" placeholder="Nilai voltase PH 6"></label>
                                    <label>pH - Voltase PH 9<input type="number" name="ph_9" step="0.001" placeholder="Nilai voltase PH 9"></label>
                                </div>
                                <div id="do_fields" style="display:none">
                                    <label>DO (Nilai Voltase)<input type="number" name="do_value" step="0.001" placeholder="Nilai voltase DO"></label>
                                </div>
                                <div id="tds_fields" style="display:none">
                                    <label>TDS (K Values)<input type="number" name="tds_k_value" step="0.001" placeholder="Input nilai K Values"></label>
                                </div>
                                <div id="temperature_fields" style="display:none">
                                    <label>Suhu (Offset)<input type="number" name="temperature_offset" step="0.001" placeholder="Nilai offset suhu"></label>
                                </div>
                                <div id="turbidity_fields" style="display:none">
                                    <label>Turbidity (Offset)<input type="number" name="turbidity_offset" step="0.001" placeholder="Nilai offset turbidity"></label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="button button-secondary" onclick="closeCalibrationForm()">Batal</button>
                            <button type="submit" class="button button-primary">Simpan Kalibrasi</button>
                        </div>
                    </form>
                </div>
                <script>
                function showCalibrationFields() {
                    var selected = document.getElementById('sensor_select').value;
                    var fields = ['ph', 'do', 'tds', 'temperature', 'turbidity'];
                    fields.forEach(function(f) {
                        document.getElementById(f + '_fields').style.display = (selected === f) ? '' : 'none';
                    });
                }
                </script>
            </div>

            <div class="chart-section" x-data="locationCharts(@js($location))">
                <div class="section-header">
                    <h2><i class="fas fa-chart-line"></i> Grafik Tren Data Sensor</h2>
                </div>
                <div class="chart-tabs">
                    <button type="button" @click="range = '6'"
                        :class="range === '6' ? 'chart-tab active' : 'chart-tab'">6 Jam</button>
                    <button type="button" @click="range = '12'"
                        :class="range === '12' ? 'chart-tab active' : 'chart-tab'">12 Jam</button>
                    <button type="button" @click="range = '24'"
                        :class="range === '24' ? 'chart-tab active' : 'chart-tab'">24 Jam</button>
                </div>
                <div class="chart-container">
                    <canvas x-ref="chartCanvas"></canvas>
                </div>
            </div>

            @if ($location['type'] === 'AQUAVISKA')
                <div style="position: fixed; bottom: 40px; right: 40px; z-index: 50;">
                    <button type="button" class="calibration-button" style="width: 180px;"
                        onclick="openCalibrationForm()">
                        <i class="fas fa-vial"></i> Form Kalibrasi
                    </button>
                </div>
            @endif

            <div class="bottom-grid">
                <div class="recommendation-card">
                    <div class="rec-header">
                        <i class="fas fa-lightbulb"></i>
                        <h3>Rekomendasi</h3>
                        <small>{{ $location['status_label'] }}</small>
                    </div>
                    <div class="rec-items">
                        <div
                            class="rec-item {{ $location['status'] === 'normal' ? 'good' : ($location['status'] === 'waspada' ? 'medium' : 'warn') }}">
                            <i class="fas fa-info-circle rec-icon"></i>
                            <div class="rec-content">
                                <strong>{{ $location['status'] === 'normal' ? 'Kondisi Baik' : ($location['status'] === 'waspada' ? 'Perlu Perhatian' : 'Perlu Tindakan Segera') }}</strong>
                                <p>{{ $location['recommendation'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="report-card">
                    <div class="rep-header">
                        <i class="fab fa-whatsapp"></i>
                        <h3>Laporkan Masalah</h3>
                        <small>WhatsApp</small>
                    </div>
                    <div class="rep-content">
                        Jika Anda menemukan masalah atau ingin melaporkan kondisi lokasi ini, hubungi admin melalui
                        WhatsApp.
                        <div class="rep-info">
                            <strong>Lokasi:</strong> {{ $location['name'] }}<br>
                            <strong>Status:</strong> {{ $location['status_label'] }}<br>
                            <strong>Skor:</strong> {{ $location['condition_score'] }}/100
                        </div>
                    </div>
                    <a href="https://wa.me/{{ config('equapp.admin_whatsapp') }}?text={{ rawurlencode('Halo Admin EQUITY UP, saya ingin melaporkan kondisi di ' . $location['name'] . ' dengan status ' . $location['status_label'] . '.') }}"
                        target="_blank" class="wa-button">
                        <i class="fab fa-whatsapp"></i>
                        Kirim Laporan
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        function openCalibrationForm() {
            const modal = document.getElementById('calibrationFormModal');
            const form = document.getElementById('calibrationForm');
            modal.classList.remove('hidden');
            form.reset();
        }

        function closeCalibrationForm() {
            const modal = document.getElementById('calibrationFormModal');
            modal.classList.add('hidden');
        }

        function submitCalibrationForm() {
            const modal = document.getElementById('calibrationFormModal');
            alert('Form kalibrasi telah disimpan (simulasi UI). Backend dapat ditambahkan kemudian.');
            modal.classList.add('hidden');
        }
    </script>
@endsection
