<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DeveloperProfileController extends Controller
{
    public function __invoke(): View
    {
        $team = [
            [
                'name' => 'Tim EQUAPP',
                'role' => 'Full-stack & Instrumentasi',
                'initials' => 'EQ',
                'instagram' => '@equapp.mbkm',
            ],
            [
                'name' => 'Koordinator Lapangan',
                'role' => 'Integrasi sensor & QA data',
                'initials' => 'KL',
                'instagram' => '@equapp.lapangan',
            ],
            [
                'name' => 'Analis Lingkungan',
                'role' => 'Model rekomendasi & dashboard',
                'initials' => 'AL',
                'instagram' => '@equapp.analis',
            ],
        ];

        $program = [
            'title' => 'Merdeka Belajar � Kampus Merdeka (MBKM)',
            'body' => 'Program MBKM memberi ruang mahasiswa untuk belajar di luar kurikulum inti melalui proyek nyata. EQUAPP adalah contoh proyek monitoring lingkungan terintegrasi yang menghubungkan perangkat lapangan, visualisasi data, dan partisipasi masyarakat.',
            'institution' => 'Perguruan Tinggi Mitra & Dinas Terkait',
            'period' => 'Agustus 2025 � Januari 2026',
        ];

        return view('public.developer', compact('team', 'program'));
    }
}
