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
        $heroImage = asset('/img_loc/dummy_loc (1).jpg');
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
                                        {{  $sensor['label'] }}</p>
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
                                onclick="openCalibrationForm(@js($sensor['label']))">
                                <i class="fas fa-vial"></i>

                            </button>
                        </article>
                    @endforeach
                </div>
            </section>

            <div id="calibrationFormModal"
                class="modal-overlay fixed inset-0 z-50 hidden bg-slate-950/70 p-4 backdrop-blur-sm">
                <div class="modal w-full max-w-5xl overflow-y-auto rounded-4xl bg-white p-6 shadow-2xl sm:p-8">
                    <div
                        class="modal-header mb-6 flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900"><i class="fas fa-flask mr-2 text-sky-500"></i>
                                Form Kalibrasi Sensor</h2>
                            <p class="mt-2 text-sm text-slate-500">Isi data kalibrasi untuk setiap sensor agar hasil dapat
                                ditrack dan tervalidasi.</p>
                        </div>
                        <button type="button"
                            class="modal-close inline-flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-slate-700 transition hover:bg-slate-200"
                            onclick="closeCalibrationForm()"><i class="fas fa-times"></i></button>
                    </div>
                    <form id="calibrationForm" action="#" method="post"
                        onsubmit="event.preventDefault(); submitCalibrationForm();">
                        <div class="space-y-4">
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <h3 class="mb-4 text-lg font-semibold text-slate-900">1. Informasi Umum</h3>
                                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">ID Sensor<input
                                            type="text" name="sensor_id" placeholder="Masukkan ID Sensor"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Nama Sensor<input
                                            type="text" name="sensor_name" placeholder="Nama Sensor"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Jenis
                                        Sensor<select name="sensor_type"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required>
                                            <option value="">Pilih jenis sensor</option>
                                            <option value="pH">pH</option>
                                            <option value="DO">DO</option>
                                            <option value="Suhu">Suhu</option>
                                            <option value="TDS">TDS</option>
                                            <option value="Kelembapan">Kelembapan</option>
                                            <option value="TVOC">TVOC</option>
                                            <option value="CO2">CO₂</option>
                                            <option value="UV">UV Index</option>
                                            <option value="Angin">Kecepatan Angin</option>
                                            <option value="Curah Hujan">Curah Hujan</option>
                                        </select></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Lokasi
                                        Sensor<input type="text" name="sensor_location" value="{{ $deviceAddress }}"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Tanggal
                                        Kalibrasi<input type="date" name="calibration_date"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Waktu
                                        Kalibrasi<input type="time" name="calibration_time"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Nama Teknisi /
                                        Operator<input type="text" name="technician" placeholder="Nama teknisi"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required></label>
                                </div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <h3 class="mb-4 text-lg font-semibold text-slate-900">2. Parameter Kalibrasi</h3>
                                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Metode
                                        Kalibrasi<select name="calibration_method"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required>
                                            <option value="">Pilih metode</option>
                                            <option value="Manual">Manual</option>
                                            <option value="Otomatis">Otomatis</option>
                                            <option value="Multi-point">Multi-point</option>
                                        </select></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Jumlah Titik
                                        Kalibrasi<select name="points"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required>
                                            <option value="">Pilih jumlah</option>
                                            <option value="1">1 titik</option>
                                            <option value="2">2 titik</option>
                                            <option value="3">3 titik</option>
                                        </select></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Nilai
                                        Referensi<input type="text" name="reference_value"
                                            placeholder="Contoh: pH 4.00 / DO 8 mg/L"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Nilai Sensor
                                        (Sebelum)<input type="text" name="before_value"
                                            placeholder="Nilai sebelum kalibrasi"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Nilai Sensor
                                        (Setelah)<input type="text" name="after_value"
                                            placeholder="Nilai setelah kalibrasi"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required></label>
                                </div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <h3 class="mb-4 text-lg font-semibold text-slate-900">3. Perhitungan Kalibrasi</h3>
                                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Error
                                        (Selisih)<input type="text" name="error_value"
                                            placeholder="Sensor - Referensi"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Correction Factor
                                        / Offset<input type="text" name="offset"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Slope (jika
                                        linear calibration)<input type="text" name="slope"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></label>
                                </div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <h3 class="mb-4 text-lg font-semibold text-slate-900">4. Hasil Kalibrasi</h3>
                                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Status
                                        Kalibrasi<select name="calibration_status"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                                            required>
                                            <option value="">Pilih status</option>
                                            <option value="Berhasil">Berhasil</option>
                                            <option value="Gagal">Gagal</option>
                                        </select></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Akurasi (%
                                        )<input type="number" name="accuracy" min="0" max="100"
                                            step="0.1" placeholder="Contoh 98.5"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Toleransi<input
                                            type="text" name="tolerance" placeholder="Contoh ±0.2"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></label>
                                    <label
                                        class="flex flex-col gap-2 text-sm font-medium text-slate-700 md:col-span-2 xl:col-span-3">Catatan
                                        Hasil
                                        <textarea name="notes" rows="2" placeholder="Contoh: sensor masih stabil"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></textarea>
                                    </label>
                                </div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <h3 class="mb-4 text-lg font-semibold text-slate-900">5. Kondisi Lingkungan</h3>
                                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Suhu Lingkungan
                                        (°C)<input type="number" name="ambient_temperature" step="0.1"
                                            placeholder="Contoh 25.4"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Kelembapan (%
                                        RH)<input type="number" name="ambient_humidity" step="0.1"
                                            placeholder="Contoh 72"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Kondisi
                                        Air/Udara<select name="environment_condition"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                                            <option value="">Pilih kondisi</option>
                                            <option value="Jernih">Jernih</option>
                                            <option value="Keruh">Keruh</option>
                                            <option value="Hujan">Hujan</option>
                                            <option value="Kering">Kering</option>
                                        </select></label>
                                </div>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <h3 class="mb-4 text-lg font-semibold text-slate-900">6. Riwayat & Validasi</h3>
                                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Kalibrasi
                                        Sebelumnya (tanggal)<input type="date" name="previous_calibration"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Jadwal Kalibrasi
                                        Berikutnya<input type="date" name="next_calibration"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></label>
                                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">Tanda Tangan
                                        Digital / Verifikasi<input type="text" name="verification"
                                            placeholder="Tanda tangan / ID verifikator"
                                            class="rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"></label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex flex-wrap justify-end gap-3">
                            <button type="button"
                                class="rounded-2xl bg-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-300"
                                onclick="closeCalibrationForm()">Batal</button>
                            <button type="submit"
                                class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">Simpan
                                Kalibrasi</button>
                        </div>
                    </form>
                </div>
            </div>

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

                    {{-- <div class="rounded-4xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900">Ringkasan Sensor</h3>
                        <div class="mt-4 space-y-3 text-sm text-slate-600">
                            @foreach ($sensorCards as $sensor)
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3"
                                    data-summary-label="{{ $sensor['label'] }}">
                                    <span class="font-medium text-slate-700">{{ $sensor['label'] }}</span>
                                    <span
                                        class="font-semibold text-slate-900">{{ $sensor['value'] }}{{ $sensor['unit'] ? ' ' . $sensor['unit'] : '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div> --}}
                </aside>
            </section>
        </div>
    </div>
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

            window.openCalibrationForm = function(sensorLabel) {
                const modal = document.getElementById('calibrationFormModal');
                if (!modal) return;
                modal.classList.remove('hidden');
                const sensorName = modal.querySelector('[name="sensor_name"]');
                if (sensorName) sensorName.value = sensorLabel || '';
            };

            window.closeCalibrationForm = function() {
                const modal = document.getElementById('calibrationFormModal');
                if (modal) modal.classList.add('hidden');
            };

            window.submitCalibrationForm = function() {
                alert('Form kalibrasi telah disimpan (simulasi UI). Backend dapat ditambahkan kemudian.');
                window.closeCalibrationForm();
            };

            refresh();
            setInterval(refresh, pollInterval);
        })();
    </script>
@endsection
