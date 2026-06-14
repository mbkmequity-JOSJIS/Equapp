@extends('layouts.app')
@section('title', 'IOT Device')

@section('content')
    <section class="p-10">
        <div class="shadow-lg p-6 rounded-lg mb-6">
            <h1 class="text-5xl font-semibold mb-6 tracking-wider"><span class="tracking-widest text-sky-500 font-black uppercase text-7xl">EquApp</span> Devices Monitoring</h1>
            <p class="text-lg text-gray-600">Monitor and manage your IoT devices with ease.</p>
        </div>
        <div class="flex justify-center gap-3">
            <a href="devices/climeet" class="inline-block w-full h-[69vh] bg-orange-500/20 text-white px-4 py-2 rounded hover:bg-orange-600/20 overflow-hidden transition-colors duration-300">
                <div class="flex flex-col h-full items-center justify-center gap-4 relative ">
                    <i class="fa-solid fa-cloud-sun text-[26rem] absolute -z-20 -left-40 top-14 text-orange-300 opacity-20"></i>
                    <span class="text-8xl font-semibold">CLIMEET</span>
                    <span class="text-xl text-white">Monitoring</span>
                </div>
            </a>
            <a href="devices/aquaviska" class="inline-block w-full h-[69vh] bg-sky-500/20 text-white px-4 py-2 rounded hover:bg-sky-600/20 transition-colors duration-300 overflow-hidden">
                <div class="flex flex-col h-full items-center justify-center gap-4 relative ">
                    <i class="fa-solid fa-water text-[26rem] absolute -z-20 -left-40 top-14 text-sky-300 opacity-20"></i>
                    <span class="text-8xl font-semibold">AQUAVISKA</span>
                    <span class="text-xl text-white">Monitoring</span>
                </div>
            </a>
        </div>
    </section>
@endsection

@section('script')
    
@endsection