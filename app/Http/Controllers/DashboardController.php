<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index(?string $module = null)
    {
        // $module = in_array($module, ['aquaviska', 'climeet'], true) ? $module : 'aquaviska';

        // $modules = [
        //     'aquaviska' => [
        //         'key' => 'aquaviska',
        //         'label' => 'AquaViska',
        //         'title' => 'AquaViska Monitoring',
        //         'subtitle' => 'Dashboard kualitas air tambak perikanan',
        //         'icon' => 'fa-solid fa-water',
        //         'theme' => [
        //             'badge' => 'border-cyan-200 bg-cyan-50 text-cyan-700',
        //             'button' => 'border-cyan-200 bg-cyan-50 text-cyan-800 hover:bg-cyan-100',
        //             'iconBg' => 'bg-cyan-50 text-cyan-700',
        //             'sectionTitle' => 'text-cyan-700',
        //             'pill' => 'border-cyan-200 bg-cyan-50 text-cyan-700',
        //             'graph' => 'from-cyan-400 to-cyan-600',
        //         ],
        //         'summaryIcons' => [
        //             'fa-solid fa-microchip',
        //             'fa-solid fa-circle-check',
        //             'fa-solid fa-triangle-exclamation',
        //             'fa-solid fa-screwdriver-wrench',
        //         ],
        //         'summary' => [
        //             ['label' => 'Total alat', 'value' => 24, 'note' => 'Semua node IoT aktif di tambak dan area iklim'],
        //             ['label' => 'Alat aktif', 'value' => 18, 'note' => 'Perangkat yang mengirim data normal'],
        //             ['label' => 'Alat rusak', 'value' => 3, 'note' => 'Perlu inspeksi lapangan dan penggantian komponen'],
        //             ['label' => 'Kerusakan', 'value' => 5, 'note' => 'Terdeteksi dari tegangan, delay data, dan drift sensor'],
        //         ],
        //         'ai_summary' => 'AI menilai kondisi tambak masih aman, namun aerator cadangan perlu disiapkan karena DO mulai turun pada jam malam.',
        //         'regions' => [
        //             ['name' => 'Tambak Barat', 'value' => 86, 'detail' => 'Stabil dan cocok untuk panen bertahap'],
        //             ['name' => 'Tambak Timur', 'value' => 74, 'detail' => 'Perlu monitoring turbidity lebih sering'],
        //             ['name' => 'Tambak Utara', 'value' => 61, 'detail' => 'DO menurun pada malam hari'],
        //             ['name' => 'Tambak Selatan', 'value' => 79, 'detail' => 'Kondisi masih aman'],
        //         ],
        //         'slides' => [
        //             ['title' => 'Aerator cadangan', 'value' => 'Siaga', 'detail' => 'Aktifkan jika DO di bawah 5 mg/L.', 'icon' => 'fa-solid fa-fan', 'badgeClass' => 'bg-emerald-50 text-emerald-700'],
        //             ['title' => 'Kualitas air', 'value' => 'Waspada', 'detail' => 'Turbidity naik setelah hujan deras.', 'icon' => 'fa-solid fa-droplet', 'badgeClass' => 'bg-sky-50 text-sky-700'],
        //             ['title' => 'Kalibrasi sensor', 'value' => '7 hari', 'detail' => 'Jadwal kalibrasi rutin sensor air.', 'icon' => 'fa-solid fa-sliders', 'badgeClass' => 'bg-amber-50 text-amber-700'],
        //         ],
        //         'chart' => [
        //             ['label' => 'Suhu Air', 'value' => 86],
        //             ['label' => 'pH', 'value' => 74],
        //             ['label' => 'DO', 'value' => 61],
        //             ['label' => 'Turbidity', 'value' => 43],
        //             ['label' => 'TDS', 'value' => 58],
        //         ],
        //         'devices' => [
        //             ['name' => 'Node Tambak Barat', 'status' => 'Aktif', 'statusClass' => 'bg-emerald-100 text-emerald-700', 'detail' => 'Sinkron 2 menit lalu'],
        //             ['name' => 'Node Tambak Timur', 'status' => 'Waspada', 'statusClass' => 'bg-amber-100 text-amber-700', 'detail' => 'Turbidity naik 18%'],
        //             ['name' => 'Node Aerator', 'status' => 'Rusak', 'statusClass' => 'bg-rose-100 text-rose-700', 'detail' => 'Arus tidak stabil'],
        //             ['name' => 'Gateway Utama', 'status' => 'Aktif', 'statusClass' => 'bg-emerald-100 text-emerald-700', 'detail' => 'Uptime 99.6%'],
        //         ],
        //         'damage' => [
        //             ['label' => 'Sensor DO', 'value' => '2 kasus', 'desc' => 'Kemungkinan fouling atau probe menua'],
        //             ['label' => 'Sensor pH', 'value' => '1 kasus', 'desc' => 'Perlu kalibrasi ulang mingguan'],
        //             ['label' => 'Kabel dan konektor', 'value' => '2 kasus', 'desc' => 'Korosi dan sambungan longgar'],
        //         ],
        //         'mitigation' => [
        //             'Kalibrasi sensor air setiap 7 hari untuk menjaga akurasi pembacaan.',
        //             'Siapkan aerator cadangan ketika DO turun di bawah ambang aman.',
        //             'Bersihkan probe dari lumut dan endapan agar drift sensor menurun.',
        //             'Pisahkan jalur listrik dari area lembap untuk mengurangi korosi konektor.',
        //         ],
        //         'alerts' => [
        //             'DO malam hari cenderung turun, aktifkan aerator otomatis pada rentang 22.00-05.00.',
        //             'Turbidity meningkat setelah hujan, lakukan aerasi dan cek sirkulasi air.',
        //             'Sensor aerator menunjukkan arus tidak stabil, jadwalkan penggantian relay.',
        //         ],
        //     ],
        //     'climeet' => [
        //         'key' => 'climeet',
        //         'label' => 'Climeet',
        //         'title' => 'Climeet Monitoring',
        //         'subtitle' => 'Analisis cuaca dan kegiatan luar ruang yang aman',
        //         'icon' => 'fa-solid fa-cloud-sun',
        //         'theme' => [
        //             'badge' => 'border-orange-200 bg-orange-50 text-orange-700',
        //             'button' => 'border-orange-200 bg-orange-50 text-orange-800 hover:bg-orange-100',
        //             'iconBg' => 'bg-orange-50 text-orange-700',
        //             'sectionTitle' => 'text-orange-700',
        //             'pill' => 'border-orange-200 bg-orange-50 text-orange-700',
        //             'graph' => 'from-orange-400 to-orange-600',
        //         ],
        //         'summaryIcons' => [
        //             'fa-solid fa-satellite-dish',
        //             'fa-solid fa-circle-check',
        //             'fa-solid fa-triangle-exclamation',
        //             'fa-solid fa-cloud-rain',
        //         ],
        //         'summary' => [
        //             ['label' => 'Total alat', 'value' => 18, 'note' => 'Stasiun cuaca dan node iklim yang aktif'],
        //             ['label' => 'Alat aktif', 'value' => 13, 'note' => 'Perangkat yang membaca cuaca normal'],
        //             ['label' => 'Alat rusak', 'value' => 2, 'note' => 'Perlu cek anemometer dan logger'],
        //             ['label' => 'Kerusakan', 'value' => 4, 'note' => 'Terdeteksi dari UV, angin, dan sinkronisasi data'],
        //         ],
        //         'ai_summary' => 'AI menyarankan aktivitas luar ruang ringan pada pagi-siang hari, sedangkan sore menjelang malam sebaiknya hindari kegiatan dengan paparan UV tinggi dan potensi hujan.',
        //         'regions' => [
        //             ['name' => 'Pesisir Utara', 'value' => 79, 'detail' => 'UV tinggi pada siang hari'],
        //             ['name' => 'Pesisir Timur', 'value' => 61, 'detail' => 'Angin cukup stabil'],
        //             ['name' => 'Dataran Tengah', 'value' => 72, 'detail' => 'Aktivitas luar ruang masih aman'],
        //             ['name' => 'Perbukitan Barat', 'value' => 55, 'detail' => 'Peluang hujan meningkat'],
        //         ],
        //         'slides' => [
        //             ['title' => 'Paparan UV', 'value' => 'Tinggi', 'detail' => 'Kurangi aktivitas di siang hari.', 'icon' => 'fa-solid fa-sun', 'badgeClass' => 'bg-amber-50 text-amber-700'],
        //             ['title' => 'Arah angin', 'value' => 'Stabil', 'detail' => 'Cocok untuk aktivitas ringan di luar.', 'icon' => 'fa-solid fa-wind', 'badgeClass' => 'bg-sky-50 text-sky-700'],
        //             ['title' => 'Hujan', 'value' => 'Potensi naik', 'detail' => 'Tunda kegiatan lapangan sensitif.', 'icon' => 'fa-solid fa-cloud-rain', 'badgeClass' => 'bg-emerald-50 text-emerald-700'],
        //         ],
        //         'chart' => [
        //             ['label' => 'UV Index', 'value' => 79],
        //             ['label' => 'Curah Hujan', 'value' => 34],
        //             ['label' => 'Kelembapan', 'value' => 72],
        //             ['label' => 'Kecepatan Angin', 'value' => 55],
        //             ['label' => 'CO2 / TVOC', 'value' => 47],
        //         ],
        //         'devices' => [
        //             ['name' => 'Stasiun Cuaca Utama', 'status' => 'Aktif', 'statusClass' => 'bg-emerald-100 text-emerald-700', 'detail' => 'Data stabil dan terkalibrasi'],
        //             ['name' => 'Sensor UV', 'status' => 'Waspada', 'statusClass' => 'bg-amber-100 text-amber-700', 'detail' => 'Nilai tinggi pada siang hari'],
        //             ['name' => 'Rain Gauge', 'status' => 'Aktif', 'statusClass' => 'bg-emerald-100 text-emerald-700', 'detail' => 'Rekaman hujan normal'],
        //             ['name' => 'Anemometer', 'status' => 'Rusak', 'statusClass' => 'bg-rose-100 text-rose-700', 'detail' => 'Pembacaan angin fluktuatif'],
        //         ],
        //         'damage' => [
        //             ['label' => 'UV sensor', 'value' => '1 kasus', 'desc' => 'Perlu cek housing dan lensa'],
        //             ['label' => 'Anemometer', 'value' => '2 kasus', 'desc' => 'Kemungkinan bearing aus'],
        //             ['label' => 'Data logger', 'value' => '1 kasus', 'desc' => 'Sinkronisasi lambat saat hujan'],
        //         ],
        //         'mitigation' => [
        //             'Hindari aktivitas berat saat UV Index tinggi, terutama di atas 8.',
        //             'Pindahkan kegiatan lapangan ke pagi atau setelah matahari turun.',
        //             'Gunakan penutup sensor untuk mengurangi paparan air hujan langsung.',
        //             'Periksa baterai dan koneksi data logger sebelum cuaca buruk.',
        //         ],
        //         'alerts' => [
        //             'Paparan UV tinggi, gunakan pelindung bila harus berada di luar.',
        //             'Kecepatan angin naik, amankan peralatan ringan di area terbuka.',
        //             'Potensi hujan meningkat, tunda pekerjaan lapangan yang sensitif.',
        //         ],
        //     ],
        // ];

        // $dashboard = $modules[$module];

        // return view('dashboard', compact('dashboard'));
    }
}