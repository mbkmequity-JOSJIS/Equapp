{{-- resources/views/admin/devices/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard - Manajemen Perangkat')

@section('style')
    <style>
        /* Custom styles for modal animation */
        .modal-overlay {
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible !important;
        }

        .modal-container {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .modal-overlay.show .modal-container {
            transform: scale(1);
            opacity: 1;
        }

        /* Custom scrollbar */
        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* Loading spinner */
        .loading-spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3b82f6;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <main class="bg-white min-h-screen px-6 md:p-10 md:pt-16 relative">
        <div
            class="absolute top-0 left-0 right-0 bg-orange-500/40 text-white p-2 text-center flex text-xl justify-center items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span class="font-semibold tracking-wider">Admin Side</span>
        </div>

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-microchip text-4xl text-blue-500"></i>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-800">Manajemen Perangkat</h1>
            </div>
            <p class="text-slate-500 text-base md:text-lg">Kelola semua perangkat monitoring AQUAVISKA dan IoT Climate</p>
        </div>

        <!-- Filter & Action Bar -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 p-5 bg-white rounded-xl border border-slate-200 shadow-sm">
            <!-- Filter Tabs -->
            <div class="flex gap-2 p-1.5 bg-slate-50 rounded-xl border border-slate-200">
                <button onclick="filterDevices('all')" id="filterAll"
                    class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-blue-500 text-white shadow-sm">
                    <i class="fas fa-th-large mr-2"></i>Semua
                </button>
                <button onclick="filterDevices('aquaviska')" id="filterAquaviska"
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-slate-600 hover:bg-slate-100">
                    <i class="fas fa-water mr-2 text-blue-400"></i>AQUAVISKA
                </button>
                <button onclick="filterDevices('iot')" id="filterIot"
                    class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-slate-600 hover:bg-slate-100">
                    <i class="fas fa-cloud-sun mr-2 text-amber-500"></i>IoT Climate
                </button>
            </div>

            <!-- Search & Add Button -->
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" onkeyup="searchDevices()" placeholder="Cari perangkat..."
                        class="pl-9 pr-4 py-2 w-full sm:w-64 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                </div>
                <button onclick="openAddModal()"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-5 py-2 rounded-xl font-medium flex items-center justify-center gap-2 hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus"></i>
                    Tambah Perangkat
                </button>
            </div>
        </div>

        <!-- Devices Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="text-left py-4 px-5 text-sm font-semibold text-slate-600">ID</th>
                            <th class="text-left py-4 px-5 text-sm font-semibold text-slate-600">Nama Perangkat</th>
                            <th class="text-left py-4 px-5 text-sm font-semibold text-slate-600">Lokasi</th>
                            <th class="text-left py-4 px-5 text-sm font-semibold text-slate-600">Tipe</th>
                            <th class="text-left py-4 px-5 text-sm font-semibold text-slate-600">Status</th>
                            <th class="text-left py-4 px-5 text-sm font-semibold text-slate-600">Sensor</th>
                            <th class="text-left py-4 px-5 text-sm font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="devicesTableBody">
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-16">
                <i class="fas fa-microchip text-6xl text-slate-300 mb-4"></i>
                <p class="text-slate-500">Tidak ada perangkat yang ditemukan</p>
            </div>
        </div>
    </main>

    <!-- MODAL TAMBAH/EDIT PERANGKAT -->
    <div id="deviceModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 opacity-0 invisible transition-all duration-300">
        <div
            class="bg-white rounded-2xl w-[95%] max-w-lg max-h-[90vh] overflow-hidden shadow-2xl transform scale-95 transition-all duration-300">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i id="modalIcon" class="fas fa-plus-circle text-blue-500"></i>
                    <span id="modalTitle">Tambah Perangkat Baru</span>
                </h2>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
                <form id="deviceForm" onsubmit="return false;">
                    <input type="hidden" id="deviceId">

                    <!-- Nama Perangkat -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-tag text-blue-400 mr-1"></i> Nama Perangkat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="deviceName" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                            placeholder="Contoh: Sensor Air Cisadane">
                    </div>

                    <!-- Serial Number & Tipe -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-barcode text-blue-400 mr-1"></i> Serial Number <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="text" id="serialNumber" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                                placeholder="SN-XXXX-XXXX">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-layer-group text-blue-400 mr-1"></i> Tipe Perangkat <span
                                    class="text-red-500">*</span>
                            </label>
                            <select id="deviceType" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                                <option value="">Pilih Tipe</option>
                                <option value="AQUAVISKA">💧 AQUAVISKA (Monitoring Air)</option>
                                <option value="IOT Climate">☁️ IoT Climate (Monitoring Udara)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-map-marker-alt text-blue-400 mr-1"></i> Lokasi <span
                                class="text-red-500">*</span>
                        </label>
                        <select id="locationId" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            <option value="">Pilih Lokasi</option>
                            @foreach ($locations ?? [] as $loc)
                                <option value="{{ $loc['id'] }}">{{ $loc['name'] }} - {{ $loc['address'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status & Score -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-chart-line text-blue-400 mr-1"></i> Status
                            </label>
                            <select id="deviceStatus"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                                <option value="active">🟢 Aktif</option>
                                <option value="inactive">🔴 Nonaktif</option>
                                <option value="maintenance">🟡 Pemeliharaan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-chart-simple text-blue-400 mr-1"></i> Condition Score
                            </label>
                            <input type="number" id="conditionScore" min="0" max="100" value="75"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    </div>

                    <!-- Sensor yang Tersedia -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-microchip text-blue-400 mr-1"></i> Sensor yang Tersedia
                        </label>
                        <div class="grid grid-cols-2 gap-2 p-3 bg-slate-50 rounded-xl">
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" value="pH Meter" class="sensor-checkbox rounded"> pH Meter
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" value="TDS" class="sensor-checkbox rounded"> TDS
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" value="Suhu Air" class="sensor-checkbox rounded"> Suhu Air
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" value="Kekeruhan" class="sensor-checkbox rounded"> Kekeruhan
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" value="PM2.5" class="sensor-checkbox rounded"> PM2.5
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" value="PM10" class="sensor-checkbox rounded"> PM10
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" value="CO2" class="sensor-checkbox rounded"> CO2
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" value="Suhu Udara" class="sensor-checkbox rounded"> Suhu Udara
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" value="Kelembaban" class="sensor-checkbox rounded"> Kelembaban
                            </label>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-align-left text-blue-400 mr-1"></i> Deskripsi (Opsional)
                        </label>
                        <textarea id="deviceDescription" rows="3"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Deskripsi singkat tentang perangkat..."></textarea>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 p-6 border-t border-slate-200 bg-slate-50">
                <button onclick="closeModal()"
                    class="px-5 py-2 border border-slate-300 rounded-xl text-slate-600 font-medium hover:bg-slate-100 transition">
                    Batal
                </button>
                <button onclick="saveDevice()"
                    class="px-5 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-medium hover:from-blue-600 hover:to-blue-700 transition shadow-md">
                    <i class="fas fa-save mr-1"></i> Simpan Perangkat
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS -->
    <div id="deleteModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 opacity-0 invisible transition-all duration-300">
        <div class="bg-white rounded-2xl w-[90%] max-w-md shadow-2xl transform scale-95 transition-all duration-300">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trash-alt text-2xl text-red-500"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Konfirmasi Hapus</h3>
                <p class="text-slate-500 mb-1">Apakah Anda yakin ingin menghapus perangkat</p>
                <p class="font-semibold text-slate-700" id="deleteDeviceName"></p>
                <p class="text-sm text-red-500 mt-3">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="flex gap-3 p-6 pt-0">
                <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2 border border-slate-300 rounded-xl text-slate-600 font-medium hover:bg-slate-100 transition">
                    Batal
                </button>
                <button onclick="confirmDeleteDevice()"
                    class="flex-1 px-4 py-2 bg-red-500 text-white rounded-xl font-medium hover:bg-red-600 transition">
                    Hapus
                </button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Data devices (simulasi - nanti diganti dengan data dari database)
        let devices = [{
                id: 1,
                name: 'Sensor Air Cisadane',
                serial_number: 'AQV-001',
                type: 'AQUAVISKA',
                location: 'Cisadane, Tangerang',
                location_id: 1,
                status: 'active',
                condition_score: 85,
                sensors: ['pH Meter', 'TDS', 'Suhu Air', 'Kekeruhan'],
                description: 'Sensor pemantauan kualitas air Sungai Cisadane'
            },
            {
                id: 2,
                name: 'Sensor Udara Jakarta Pusat',
                serial_number: 'IOT-001',
                type: 'IOT Climate',
                location: 'Jakarta Pusat',
                location_id: 2,
                status: 'active',
                condition_score: 62,
                sensors: ['PM2.5', 'PM10', 'CO2', 'Suhu Udara'],
                description: 'Sensor pemantauan kualitas udara di area perkantoran'
            },
            {
                id: 3,
                name: 'Sensor Air Bengawan Solo',
                serial_number: 'AQV-002',
                type: 'AQUAVISKA',
                location: 'Bengawan Solo, Surakarta',
                location_id: 3,
                status: 'maintenance',
                condition_score: 45,
                sensors: ['pH Meter', 'TDS', 'Suhu Air'],
                description: 'Sensor pemantauan kualitas air Bengawan Solo'
            },
            {
                id: 4,
                name: 'Sensor Udara Bandung',
                serial_number: 'IOT-002',
                type: 'IOT Climate',
                location: 'Bandung, Jawa Barat',
                location_id: 4,
                status: 'inactive',
                condition_score: 30,
                sensors: ['PM2.5', 'PM10', 'Suhu Udara', 'Kelembaban'],
                description: 'Sensor pemantauan kualitas udara Kota Bandung'
            }
        ];

        let currentFilter = 'all';
        let currentSearch = '';
        let deleteId = null;

        // Render tabel devices
        function renderDevices() {
            let filtered = [...devices];

            // Filter by type
            if (currentFilter !== 'all') {
                filtered = filtered.filter(d =>
                    currentFilter === 'aquaviska' ? d.type === 'AQUAVISKA' : d.type === 'IOT Climate'
                );
            }

            // Filter by search
            if (currentSearch) {
                const keyword = currentSearch.toLowerCase();
                filtered = filtered.filter(d =>
                    d.name.toLowerCase().includes(keyword) ||
                    d.location.toLowerCase().includes(keyword) ||
                    d.serial_number.toLowerCase().includes(keyword)
                );
            }

            const tbody = document.getElementById('devicesTableBody');
            const emptyState = document.getElementById('emptyState');

            if (filtered.length === 0) {
                tbody.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');

            tbody.innerHTML = filtered.map(device => `
            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                <td class="py-3.5 px-5 text-sm text-slate-500">${device.id}</td>
                <td class="py-3.5 px-5">
                    <div class="font-medium text-slate-800">${escapeHtml(device.name)}</div>
                    <div class="text-xs text-slate-400">${escapeHtml(device.serial_number)}</div>
                </td>
                <td class="py-3.5 px-5 text-sm text-slate-600">${escapeHtml(device.location)}</td>
                <td class="py-3.5 px-5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium ${device.type === 'AQUAVISKA' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'}">
                        <i class="fas ${device.type === 'AQUAVISKA' ? 'fa-water' : 'fa-cloud-sun'} text-xs"></i>
                        ${device.type}
                    </span>
                </td>
                <td class="py-3.5 px-5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                        ${device.status === 'active' ? 'bg-green-100 text-green-700' : (device.status === 'inactive' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')}">
                        <i class="fas fa-circle text-[6px]"></i>
                        ${device.status === 'active' ? 'Aktif' : (device.status === 'inactive' ? 'Nonaktif' : 'Pemeliharaan')}
                    </span>
                </td>
                <td class="py-3.5 px-5">
                    <div class="flex flex-wrap gap-1.5">
                        ${device.sensors.slice(0, 2).map(s => `<span class="px-2 py-0.5 bg-slate-100 rounded-md text-xs text-slate-600">${escapeHtml(s)}</span>`).join('')}
                        ${device.sensors.length > 2 ? `<span class="px-2 py-0.5 bg-slate-100 rounded-md text-xs text-slate-500">+${device.sensors.length - 2}</span>` : ''}
                    </div>
                </td>
                <td class="py-3.5 px-5">
                    <div class="flex gap-2">
                        <button onclick="openEditModal(${device.id})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openDeleteModal(${device.id})" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        }

        // Escape HTML untuk keamanan
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Filter devices
        function filterDevices(type) {
            currentFilter = type;

            // Update active button style
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white', 'shadow-sm');
                btn.classList.add('text-slate-600');
            });

            if (type === 'all') {
                document.getElementById('filterAll').classList.add('bg-blue-500', 'text-white', 'shadow-sm');
                document.getElementById('filterAll').classList.remove('text-slate-600');
            } else if (type === 'aquaviska') {
                document.getElementById('filterAquaviska').classList.add('bg-blue-500', 'text-white', 'shadow-sm');
                document.getElementById('filterAquaviska').classList.remove('text-slate-600');
            } else {
                document.getElementById('filterIot').classList.add('bg-blue-500', 'text-white', 'shadow-sm');
                document.getElementById('filterIot').classList.remove('text-slate-600');
            }

            renderDevices();
        }

        // Search devices
        function searchDevices() {
            currentSearch = document.getElementById('searchInput').value;
            renderDevices();
        }

        // Open Add Modal
        function openAddModal() {
            document.getElementById('modalIcon').className = 'fas fa-plus-circle text-blue-500';
            document.getElementById('modalTitle').innerText = 'Tambah Perangkat Baru';
            document.getElementById('deviceId').value = '';
            document.getElementById('deviceName').value = '';
            document.getElementById('serialNumber').value = '';
            document.getElementById('deviceType').value = '';
            document.getElementById('locationId').value = '';
            document.getElementById('deviceStatus').value = 'active';
            document.getElementById('conditionScore').value = '75';
            document.getElementById('deviceDescription').value = '';

            // Reset checkboxes
            document.querySelectorAll('.sensor-checkbox').forEach(cb => cb.checked = false);

            openModal();
        }

        // Open Edit Modal
        function openEditModal(id) {
            const device = devices.find(d => d.id === id);
            if (!device) return;

            document.getElementById('modalIcon').className = 'fas fa-edit text-blue-500';
            document.getElementById('modalTitle').innerText = 'Edit Perangkat';
            document.getElementById('deviceId').value = device.id;
            document.getElementById('deviceName').value = device.name;
            document.getElementById('serialNumber').value = device.serial_number;
            document.getElementById('deviceType').value = device.type;
            document.getElementById('locationId').value = device.location_id || '';
            document.getElementById('deviceStatus').value = device.status;
            document.getElementById('conditionScore').value = device.condition_score;
            document.getElementById('deviceDescription').value = device.description || '';

            // Set checkboxes
            document.querySelectorAll('.sensor-checkbox').forEach(cb => {
                cb.checked = device.sensors.includes(cb.value);
            });

            openModal();
        }

        // Open modal
        function openModal() {
            const modal = document.getElementById('deviceModal');
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');
            document.body.style.overflow = 'hidden';
        }

        // Close modal
        function closeModal() {
            const modal = document.getElementById('deviceModal');
            modal.classList.add('opacity-0', 'invisible');
            modal.classList.remove('opacity-100', 'visible');
            document.body.style.overflow = '';
        }

        // Save device
        function saveDevice() {
            const id = document.getElementById('deviceId').value;
            const name = document.getElementById('deviceName').value;
            const serial_number = document.getElementById('serialNumber').value;
            const type = document.getElementById('deviceType').value;
            const location_id = document.getElementById('locationId').value;
            const status = document.getElementById('deviceStatus').value;
            const condition_score = parseInt(document.getElementById('conditionScore').value) || 50;
            const description = document.getElementById('deviceDescription').value;

            // Get selected sensors
            const sensors = [];
            document.querySelectorAll('.sensor-checkbox:checked').forEach(cb => {
                sensors.push(cb.value);
            });

            // Validation
            if (!name || !serial_number || !type || !location_id) {
                alert('Mohon lengkapi semua field yang diperlukan!');
                return;
            }

            // Get location name (simulasi)
            const locationSelect = document.getElementById('locationId');
            const locationName = locationSelect.options[locationSelect.selectedIndex]?.text.split(' - ')[0] || 'Lokasi';

            if (id) {
                // Edit device
                const index = devices.findIndex(d => d.id == id);
                if (index !== -1) {
                    devices[index] = {
                        ...devices[index],
                        name,
                        serial_number,
                        type,
                        location_id,
                        location: locationName,
                        status,
                        condition_score,
                        sensors,
                        description
                    };
                    alert('Perangkat berhasil diupdate!');
                }
            } else {
                // Add new device
                const newId = Math.max(...devices.map(d => d.id), 0) + 1;
                devices.push({
                    id: newId,
                    name,
                    serial_number,
                    type,
                    location_id,
                    location: locationName,
                    status,
                    condition_score,
                    sensors,
                    description
                });
                alert('Perangkat berhasil ditambahkan!');
            }

            closeModal();
            renderDevices();
        }

        // Open delete modal
        function openDeleteModal(id) {
            const device = devices.find(d => d.id === id);
            if (device) {
                deleteId = id;
                document.getElementById('deleteDeviceName').innerText = device.name;
                const modal = document.getElementById('deleteModal');
                modal.classList.remove('opacity-0', 'invisible');
                modal.classList.add('opacity-100', 'visible');
                document.body.style.overflow = 'hidden';
            }
        }

        // Close delete modal
        function closeDeleteModal() {
            deleteId = null;
            const modal = document.getElementById('deleteModal');
            modal.classList.add('opacity-0', 'invisible');
            modal.classList.remove('opacity-100', 'visible');
            document.body.style.overflow = '';
        }

        // Confirm delete device
        function confirmDeleteDevice() {
            if (deleteId) {
                devices = devices.filter(d => d.id !== deleteId);
                alert('Perangkat berhasil dihapus!');
                closeDeleteModal();
                renderDevices();
            }
        }

        // Initial render
        renderDevices();

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeDeleteModal();
            }
        });

        // Close modal on overlay click
        document.getElementById('deviceModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
    </script>
@endsection
