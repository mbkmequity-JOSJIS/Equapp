@extends('layouts.app')
@section('title', 'Dashboard Module')

@php
    $activeModule = $dashboard['key'];
    $isAqua = $activeModule === 'aquaviska';
    $isAqua = request()->routeIs('module') && request()->route('module');
    $surfaceClass = $isAqua
        ? 'bg-linear-to-br from-cyan-50 via-white to-sky-50'
        : 'bg-linear-to-br from-orange-50 via-white to-amber-50';
    $badgeClass = $isAqua
        ? 'border-cyan-200 bg-cyan-50 text-cyan-700'
        : 'border-orange-200 bg-orange-50 text-orange-700';
    $buttonClass = $isAqua
        ? 'border-cyan-200 bg-cyan-50 text-cyan-800 hover:bg-cyan-100'
        : 'border-orange-200 bg-orange-50 text-orange-800 hover:bg-orange-100';
    $iconBoxClass = $isAqua ? 'bg-cyan-50 text-cyan-700' : 'bg-orange-50 text-orange-700';
    $sectionTitleClass = $isAqua ? 'text-cyan-700' : 'text-orange-700';
    $pillClass = $isAqua
        ? 'border-cyan-200 bg-cyan-50 text-cyan-700'
        : 'border-orange-200 bg-orange-50 text-orange-700';
    $graphClass = $isAqua ? 'from-cyan-400 to-cyan-600' : 'from-orange-400 to-orange-600';
@endphp

