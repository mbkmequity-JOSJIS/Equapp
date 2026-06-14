@extends('layouts.app')
@section('title', 'Device Detail')

@section('style')
    <style>
        .sensor-bar-track {
            height: 0.5rem;
            background: rgba(148, 163, 184, 0.22);
            border-radius: 9999px;
            overflow: hidden;
        }

        .sensor-bar-fill {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.45s ease;
        }

        .sensor-bar-fill.good {
            background: #22c55e;
        }

        .sensor-bar-fill.medium {
            background: #f59e0b;
        }

        .sensor-bar-fill.bad {
            background: #ef4444;
        }

        .sensor-status.good {
            background: rgba(34, 197, 94, 0.12);
            color: #166534;
        }

        .sensor-status.medium {
            background: rgba(245, 158, 11, 0.12);
            color: #92400e;
        }

        .sensor-status.bad {
            background: rgba(239, 68, 68, 0.12);
            color: #991b1b;
        }

        /* Modal Custom Styles */
        .modal-transition {
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .modal-transition.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .modal-transition:not(.hidden) {
            opacity: 1;
            visibility: visible;
        }
    </style>
@endsection

@section('content')
    @php
        $deviceType = $deviceDataInfo['type'] ?? ($device === 'aquaviska' ? 'AQUAVISKA' : 'CLIMEET');
        $deviceName = $deviceDataInfo['device_name'] ?? 'Device Detail';
        $deviceAddress = data_get($deviceDataInfo, 'location.address', '-');
        $deviceScore = (int) ($deviceDataInfo['condition_score'] ?? 0);
        $deviceStatus = $deviceDataInfo['status'] ?? 'normal';
        $statusTone = $deviceStatus === 'normal' ? 'good' : ($deviceStatus === 'waspada' ? 'medium' : 'bad');
        $sensorCards = $deviceDataMonitoring['sensors'] ?? [];
        $detailApi = route('api.device.detail', [
            'device' => $device,
            'id' => $deviceDataInfo['device_code'] ?? request()->route('id'),
        ]);
        $heroImage = asset('../img_loc/dummy_loc (1).jpg');
    @endphp

    <div class="min-h-screen bg-slate-50 text-slate-900">
        <div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8" data-device-api="{{ $detailApi }}"
            data-device-code="{{ $deviceDataInfo['device_code'] ?? '' }}">
            <div class="mb-6 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                <a href="{{ route('device.list', $device) }}"
                    class="inline-flex items-center gap-2 r px-4 py-2 font-medium text-green-500 transition  hover:text-emerald-700">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Daftar Device
                </a>
                <span class="text-slate-300">/</span>
                <span>{{ $deviceName }}</span>
            </div>

            <section
                class="relative overflow-hidden rounded-4xl border border-white/60 bg-slate-900 shadow-[0_20px_60px_rgba(15,23,42,0.18)]">
                <div class="absolute inset-0">
                    <div class="h-full w-full bg-cover bg-center opacity-35"
                        style="background-image: url('{{ $heroImage }}')"></div>
                    {{-- <div class="absolute inset-0 bg-linear-to-br from-slate-950 via-slate-900/85 to-emerald-950/70"></div> --}}
                </div>

                <div class="relative grid gap-6 p-6 sm:p-8 lg:grid-cols-[1.35fr_0.65fr] lg:gap-8 lg:p-10">
                    <div class="flex flex-col justify-end gap-5 text-white">
                        <div
                            class="inline-flex w-fit items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold backdrop-blur">
                            <i class="fas fa-{{ $deviceType === 'AQUAVISKA' ? 'water' : 'cloud-sun' }}"></i>
                            {{ $deviceType }}
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">{{ $deviceName }}</h1>
                            <p class="mt-3 flex items-center gap-2 text-sm text-slate-200 sm:text-base">
                                <i class="fas fa-map-marker-alt text-emerald-300"></i>
                                {{ $deviceAddress }}
                            </p>
                        </div>
                    </div>

                    <div class="gap-4 flex flex-col justify-end items-end">
                        <div class=" rounded-3xl border border-white/15 bg-white/90 p-5 shadow-xl backdrop-blur"
                            id="score-badge" data-location-id="{{ $deviceDataInfo['device_code'] ?? '' }}">
                            <p class="text-sm font-medium text-slate-500">Skor Kondisi</p>
                            <div class="mt-2 flex items-end gap-2">
                                <span class="text-5xl font-black tracking-tight text-slate-900">{{ $deviceScore }}</span>
                                <span class="pb-1 text-lg font-semibold text-slate-500">/100</span>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-white/15 bg-white/10 p-5 text-white backdrop-blur">
                            <p class="text-sm text-slate-200">Live Status</p>
                            <div class="mt-4 rounded-2xl bg-white/10 px-4 py-3 text-sm text-slate-100">
                                <div class="flex items-center gap-3">
                                    <span class="relative flex h-3 w-3">
                                        <span
                                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                                        <span class="relative inline-flex h-3 w-3 rounded-full bg-emerald-400"></span>
                                    </span>
                                    <span class="text-sm font-semibold">Sensor aktif dan siap dipantau</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-8">
                <div class="mb-5 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="flex items-center gap-3 text-2xl font-bold tracking-tight text-slate-900">
                            <i class="fas fa-tachometer-alt text-emerald-500"></i>
                            Sensor & Perangkat
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">Data real-time dari sensor dan perangkat</p>
                    </div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                        </span>
                        Live Data
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3 relative">
                    @foreach ($sensorCards as $sensor)
                        @php
                            $sensorTone =
                                $sensor['status'] === 'normal'
                                    ? 'good'
                                    : ($sensor['status'] === 'waspada'
                                        ? 'medium'
                                        : 'bad');
                            $sensorKey = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $sensor['label']));
                            $sensorKey = trim($sensorKey, '-');
                            $icon = 'question';
                            if (
                                str_contains(strtolower($sensor['label']), 'temperatur') ||
                                str_contains(strtolower($sensor['label']), 'suhu')
                            ) {
                                $icon = 'thermometer-half';
                            } elseif (str_contains($sensor['label'], 'pH')) {
                                $icon = 'flask';
                            } elseif (
                                str_contains(strtolower($sensor['label']), 'turbidity') ||
                                str_contains(strtolower($sensor['label']), 'kekeruhan')
                            ) {
                                $icon = 'eye';
                            } elseif (
                                str_contains(strtolower($sensor['label']), 'dissolved oxygen') ||
                                str_contains(strtolower($sensor['label']), 'do')
                            ) {
                                $icon = 'wind';
                            } elseif (str_contains(strtolower($sensor['label']), 'total dissolved solids')) {
                                $icon = 'tint';
                            } elseif (str_contains(strtolower($sensor['label']), 'kelembapan')) {
                                $icon = 'droplet';
                            } elseif (
                                str_contains(strtolower($sensor['label']), 'tvoc') ||
                                str_contains(strtolower($sensor['label']), 'co2')
                            ) {
                                $icon = 'cloud';
                            } elseif (str_contains(strtolower($sensor['label']), 'uv')) {
                                $icon = 'sun';
                            } elseif (str_contains(strtolower($sensor['label']), 'angin')) {
                                $icon = 'wind';
                            } elseif (str_contains(strtolower($sensor['label']), 'curah')) {
                                $icon = 'cloud-showers-heavy';
                            }
                        @endphp
                        <article
                            class="sensor-card overflow-hidden rounded-3xl border border-slate-200 p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg flex flex-col justify-between relative"
                            data-sensor-label="{{ $sensor['label'] }} ">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-700 {{ $sensorKey }}">
                                    <i class="fas fa-{{ $icon }}"></i>
                                </div>
                                <div class="min-w-0 flex-1 ">
                                    <p class="text-base font-semibold text-slate-900">
                                        {{ $sensor['label'] }}</p>
                                </div>
                            </div>

                            <div class="text-center w-full">
                                <p class="sensor-value text-3xl font-black tracking-tight text-slate-900">
                                    <span class="text-black">{{ $sensor['value'] }}</span><span
                                        class="sensor-unit ml-1 text-sm font-semibold text-slate-500">{{ $sensor['unit'] }}</span>
                                </p>

                                <div class="mt-5">
                                    <div class="sensor-bar-track w-full">
                                        <div class="sensor-bar-fill {{ $sensorTone }}"
                                            style="width: {{ (int) $sensor['pct'] }}%"></div>
                                    </div>
                                </div>

                                <span
                                    class="sensor-status {{ $sensorTone }} mt-4 inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide">
                                    {{ ucfirst($sensor['status']) }}
                                </span>

                            </div>
                            <button type="button"
                                class="mt-5 absolute cursor-pointer bottom-2 right-2 inline-flex items-center justify-center gap-2 rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-sky-600"
                                onclick="openCalibrationModal('{{ addslashes($sensor['label']) }}', '{{ addslashes($sensor['value']) }}')">
                                <i class="fas fa-vial"></i>
                            </button>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_0.6fr]">
                <div class="rounded-4xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="flex items-center gap-3 text-xl font-bold text-slate-900">
                                <i class="fas fa-chart-line text-sky-500"></i>
                                Grafik Tren Data Sensor
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">Tampilan chart tetap memakai konfigurasi Alpine dan
                                Chart.js dari layout yang sudah ada.</p>
                        </div>
                    </div>
                    <div class="mb-4 flex flex-wrap gap-2" x-data="locationCharts(@js($deviceDataMonitoring))">
                        <div class="flex gap-2">
                            <button type="button" @click="range = '6'"
                                :class="range === '6' ? 'bg-emerald-500 text-white border-emerald-500' :
                                    'bg-white text-slate-600 border-slate-200'"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition">6 Jam</button>
                            <button type="button" @click="range = '12'"
                                :class="range === '12' ? 'bg-emerald-500 text-white border-emerald-500' :
                                    'bg-white text-slate-600 border-slate-200'"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition">12 Jam</button>
                            <button type="button" @click="range = '24'"
                                :class="range === '24' ? 'bg-emerald-500 text-white border-emerald-500' :
                                    'bg-white text-slate-600 border-slate-200'"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition">24 Jam</button>
                        </div>
                        <div class="mt-4 h-90 w-full rounded-3xl border border-slate-200 bg-slate-50 p-3">
                            <canvas x-ref="chartCanvas" class="h-full w-full"></canvas>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-4xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Laporkan Masalah</h3>
                                <p class="text-sm text-slate-500">WhatsApp</p>
                            </div>
                        </div>
                        <p class="text-sm leading-6 text-slate-600">
                            Jika Anda menemukan masalah atau ingin melaporkan kondisi device ini, hubungi admin melalui
                            WhatsApp.
                        </p>
                        <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                            <p><span class="font-semibold">Device:</span> {{ $deviceName }}</p>
                            <p class="mt-1"><span class="font-semibold">Lokasi:</span> {{ $deviceAddress }}</p>
                            <p class="mt-1"><span class="font-semibold">Status:</span> <span
                                    id="report-status">{{ $deviceStatus }}</span></p>
                            <p class="mt-1"><span class="font-semibold">Skor:</span> <span
                                    id="report-score">{{ $deviceScore }}</span>/100</p>
                        </div>
                        <a href="https://wa.me/6281234567890?text={{ rawurlencode('Halo Admin EQUITY UP, saya ingin melaporkan kondisi di ' . $deviceAddress . ' dengan status ' . $deviceStatus . '.') }}"
                            target="_blank"
                            class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                            <i class="fab fa-whatsapp"></i>
                            Kirim Laporan
                        </a>
                    </div>
                </aside>
            </section>
        </div>
    </div>

    <div id="calibrationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm modal-transition" onclick="if(event.target === this) closeCalibrationModal()">
        <div class="relative mx-4 w-full max-w-xl overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-900 px-6 py-4">
                <div>
                    <h3 id="modalTitle" class="text-lg font-bold text-white">Kalibrasi Sensor</h3>
                    <p id="modalSensorLabel" class="text-xs text-slate-300">-</p>
                </div>
                <button onclick="closeCalibrationModal()" class="text-slate-400 transition-colors hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="calibrationForm" class="space-y-4 px-6 py-5" onsubmit="saveCalibration(event)">
                @csrf
                <input type="hidden" id="sensorType" name="sensor_type">
                <input type="hidden" id="currentValue" name="current_value">

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Referensi Kalibrasi</label>
                    <input type="number" step="any" id="referenceValue" name="reference_value" class="w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Masukkan nilai referensi" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Nilai Offset / Koreksi</label>
                    <input type="number" step="any" id="offsetValue" name="offset_value" class="w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Opsional">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Catatan</label>
                    <textarea id="calibrationNotes" name="notes" rows="3" class="w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100" placeholder="Opsional"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-4">
                    <button type="button" onclick="closeCalibrationModal()" class="rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Batal</button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-600">
                        <i class="fas fa-save"></i>
                        Simpan ke Firebase
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
@endsection

@section('script')
    <script>
        (function() {
            const root = document.querySelector('[data-device-api]');
            if (!root) return;

            const apiUrl = root.dataset.deviceApi;
            const pollInterval = 5000;
            let lastSignature = '';

            function statusClass(status) {
                return status === 'normal' ? 'good' : (status === 'waspada' ? 'medium' : 'bad');
            }

            async function fetchDeviceDetail() {
                try {
                    const response = await fetch(`${apiUrl}?_=${Date.now()}`, {
                        cache: 'no-store'
                    });
                    if (!response.ok) throw new Error('Failed to fetch device detail');
                    return await response.json();
                } catch (error) {
                    console.warn('Device polling error:', error);
                    return null;
                }
            }

            function updateHeader(data) {
                const title = document.querySelector('h1');
                const deviceStatusLabel = document.getElementById('device-status-label');
                const reportStatus = document.getElementById('report-status');
                const reportScore = document.getElementById('report-score');
                const scoreBadge = document.getElementById('score-badge');
                const scoreValue = scoreBadge?.querySelector('.text-5xl');
                const scoreStatus = scoreBadge?.querySelector('p:last-child');

                if (title && data.device_name) title.textContent = data.device_name;
                if (deviceStatusLabel && data.status) deviceStatusLabel.textContent = data.status;
                if (reportStatus && data.status) reportStatus.textContent = data.status;
                if (reportScore && typeof data.condition_score !== 'undefined') reportScore.textContent = data
                    .condition_score;
                if (scoreValue && typeof data.condition_score !== 'undefined') scoreValue.textContent = data
                    .condition_score;
                if (scoreStatus && data.status) {
                    scoreStatus.textContent = data.status;
                    scoreStatus.className =
                        `mt-2 text-sm font-semibold uppercase tracking-[0.2em] text-${statusClass(data.status) === 'good' ? 'emerald' : (statusClass(data.status) === 'medium' ? 'amber' : 'rose')}-600`;
                }
            }

            function updateSensorCards(data) {
                if (!Array.isArray(data.sensors)) return;

                data.sensors.forEach((sensor) => {
                    const card = document.querySelector(`[data-sensor-label="${sensor.label}"]`);
                    if (!card) return;

                    const valueEl = card.querySelector('.sensor-value');
                    const statusEl = card.querySelector('.sensor-status');
                    const barEl = card.querySelector('.sensor-bar-fill');
                    const unitEl = card.querySelector('.sensor-unit');

                    if (valueEl) {
                        valueEl.innerHTML =
                            `${sensor.value ?? ''}<span class="sensor-unit ml-1 text-sm font-semibold text-slate-900">${sensor.unit ?? ''}</span>`;
                    }

                    if (statusEl) {
                        const tone = statusClass(sensor.status);
                        statusEl.textContent = sensor.status.charAt(0).toUpperCase() + sensor.status.slice(1);
                        statusEl.className =
                            `sensor-status ${tone} mt-4 inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide`;
                    }

                    if (barEl) {
                        const tone = statusClass(sensor.status);
                        barEl.className = `sensor-bar-fill ${tone}`;
                        barEl.style.width = `${sensor.pct}%`;
                    }
                });
            }

            async function refresh() {
                const newData = await fetchDeviceDetail();
                if (!newData) return;

                const signature = JSON.stringify(newData);
                if (signature === lastSignature) return;

                updateHeader(newData);
                updateSensorCards(newData);
                lastSignature = signature;
            }

            refresh();
            setInterval(refresh, pollInterval);
        })();

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        function showToast(icon, title, text) {
            if (!window.Swal) {
                alert(text || title);
                return;
            }

            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon,
                title,
                text,
            });
        }

        function getSensorType(label) {
            const lowerLabel = label.toLowerCase();
            if (lowerLabel.includes('ph')) return 'ph';
            if (lowerLabel.includes('tds') || lowerLabel.includes('total dissolved solids')) return 'tds';
            if (lowerLabel.includes('dissolved oxygen') || lowerLabel.includes('do')) return 'do';
            if (lowerLabel.includes('temperatur') || lowerLabel.includes('suhu')) return 'temp';
            if (lowerLabel.includes('turbidity') || lowerLabel.includes('kekeruhan')) return 'turbidity';
            return 'default';
        }

        window.openCalibrationModal = function(sensorLabel, currentValue, unit = '') {
            const modal = document.getElementById('calibrationModal');
            document.getElementById('modalTitle').innerHTML = `Kalibrasi ${sensorLabel}`;
            document.getElementById('modalSensorLabel').innerHTML = `Sensor ${sensorLabel} | Nilai saat ini: ${currentValue} ${unit || ''}`;
            document.getElementById('sensorType').value = getSensorType(sensorLabel);
            document.getElementById('currentValue').value = currentValue;
            document.getElementById('referenceValue').value = currentValue || '';
            document.getElementById('offsetValue').value = '';
            document.getElementById('calibrationNotes').value = '';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        };

        window.closeCalibrationModal = function() {
            const modal = document.getElementById('calibrationModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        };

        window.saveCalibration = async function(event) {
            if (event) event.preventDefault();

            const root = document.querySelector('[data-device-api]');
            const deviceCode = root?.dataset.deviceCode;
            const apiUrl = `{{ route('api.device.calibration.store', ['device' => $device, 'id' => $deviceDataInfo['device_code'] ?? request()->route('id')]) }}`;

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        sensor_label: document.getElementById('modalSensorLabel').textContent.replace(/^Sensor\s+/, '').split('|')[0].trim(),
                        sensor_type: document.getElementById('sensorType').value,
                        current_value: document.getElementById('currentValue').value,
                        reference_value: document.getElementById('referenceValue').value,
                        offset_value: document.getElementById('offsetValue').value,
                        notes: document.getElementById('calibrationNotes').value,
                        device_code: deviceCode,
                    }),
                });

                const payload = await response.json();

                if (!response.ok) {
                    throw new Error(payload.details || payload.error || 'Gagal menyimpan kalibrasi');
                }

                showToast('success', 'Berhasil', payload.message || 'Data kalibrasi berhasil dikirim ke Firebase.');
                window.closeCalibrationModal();
            } catch (error) {
                showToast('error', 'Gagal', error.message || 'Terjadi kesalahan saat menyimpan kalibrasi.');
            }
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCalibrationModal();
            }
        });
    </script>
@endsection
