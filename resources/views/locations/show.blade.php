@extends('layouts.public')

@section('title', $location['name'].' � '.config('app.name'))
@section('heading', 'Detail Lokasi')

@php
    $waText = 'Halo Admin EQUAPP, saya ingin melaporkan kondisi di '.$location['name'].'. Status saat ini: '.$location['status_label'].'.';
    $waLink = 'https://wa.me/'.config('equapp.admin_whatsapp').'?text='.rawurlencode($waText);
    $alertVariant = match ($location['status']) {
        'bahaya', 'offline' => 'danger',
        'waspada' => 'warn',
        default => 'info',
    };
@endphp

@section('content')
    <div class="grid gap-8 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-3xl border border-white/15 bg-white/10 p-6 shadow-glass backdrop-blur-xl">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-white/55">Lokasi</p>
                        <h2 class="mt-1 text-2xl font-bold text-white">{{ $location['name'] }}</h2>
                        <p class="mt-2 text-sm text-white/70">{{ $location['address'] }}</p>
                        <p class="mt-3 text-xs uppercase tracking-wider text-white/45">Tipe alat</p>
                        <p class="text-sm font-semibold text-sky-100">{{ $location['type'] }}</p>
                    </div>
                    <x-badge :type="$location['status']" />
                </div>
            </div>

            <div class="rounded-3xl border border-white/15 bg-white/10 p-6 shadow-glass backdrop-blur-xl">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-white/70">Sensor &amp; indikator</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach ($location['sensors'] as $sensor)
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-white">{{ $sensor['label'] }}</p>
                                <x-badge :type="$sensor['status']" class="!px-2 !py-0.5 !text-[10px]" />
                            </div>
                            <p class="mt-2 text-2xl font-bold text-sky-50">
                                {{ $sensor['value'] }}<span class="text-sm font-medium text-white/60">{{ $sensor['unit'] }}</span>
                            </p>
                            <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-white/10">
                                <div
                                    class="h-full rounded-full bg-gradient-to-r from-[#0055A0] via-[#8CC1E9] to-emerald-300"
                                    style="width: {{ (int) $sensor['pct'] }}%"
                                ></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div
                class="rounded-3xl border border-white/15 bg-white/10 p-6 shadow-glass backdrop-blur-xl"
                x-data="locationCharts(@js($location))"
            >
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-white/70">Grafik tren (demo)</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['6' => '6 jam', '12' => '12 jam', '24' => '24 jam'] as $key => $label)
                            <button
                                type="button"
                                class="rounded-full border px-3 py-1 text-xs font-semibold transition"
                                :class="range === '{{ $key }}' ? 'border-white/45 bg-white/20 text-white' : 'border-white/15 bg-white/5 text-white/70 hover:bg-white/10'"
                                @click="range = '{{ $key }}'"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="relative mt-6 h-72 w-full">
                    <canvas x-ref="chartCanvas" class="max-h-full w-full"></canvas>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-white/15 bg-white/10 p-6 text-center shadow-glass backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-wider text-white/60">Skor kondisi (0�100)</p>
                <div class="mt-4 flex justify-center">
                    <x-circular-score :score="$location['condition_score']" />
                </div>
                <p class="mt-4 text-xs text-white/60">Warna melingkar mengikuti ambang skor: hijau aman, kuning waspada, merah bahaya.</p>
            </div>

            <x-alert :variant="$alertVariant">
                {{ $location['recommendation'] }}
            </x-alert>

            <a
                href="{{ $waLink }}"
                target="_blank"
                rel="noopener noreferrer"
                class="flex w-full items-center justify-center rounded-2xl border border-emerald-300/40 bg-emerald-500/20 px-4 py-3 text-sm font-semibold text-emerald-50 shadow-[0_0_18px_rgba(16,185,129,0.35)] transition hover:bg-emerald-500/30"
            >
                Laporkan via WhatsApp
            </a>

            <a href="{{ route('locations.index') }}" class="block text-center text-sm font-medium text-sky-200/90 underline-offset-4 hover:underline">
                Kembali ke daftar lokasi
            </a>
        </div>
    </div>
@endsection
