@extends('layouts.public')

@section('title', 'Lokasi & Perangkat � '.config('app.name'))
@section('heading', 'Lokasi & Perangkat')

@section('content')
    <div
        class="rounded-3xl border border-white/15 bg-white/10 p-6 shadow-glass backdrop-blur-xl"
        x-data="{ activeTab: 'semua' }"
    >
        <p class="text-sm text-white/70">Filter berdasarkan tipe perangkat sesuai analisis fungsional.</p>
        <div class="mt-4 flex flex-wrap gap-2">
            <button
                type="button"
                class="rounded-full border px-4 py-2 text-xs font-semibold transition"
                :class="activeTab === 'semua' ? 'border-white/40 bg-white/20 text-white' : 'border-white/15 bg-white/5 text-white/70 hover:bg-white/10'"
                @click="activeTab = 'semua'"
            >
                Semua
            </button>
            <button
                type="button"
                class="rounded-full border px-4 py-2 text-xs font-semibold transition"
                :class="activeTab === 'aquaviska' ? 'border-white/40 bg-white/20 text-white' : 'border-white/15 bg-white/5 text-white/70 hover:bg-white/10'"
                @click="activeTab = 'aquaviska'"
            >
                AQUAVISKA
            </button>
            <button
                type="button"
                class="rounded-full border px-4 py-2 text-xs font-semibold transition"
                :class="activeTab === 'iot' ? 'border-white/40 bg-white/20 text-white' : 'border-white/15 bg-white/5 text-white/70 hover:bg-white/10'"
                @click="activeTab = 'iot'"
            >
                IOT Climate
            </button>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2">
            @foreach ($locations as $loc)
                <div
                    x-show="activeTab === 'semua' || (activeTab === 'aquaviska' && @js($loc['type']) === 'AQUAVISKA') || (activeTab === 'iot' && @js($loc['type']) === 'IOT Climate')"
                    x-transition.opacity.duration.200ms
                >
                    <x-location-card
                        :id="$loc['id']"
                        :name="$loc['name']"
                        :type="$loc['type']"
                        :status="$loc['status']"
                        :address="$loc['address'] ?? null"
                    />
                </div>
            @endforeach
        </div>
    </div>
@endsection
