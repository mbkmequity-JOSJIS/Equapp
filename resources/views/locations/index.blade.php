@extends('layouts.app')
@section('title', 'Lokasi Instalasi')
{{-- resources/views/locations/index.blade.php --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('style')
    <style>
        /* CONTENT */
        .content {
            background: #fff;
            min-height: 100vh;
            padding: 40px;
        }

        .main-content {
            background: #fff;
        }

        /* Additional styles for locations page */
        .page-header {
            margin-bottom: 30px;
        }

        .page-title-wrap {
            width: 100%;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .page-title {
            font-size: 36px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-subtitle {
            font-size: 18px;
            color: #64748b;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #ffffff !important;
            border-radius: 16px;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 5px rgba(15, 23, 42, 0.08) !important;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            padding: 10px;
            border-radius: 14px;
        }

        .filter-tab {
            padding: 10px 20px;
            border: 1px solid transparent;
            background: #f8fafc !important;
            color: #0f172a !important;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
            box-shadow: none !important;
        }

        .filter-tab.active {
            background: #6ee89a !important;
            color: #ffffff !important;
            border-color: transparent !important;
        }

        .filter-tab.active.aquaviska {
            background: #3b82f6;
        }

        .filter-tab.active.iot {
            background: #f59e0b;
        }

        .search-sort-wrap {
            display: flex;
            gap: 15px;
        }

        .search-box {
            position: relative;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px;
            padding: 10px 12px 10px 40px;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .search-box input {
            width: 100%;
            background: transparent !important;
            border: none;
            outline: none;
            color: #0f172a !important;
            font-size: 14px;
            padding: 0;
        }

        .sort-select select {
            width: 220px;
            padding: 10px 15px;
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px !important;
            background: #ffffff !important;
            color: #0f172a !important;
            font-size: 14px;
            box-shadow: none !important;
        }

        .sort-select select option {
            background: #ffffff !important;
            color: #0f172a !important;
        }

        .location-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .location-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .location-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .card-image-wrap {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-device-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            color: #1e293b;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .card-score-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--score-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .card-meta {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 15px;
        }

        .card-meta i {
            margin-right: 5px;
        }

        .card-quality-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .quality-label {
            font-size: 14px;
            color: #64748b;
        }

        .quality-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .quality-badge.good {
            background: #dcfce7;
            color: #166534;
        }

        .quality-badge.medium {
            background: #fef3c7;
            color: #92400e;
        }

        .quality-badge.bad {
            background: #fee2e2;
            color: #991b1b;
        }

        .sensor-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .preview-item {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .preview-item i {
            font-size: 8px;
        }
    </style>
@endsection

@section('content')
    <!-- MAIN CONTENT -->
    <main class="content">
        <div class="main-content" x-data="{ activeTab: 'semua' }">
            <div class="page-header ">
                <div class="page-title-wrap w-full">
                    <h1 class="page-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Lokasi & Perangkat
                    </h1>
                    <p class="page-subtitle">Pantau kondisi perangkat monitoring di berbagai lokasi</p>
                </div>
            </div>

            <div class="filter-bar">
                <div class="filter-tabs">
                    <button type="button" @click="activeTab = 'semua'"
                        :class="activeTab === 'semua' ? 'filter-tab active' : 'filter-tab'">
                        <i class="fas fa-th-large"></i>
                        Semua
                    </button>
                    <button type="button" @click="activeTab = 'aquaviska'"
                        :class="activeTab === 'aquaviska' ? 'filter-tab active aquaviska' : 'filter-tab'">
                        <i class="fas fa-water"></i>
                        AQUAVISKA
                    </button>
                    <button type="button" @click="activeTab = 'iot'"
                        :class="activeTab === 'iot' ? 'filter-tab active iot' : 'filter-tab'">
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
                    <a href="{{ route('location.detail.modul', $loc['id']) }}" class="location-card"
                        x-show="activeTab === 'semua' || (activeTab === 'aquaviska' && '{{ $loc['type'] }}' === 'AQUAVISKA') || (activeTab === 'iot' && '{{ $loc['type'] }}' === 'IOT Climate')"
                        x-cloak>
                        <div class="card-image-wrap">
                            <img src="{{ asset('storage/img_loc/' . $loc['image']) }}"
                                alt="{{ $loc['name'] }}" class="card-image">
                            <div class="card-device-badge {{ strtolower(str_replace(' ', '_', $loc['type'])) }}">
                                <i class="fas fa-{{ $loc['type'] === 'AQUAVISKA' ? 'water' : 'cloud-sun' }}"></i>
                                {{ $loc['type'] }}
                            </div>
                            <div class="card-score-badge"
                                style="--score-color: {{ $loc['status'] === 'normal' ? '#22c55e' : ($loc['status'] === 'waspada' ? '#f59e0b' : '#ef4444') }}">
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
                                <span
                                    class="quality-badge {{ $loc['status'] === 'normal' ? 'good' : ($loc['status'] === 'waspada' ? 'medium' : 'bad') }}">
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
    </main>
@endsection
