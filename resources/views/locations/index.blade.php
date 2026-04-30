{{-- resources/views/locations/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EQUApp | Lokasi & Perangkat</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Montserrat', sans-serif;
    }

    body {
      background: #fff;
      color: #222;
      overflow-x: hidden;
    }

    .layout {
      display: flex;
      min-height: 100vh;
    }

    /* SIDEBAR */
    .sidebar {
      width: 260px;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      background: linear-gradient(90deg, #6ee89a, #95d6f4);
      padding: 40px 35px;
      color: white;
      z-index: 10;
    }

    .logo {
      width: 130px;
      margin-bottom: 130px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar li {
      margin-bottom: 16px;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      font-size: 28px;
      font-weight: 700;
      transition: .2s;
      display: block;
      padding: 10px 0;
    }

    .sidebar .sidebar-link {
      color: white;
      text-decoration: none;
      font-size: 28px;
      font-weight: 700;
      transition: .2s;
      display: block;
      width: 100%;
      padding: 10px 12px;
      border-radius: 12px;
    }

    .sidebar .sidebar-link.active {
      background: rgba(255,255,255,0.18);
      color: #0f172a !important;
    }

    .sidebar .sidebar-link:hover {
      color: #72ee9c;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      color: #72ee9c;
    }

    .profile-icon {
      position: absolute;
      bottom: 45px;
      left: 45px;
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: white;
    }

    /* CONTENT */
    .content {
      margin-left: 260px;
      width: calc(100% - 260px);
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
      display: flex;
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
</head>
<body>
  <div class="layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <img src="{{ asset('logo.png') }}" alt="Equapp Logo" class="logo">
      <ul>
        <li><a href="{{ route('home') }}" class="sidebar-link">Dashboard</a></li>
        <li><a href="{{ route('lokasi') }}" class="sidebar-link {{ request()->route()->getName() === 'lokasi' ? 'active' : '' }}">Lokasi</a></li>
        <li><a href="{{ route('perangkat') }}" class="sidebar-link {{ request()->route()->getName() === 'perangkat' ? 'active' : '' }}">Perangkat</a></li>
      </ul>
      <div class="profile-icon"></div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">
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
            <button type="button" @click="activeTab = 'semua'" :class="activeTab === 'semua' ? 'filter-tab active' : 'filter-tab'">
              <i class="fas fa-th-large"></i>
              Semua
            </button>
            <button type="button" @click="activeTab = 'aquaviska'" :class="activeTab === 'aquaviska' ? 'filter-tab active aquaviska' : 'filter-tab'">
              <i class="fas fa-water"></i>
              AQUAVISKA
            </button>
            <button type="button" @click="activeTab = 'iot'" :class="activeTab === 'iot' ? 'filter-tab active iot' : 'filter-tab'">
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
    </main>
  </div>
</body>
</html>