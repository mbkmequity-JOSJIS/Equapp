@extends('layouts.app')

@section('title', $location['name'])

@section('content')
<style>
    .sidebar {
        background: white !important;
        color: #333 !important;
    }
    .sidebar a {
        color: #333 !important;
    }
    .sidebar a:hover,
    .sidebar a.active {
        color: #6ee89a !important;
    }
    .profile-icon {
        background: #6ee89a !important;
    }
</style>
    <div class="main-content">
        <div class="page-header">
            <div class="breadcrumb">
                <a href="{{ route('lokasi') }}"><i class="fas fa-arrow-left"></i> Kembali ke Lokasi</a>
                <span class="bc-sep">/</span>
                <span>{{ $location['name'] }}</span>
            </div>
        </div>

        <div class="detail-hero">
            <div class="detail-hero-image" style="background-image: url('https://via.placeholder.com/800x240/0a0f1e/f1f5f9?text={{ urlencode($location['name']) }}');">
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
                        <div class="sensor-icon-wrap {{ strtolower(str_replace([' ', '/', '(', ')'], ['', '', '', ''], $sensor['label'])) }}">
                            <i class="fas fa-{{ $sensor['label'] === 'Suhu' ? 'thermometer-half' : ($sensor['label'] === 'pH' ? 'flask' : ($sensor['label'] === 'Kekeruhan' ? 'eye' : ($sensor['label'] === 'Dissolved Oxygen' ? 'wind' : ($sensor['label'] === 'TDS' ? 'tint' : ($sensor['label'] === 'Kelembapan' ? 'tint' : ($sensor['label'] === 'TVOC' ? 'cloud' : ($sensor['label'] === 'CO2' ? 'cloud' : ($sensor['label'] === 'UV Index' ? 'sun' : 'question')))))))) }}"></i>
                        </div>
                        <div class="sensor-info">
                            <p class="sensor-label">{{ $sensor['label'] }}</p>
                            <p class="sensor-value">{{ $sensor['value'] }}<span class="sensor-unit">{{ $sensor['unit'] }}</span></p>
                            <div class="sensor-bar-wrap">
                                <div class="sensor-bar {{ $sensor['status'] === 'normal' ? 'good' : ($sensor['status'] === 'waspada' ? 'medium' : 'bad') }}" style="width: {{ (int) $sensor['pct'] }}%"></div>
                            </div>
                            <span class="sensor-status {{ $sensor['status'] === 'normal' ? 'good' : ($sensor['status'] === 'waspada' ? 'medium' : 'bad') }}">{{ ucfirst($sensor['status']) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="chart-section" x-data="locationCharts(@js($location))">
            <div class="section-header">
                <h2><i class="fas fa-chart-line"></i> Grafik Tren Data Sensor</h2>
            </div>
            <div class="chart-tabs">
                <button type="button" @click="range = '6'" :class="range === '6' ? 'chart-tab active' : 'chart-tab'">6 Jam</button>
                <button type="button" @click="range = '12'" :class="range === '12' ? 'chart-tab active' : 'chart-tab'">12 Jam</button>
                <button type="button" @click="range = '24'" :class="range === '24' ? 'chart-tab active' : 'chart-tab'">24 Jam</button>
            </div>
            <div class="chart-container">
                <canvas x-ref="chartCanvas"></canvas>
            </div>
        </div>

        <div class="bottom-grid">
            <div class="recommendation-card">
                <div class="rec-header">
                    <i class="fas fa-lightbulb"></i>
                    <h3>Rekomendasi</h3>
                    <small>{{ $location['status_label'] }}</small>
                </div>
                <div class="rec-items">
                    <div class="rec-item {{ $location['status'] === 'normal' ? 'good' : ($location['status'] === 'waspada' ? 'medium' : 'warn') }}">
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
                    Jika Anda menemukan masalah atau ingin melaporkan kondisi lokasi ini, hubungi admin melalui WhatsApp.
                    <div class="rep-info">
                        <strong>Lokasi:</strong> {{ $location['name'] }}<br>
                        <strong>Status:</strong> {{ $location['status_label'] }}<br>
                        <strong>Skor:</strong> {{ $location['condition_score'] }}/100
                    </div>
                </div>
                <a href="https://wa.me/6281234567890?text={{ rawurlencode('Halo Admin EQUITY UP, saya ingin melaporkan kondisi di '.$location['name'].' dengan status '.$location['status_label'].'.') }}" target="_blank" class="wa-button">
                    <i class="fab fa-whatsapp"></i>
                    Kirim Laporan
                </a>
            </div>
        </div>
    </div>
@endsection