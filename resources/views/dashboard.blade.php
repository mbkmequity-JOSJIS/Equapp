@extends('layouts.app')
@section('title', 'Dashboard Module')
@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('style')
    <style>

        body {
            background: #fff;
            color: #222;
            overflow-x: hidden;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }


        /* CONTENT */
        .content {
            width: 100%;
        }

        section {
            min-height: 100vh;
            padding: 0 40px;
            display: none;
            animation: fade .4s ease;
        }

        section.active {
            display: block;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-bar {
            height: 35px;
            line-height: 35px;
            color: white;
            font-weight: bold;
            letter-spacing: 2px;
            padding-left: 35px;
            margin-bottom: 50px;
        }

        .blue {
            background: linear-gradient(90deg, #6ee89a, #95d6f4);
        }

        .green {
            background: linear-gradient(90deg, #95d6f4, #6ee89a);
        }

        .orange {
            background: linear-gradient(90deg, #6ee89a, #ffc176);
        }

        /* SDGS */
        .sdgs-wrap {
            display: flex;
            align-items: center;
            gap: 70px;
            background: #9bd8f2;
            padding: 90px 70px;
            min-height: 85vh;
        }

        .sdgs-wrap img {
            width: 420px;
        }

        .sdgs-text h1 {
            font-size: 58px;
            color: white;
        }

        .sdgs-text h3 {
            font-size: 28px;
            color: #3e5870;
            margin-bottom: 30px;
        }

        .sdgs-text p {
            font-size: 26px;
            line-height: 1.45;
            color: white;
            max-width: 850px;
        }


        .indicator-section {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
            margin-bottom: 40px;
        }

        .indicator-section h2 {
            font-size: 28px;
            margin-bottom: 12px;
            color: #0f172a;
        }

        .indicator-section p {
            color: #475569;
            line-height: 1.75;
            margin-bottom: 30px;
            max-width: 760px;
        }

        .indicator-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            color: #0f172a;
        }

        .indicator-table th,
        .indicator-table td {
            padding: 16px 18px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            vertical-align: top;
        }

        .indicator-table th {
            background: #f8fafc;
            color: #334155;
            font-weight: 700;
        }

        .indicator-table tr:hover {
            background: #f8fafc;
        }

        .indicator-category {
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        .status-chip.green {
            background: #dcfce7;
            color: #166534;
        }

        .status-chip.yellow {
            background: #fef9c3;
            color: #92400e;
        }

        .status-chip.red {
            background: #fee2e2;
            color: #b91c1c;
        }

    </style>
@endsection



@section('content')
    <main class="content">

        <section id="feature" class="active">
            <div class="sdgs-wrap">
                <div class="sdgs-text">
                    <h1>EQUApp</h1>
                    <h3>Environmental Quality Application</h3>
                    <p>
                        A web-based IoT monitoring platform for real-time environmental
                        quality tracking, especially water and air quality.
                    </p>
                </div>
            </div>
        </section>

        <section id="indikator" class="active">

            <div class="indicator-section">
                <h2>Daftar Indikator Sensor</h2>
                <p>Semua indikator ditulis dengan satuan yang umum digunakan dan penjelasan sederhana agar mudah
                    dimengerti oleh pengguna dashboard.</p>

                <table class="indicator-table">
                    <thead>
                        <tr>
                            <th>Indikator</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="indicator-category">
                            <td colspan="4">AQUA VISKA – Sensor Kualitas Air</td>
                        </tr>
                        <tr>
                            <td>Suhu Air</td>
                            <td>°C</td>
                            <td>Temperatur air permukaan yang diukur di lokasi.</td>
                            <td><span class="status-chip green">Normal</span></td>
                        </tr>
                        <tr>
                            <td>pH</td>
                            <td>skala 0–14</td>
                            <td>Tingkat keasaman atau kebasaan air tanpa satuan.</td>
                            <td><span class="status-chip green">Normal</span></td>
                        </tr>
                        <tr>
                            <td>Kekeruhan (Turbidity)</td>
                            <td>NTU</td>
                            <td>Seberapa keruh air; nilai lebih tinggi berarti air lebih keruh.</td>
                            <td><span class="status-chip yellow">Waspada</span></td>
                        </tr>
                        <tr>
                            <td>Dissolved Oxygen (DO)</td>
                            <td>mg/L</td>
                            <td>Jumlah oksigen terlarut yang tersedia di dalam air.</td>
                            <td><span class="status-chip green">Normal</span></td>
                        </tr>
                        <tr>
                            <td>Total Dissolved Solids (TDS)</td>
                            <td>ppm</td>
                            <td>Kadar mineral dan zat terlarut dalam air.</td>
                            <td><span class="status-chip yellow">Waspada</span></td>
                        </tr>

                        <tr class="indicator-category">
                            <td colspan="4">IOT CLIMATE – Sensor Kualitas Udara & Iklim</td>
                        </tr>
                        <tr>
                            <td>Suhu Udara</td>
                            <td>°C</td>
                            <td>Temperatur udara di sekitar lokasi sensor.</td>
                            <td><span class="status-chip green">Normal</span></td>
                        </tr>
                        <tr>
                            <td>Kelembapan</td>
                            <td>% RH</td>
                            <td>Persentase uap air di udara.</td>
                            <td><span class="status-chip green">Normal</span></td>
                        </tr>
                        <tr>
                            <td>TVOC</td>
                            <td>mg/m³</td>
                            <td>Kadar senyawa organik volatil di udara.</td>
                            <td><span class="status-chip yellow">Waspada</span></td>
                        </tr>
                        <tr>
                            <td>CO₂</td>
                            <td>ppm</td>
                            <td>Kadar karbon dioksida di udara.</td>
                            <td><span class="status-chip yellow">Waspada</span></td>
                        </tr>
                        <tr>
                            <td>UV Index</td>
                            <td>skala</td>
                            <td>Intensitas sinar ultraviolet yang mencapai permukaan.</td>
                            <td><span class="status-chip red">Tinggi</span></td>
                        </tr>
                        <tr>
                            <td>Kecepatan Angin</td>
                            <td>m/s</td>
                            <td>Kecepatan angin di sekitar area sensor.</td>
                            <td><span class="status-chip green">Normal</span></td>
                        </tr>
                        <tr>
                            <td>Curah Hujan</td>
                            <td>mm</td>
                            <td>Jumlah hujan yang tercatat dalam periode tertentu.</td>
                            <td><span class="status-chip green">Normal</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>


    </main>
@endsection
