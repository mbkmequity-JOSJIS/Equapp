@extends('layouts.app')

@section('title', 'Lokasi & Perangkat')

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
    <div class="main-content" x-data="{ activeTab: 'semua' }">
        <div class="page-header">
            <div class="page-title-wrap">
                <h1 class="page-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Lokasi & Perangkat
                </h1>
                <p class="page-subtitle">Pantau kondisi perangkat monitoring di berbagai lokasi</p>
            </div>
        </div>

        <div class="filter-bar">
            <div class="filter-tabs">
                <button type="button" @click="activeTab = 'semua'" :class="activeTab === 'semua' ? 'filter-tab active' : 'filter-tab'" class="filter-tab">
                    <i class="fas fa-th-large"></i>
                    Semua
                </button>
                <button type="button" @click="activeTab = 'aquaviska'" :class="activeTab === 'aquaviska' ? 'filter-tab active aquaviska' : 'filter-tab'" class="filter-tab">
                    <i class="fas fa-water"></i>
                    AQUAVISKA
                </button>
                <button type="button" @click="activeTab = 'iot'" :class="activeTab === 'iot' ? 'filter-tab active iot' : 'filter-tab'" class="filter-tab">
                    <i class="fas fa-cloud-sun"></i>
                    IoT Climate
                </button>
            </div>

            <div class="search-sort-wrap">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari lokasi..." x-data="{ search: '' }" x-model="search">
                </div>
                <div class="sort-select">
                    <select>
                        <option>Urutkan berdasarkan</option>
                        <option>Skor Tertinggi</option>
                        <option>Skor Terendah</option>
                        <option>Nama A-Z</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="location-grid">
            @foreach ($locations as $loc)
                <a href="{{ route('location.detail', $loc['id']) }}" class="location-card" x-show="activeTab === 'semua' || (activeTab === 'aquaviska' && '{{ $loc['type'] }}' === 'AQUAVISKA') || (activeTab === 'iot' && '{{ $loc['type'] }}' === 'IOT Climate')" x-cloak>
                    <div class="card-image-wrap">
                        <img src="https://via.placeholder.com/300x180/1e2d45/94a3b8?text={{ urlencode($loc['name']) }}" alt="{{ $loc['name'] }}" class="card-image">
                        <div class="card-device-badge {{ strtolower(str_replace(' ', '_', $loc['type'])) }}">
                            <i class="fas fa-{{ $loc['type'] === 'AQUAVISKA' ? 'water' : 'cloud-sun' }}"></i>
                            {{ $loc['type'] }}
                        </div>
                        <div class="card-score-badge" style="--score-color: {{ $loc['status'] === 'normal' ? '#22c55e' : ($loc['status'] === 'waspada' ? '#f59e0b' : '#ef4444') }}">
                            {{ $loc['condition_score'] }}
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $loc['name'] }}</h3>
                        <div class="card-meta">
                            <span><i class="fas fa-map-pin"></i>{{ $loc['address'] }}</span>
                        </div>
                        <div class="card-quality-row">
                            <span class="quality-label">Kualitas</span>
                            <span class="quality-badge {{ $loc['status'] === 'normal' ? 'good' : ($loc['status'] === 'waspada' ? 'medium' : 'bad') }}">
                                {{ $loc['status_label'] }}
                            </span>
                        </div>
                        <div class="sensor-preview">
                            @foreach (array_slice($loc['sensors'], 0, 3) as $sensor)
                                <div class="preview-item">
                                    <i class="fas fa-circle"></i>
                                    {{ $sensor['label'] }}
                                </div>
                            @endforeach
                            @if (count($loc['sensors']) > 3)
                                <div class="preview-item">
                                    +{{ count($loc['sensors']) - 3 }} lainnya
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection