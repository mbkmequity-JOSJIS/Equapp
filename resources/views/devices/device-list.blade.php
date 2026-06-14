@extends('layouts.app')
@section('title', 'IOT Device')

@section('content')
    <div class="p-10">
        <div class="header-content p-6 rounded-lg mb-8 shadow-lg">
            <h1 class="header-title text-6xl font-semibold mb-6"><span class="{{ $device == 'aquaviska' ? 'text-sky-500' : 'text-orange-500' }} font-black tracking-widest uppercase">{{ $device }}</span> Device List</h1>
            <p class="header-subtitle text-lg text-gray-600">Explore the status and locations of our IOT devices</p>
        </div>
        <section>
            <div class="location-grid">
                @foreach ($dataDevices as $data)
                    <a href="{{ route('device.detail', ['device' => $device, $data['device_code']]) }}" class="location-card"
                        x-show="activeTab === 'semua' || (activeTab === 'aquaviska' && '{{ $data['type'] }}' === 'AQUAVISKA') || (activeTab === 'iot' && '{{ $data['type'] }}' === 'IOT Climate')"
                        x-cloak>
                        <div class="card-image-wrap">
                            {{-- <img src="{{ asset('storage/img_loc/' . $data['image_url']) }}" alt="{{ $data['name'] }}" class="card-image"> --}}
                            <div class="card-device-badge {{ strtolower(str_replace(' ', '_', $data['type'])) }} uppercase">
                                <i class="fas fa-{{ $data['type'] === 'aquaviska' ? 'water' : 'cloud-sun' }} "></i>
                                {{ $data['type'] }}
                            </div>
                            <div class="card-score-badge"
                                style="--score-color: {{ $data['status'] === 'normal' ? '#22c55e' : ($data['status'] === 'waspada' ? '#f59e0b' : '#ef4444') }}">
                                {{ $data['condition_score'] }}
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">{{ $data['device_name'] }}</h3>
                            <div class="card-meta">
                                <span><i class="fas fa-map-pin"></i>{{ $data['location']['address'] }},
                                    {{ $data['location']['city'] }}</span>
                            </div>
                            <div class="card-quality-row">
                                <span class="quality-label">status</span>
                                <span
                                    class="quality-badge {{ $data['status'] === 'normal' ? 'good' : ($data['status'] === 'waspada' ? 'medium' : 'bad') }}">
                                    {{ $data['status'] }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
@endsection

@section('script')

@endsection