@section('content')
    <div class="relative min-h-screen overflow-hidden bg-linear-to-br {{ $surfaceClass }} text-slate-800">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -left-32 -top-24 h-72 w-72 rounded-full bg-cyan-300/25 blur-3xl"></div>
            <div class="absolute -right-32 top-40 h-80 w-80 rounded-full bg-emerald-300/20 blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/3 h-80 w-80 rounded-full bg-amber-200/25 blur-3xl"></div>
        </div>

        <div class="relative mx-auto px-6 py-8 lg:px-10 lg:py-10">
            <div
                class="mb-6 rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-xl shadow-slate-200/70 backdrop-blur-xl">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div
                            class="inline-flex items-center gap-2 rounded-full {{ $badgeClass }} px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em]">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            Powered by AI
                        </div>
                        <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 lg:text-5xl">
                            Dashboard {{ $dashboard['label'] }}.
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-600 lg:text-base">
                            {{ $dashboard['subtitle'] }}
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3 lg:min-w-105">
                        <a href="#wilayah"
                            class="rounded-2xl border px-4 py-3 text-sm font-semibold transition hover:-translate-y-0.5 {{ $buttonClass }}">
                            <i class="fa-solid fa-map-location-dot mr-2"></i>
                            Grafik Wilayah
                        </a>
                        <a href="#alat"
                            class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:bg-slate-50">
                            <i class="fa-solid fa-microchip mr-2"></i>
                            Status Alat
                        </a>
                        <a href="#mitigasi"
                            class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800 transition hover:-translate-y-0.5 hover:bg-amber-100">
                            <i class="fa-solid fa-shield-halved mr-2"></i>
                            Mitigasi AI
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($dashboard['summary'] as $index => $stat)
                    <div
                        class="rounded-3xl border border-slate-200 bg-white/90 p-5 shadow-lg shadow-slate-200/70 backdrop-blur-sm">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl {{ $iconBoxClass }}">
                                <i class="{{ $dashboard['summaryIcons'][$index] }}"></i>
                            </span>
                        </div>
                        <div class="mt-3 flex items-end justify-between gap-4">
                            <div class="text-4xl font-bold tracking-tight text-slate-900">{{ $stat['value'] }}</div>
                            <span
                                class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">Real
                                time</span>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-500">{{ $stat['note'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                <section id="wilayah"
                    class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-xl shadow-slate-200/70 backdrop-blur-sm">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-200 pb-5">
                        <div>
                            <div
                                class="inline-flex rounded-full {{ $pillClass }} px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em]">
                                <i class="{{ $dashboard['icon'] }} mr-2"></i>
                                {{ $dashboard['label'] }} aktif
                            </div>
                            <h2 class="mt-3 text-2xl font-bold text-slate-900">Grafik data berbagai wilayah</h2>
                            <p class="mt-2 text-sm text-slate-600">Perbandingan kondisi dari beberapa wilayah untuk modul
                                yang sedang aktif.</p>
                        </div>
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            <i class="fa-solid fa-robot mr-2"></i>
                            AI summary aktif
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        @foreach ($dashboard['regions'] as $region)
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $region['name'] }}</p>
                                        <p class="text-sm text-slate-500">{{ $region['detail'] }}</p>
                                    </div>
                                    <span
                                        class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">{{ $region['value'] }}%</span>
                                </div>
                                <div class="h-3 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full rounded-full bg-linear-to-r {{ $graphClass }}"
                                        style="width: {{ $region['value'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <aside class="space-y-6">
                    <section
                        class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-xl shadow-slate-200/70 backdrop-blur-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] {{ $sectionTitleClass }}">
                                    <i class="fa-solid fa-repeat mr-2"></i>Informasi bergantian
                                </p>
                                <h3 class="mt-2 text-2xl font-bold text-slate-900">Kotak informasi wilayah</h3>
                            </div>
                            <span
                                class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">5
                                detik</span>
                        </div>

                        <div class="mt-5 relative min-h-40">
                            @foreach ($dashboard['slides'] as $index => $slide)
                                <div class="info-slide absolute inset-0 rounded-3xl border border-slate-200 bg-slate-50 p-5 transition-all duration-500"
                                    data-slide-index="{{ $index }}"
                                    @if ($index !== 0) style="opacity: 0; transform: translateX(18px); pointer-events: none;"
                                    @else style="opacity: 1; transform: translateX(0);" @endif>
                                    <div class="flex h-full flex-col justify-between gap-5">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <span
                                                    class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $slide['badgeClass'] }}">
                                                    <i class="{{ $slide['icon'] }}"></i>
                                                </span>
                                                <div>
                                                    <p
                                                        class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">
                                                        Kotak {{ $index + 1 }}</p>
                                                    <h4 class="text-xl font-bold text-slate-900">{{ $slide['title'] }}</h4>
                                                </div>
                                            </div>
                                            <span
                                                class="rounded-full px-3 py-1 text-sm font-semibold {{ $slide['badgeClass'] }}">
                                                {{ $slide['value'] }}
                                            </span>
                                        </div>
                                        <p class="text-sm leading-6 text-slate-600">{{ $slide['detail'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 flex items-center justify-center gap-2" id="slide-dots">
                            @foreach ($dashboard['slides'] as $index => $slide)
                                <button type="button" class="slide-dot h-2.5 w-2.5 rounded-full bg-slate-300 transition"
                                    data-dot-index="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                    </section>

                    <section
                        class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-xl shadow-slate-200/70 backdrop-blur-sm">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] {{ $sectionTitleClass }}"><i
                                class="fa-solid fa-wand-magic-sparkles mr-2"></i>Rekomendasi AI</p>
                        <div
                            class="mt-4 rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm leading-6 text-emerald-900">
                            <i class="fa-solid fa-robot mr-2"></i>
                            {{ $dashboard['ai_summary'] }}
                        </div>
                        <div class="mt-4 space-y-3">
                            @foreach ($dashboard['alerts'] as $alert)
                                <div
                                    class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                                    <i
                                        class="fa-solid fa-circle-info mr-2 {{ $isAqua ? 'text-cyan-600' : 'text-orange-600' }}"></i>
                                    {{ $alert }}
                                </div>
                            @endforeach
                        </div>
                    </section>
                </aside>
            </div>


            <section
                class="mt-8 rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-xl shadow-slate-200/70 backdrop-blur-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center  md:justify-between">
                    <div class="flex items-center  gap-3 w-full md:w-auto ">
                        <div class="">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] {{ $sectionTitleClass }}"><i
                                    class="fa-solid fa-compass mr-2"></i>Fokus halaman</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">Buka Menu Perangkat </h3>
                            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                                Dashboard ini sekarang mengikuti menu yang sedang dibuka, sehingga AquaViska dan Climeet
                                tidak tampil bersamaan.
                                Grafik wilayah dan kotak informasi detail ada di sini nantinya.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('devices') }}"
                        class="w-1/2 h-full px-4 py-10 {{ $dashboard['label'] === 'AquaViska' ? 'bg-sky-400/20 hover:bg-sky-400/50' : 'bg-orange-400/20 hover:bg-orange-400/50' }}  font-semibold rounded-3xl flex items-center justify-center transition-all duration-300 hover:-translate-y-2">
                        <div
                            class=" flex items-center justify-center text-white font-semibold tracking-wider text-5xl rounded-3xl">
                            <i class="fa-solid fa-microchip mr-4"></i>
                            @if ($dashboard['label'] === 'Climeet')
                                <i class="fa-solid fa-cloud-sun mr-4"></i>
                            @else
                                <i class="fa-solid fa-water mr-4"></i>
                            @endif
                            <i class="fa-solid fa-location-arrow mr-4"></i>
                            <i class="fa-solid fa-shield-halved mr-4"></i>
                            <span class="uppercase">{{ $dashboard['label'] }}</span>
                        </div>

                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = Array.from(document.querySelectorAll('.info-slide'));
            const dots = Array.from(document.querySelectorAll('.slide-dot'));

            if (!slides.length) {
                return;
            }

            let currentIndex = 0;

            const showSlide = function(index) {
                slides.forEach(function(slide, slideIndex) {
                    const active = slideIndex === index;
                    slide.style.opacity = active ? '1' : '0';
                    slide.style.transform = active ? 'translateX(0)' : 'translateX(18px)';
                    slide.style.pointerEvents = active ? 'auto' : 'none';
                });

                dots.forEach(function(dot, dotIndex) {
                    dot.classList.toggle('bg-slate-700', dotIndex === index);
                    dot.classList.toggle('bg-slate-300', dotIndex !== index);
                    dot.classList.toggle('scale-125', dotIndex === index);
                });
            };

            dots.forEach(function(dot) {
                dot.addEventListener('click', function() {
                    currentIndex = Number(this.dataset.dotIndex || 0);
                    showSlide(currentIndex);
                });
            });

            showSlide(currentIndex);

            setInterval(function() {
                currentIndex = (currentIndex + 1) % slides.length;
                showSlide(currentIndex);
            }, 5000);
        });
    </script>
@endsection
