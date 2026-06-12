@extends('layouts.app')
@section('title', 'Lokasi Instalasi')
{{-- resources/views/locations/index.blade.php --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
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
    <style>
        #locationsMap {
            height: 20rem;
            border-radius: 12px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <!-- MAIN CONTENT -->
    <main class="content">
        <div class="main-content"
            x-data="locationsPage({ locations: @js($locations), detailBaseUrl: '{{ url('/modul/lokasi') }}', imageBaseUrl: '{{ asset('storage/img_loc') }}' })">
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
                            CLIMEET
                    </button>
                </div>

                <div class="search-sort-wrap">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari lokasi..." x-model="search">
                    </div>
                    <div class="sort-select">
                        <select x-model="sortBy">
                            <option value="default">Urutkan berdasarkan</option>
                            <option value="score_desc">Skor Tertinggi</option>
                            <option value="score_asc">Skor Terendah</option>
                            <option value="name_asc">Nama A-Z</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="location-grid">
                <template x-for="loc in filteredLocations" :key="loc.id">
                    <a :href="`${detailBaseUrl}/${loc.id}`" class="location-card" x-cloak>
                        <div class="card-image-wrap">
                            <img :src="`${imageBaseUrl}/${loc.image}`" :alt="loc.name" class="card-image">
                            <div class="card-device-badge">
                                <i class="fas" :class="loc.type === 'AQUAVISKA' ? 'fa-water' : 'fa-cloud-sun'"></i>
                                <span x-text="loc.type"></span>
                            </div>
                            <div class="card-score-badge" :style="`--score-color: ${scoreColor(loc.status)}`">
                                <span x-text="loc.condition_score"></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title" x-text="loc.name"></h3>
                            <div class="card-meta">
                                <span><i class="fas fa-map-pin"></i><span x-text="loc.address"></span></span>
                            </div>
                            <div class="card-quality-row">
                                <span class="quality-label">Kualitas</span>
                                <span class="quality-badge" :class="qualityBadgeClass(loc.status)" x-text="loc.status_label"></span>
                            </div>
                            <div class="sensor-preview">
                                <template x-for="sensor in (loc.sensors || []).slice(0, 3)"
                                    :key="`${loc.id}-${sensor.label}`">
                                    <div class="preview-item">
                                        <i class="fas fa-circle"></i>
                                        <span x-text="sensor.label"></span>
                                    </div>
                                </template>
                                <template x-if="(loc.sensors || []).length > 3">
                                    <div class="preview-item">
                                        <span x-text="`+${(loc.sensors || []).length - 3} lainnya`"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </a>
                </template>
            </div>

            <div x-show="filteredLocations.length === 0" x-cloak
                style="margin-top: 20px; padding: 16px; border-radius: 10px; background: #f8fafc; color: #475569;">
                Data lokasi tidak ditemukan untuk filter/pencarian saat ini.
            </div>

            <!-- MAP: lokasi pemasangan -->
            <div class="mt-6">
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Peta Lokasi Pemasangan</h3>
                    <div id="locationsMap"></div>
                </div>
            </div>

        </div>
    </main>
@endsection

@section('script')
    <script>
        function locationsPage({
            locations,
            detailBaseUrl,
            imageBaseUrl
        }) {
            return {
                locations: Array.isArray(locations) ? locations : [],
                detailBaseUrl,
                imageBaseUrl,
                activeTab: 'semua',
                search: '',
                sortBy: 'default',
                filteredLocations: [],
                init() {
                    this.applyFilters();
                    this.$watch('activeTab', () => this.applyFilters());
                    this.$watch('search', () => this.applyFilters());
                    this.$watch('sortBy', () => this.applyFilters());
                },
                applyFilters() {
                    const query = this.search.trim().toLowerCase();

                    let rows = this.locations.filter((loc) => {
                        const passTab = this.activeTab === 'semua' ||
                            (this.activeTab === 'aquaviska' && loc.type === 'AQUAVISKA') ||
                            (this.activeTab === 'iot' && loc.type === 'IOT Climate');

                        if (!passTab) {
                            return false;
                        }

                        if (!query) {
                            return true;
                        }

                        const sensorLabels = (loc.sensors || [])
                            .map((sensor) => sensor.label || '')
                            .join(' ')
                            .toLowerCase();

                        const haystack = `${loc.name || ''} ${loc.address || ''} ${loc.type || ''} ${sensorLabels}`
                            .toLowerCase();

                        return haystack.includes(query);
                    });

                    rows.sort((a, b) => {
                        if (this.sortBy === 'score_desc') {
                            return (b.condition_score || 0) - (a.condition_score || 0);
                        }
                        if (this.sortBy === 'score_asc') {
                            return (a.condition_score || 0) - (b.condition_score || 0);
                        }
                        if (this.sortBy === 'name_asc') {
                            return (a.name || '').localeCompare(b.name || '', 'id-ID');
                        }
                        return 0;
                    });

                    this.filteredLocations = rows;
                },
                qualityBadgeClass(status) {
                    if (status === 'normal') {
                        return 'good';
                    }
                    if (status === 'waspada') {
                        return 'medium';
                    }
                    return 'bad';
                },
                scoreColor(status) {
                    if (status === 'normal') {
                        return '#22c55e';
                    }
                    if (status === 'waspada') {
                        return '#f59e0b';
                    }
                    return '#ef4444';
                },
            };
        }
    </script>
@endsection
