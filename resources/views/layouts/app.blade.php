<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EQUapp | @yield('title')</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('style')
</head>

<body>
    <div class="flex w-full">
        <nav id="nav-menu"
            class="sticky top-0 z-0 bg-[#a9cbe0] h-dvh w-1/7 hover:w-1/2  transition-all duration-300 flex flex-col flex-nowrap items-start pl-10 justify-between py-10 gap-6">
            <a href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="Equapp Logo" class="h-30">
            </a>
            <div class="flex">
                <ul
                    class="[&>li]:text-3xl tracking-wider font-semibold [&>li]:whitespace-nowrap [&>li]:text-white  [&>li]:hover:text-sky-400 [&>li]:hover:text-shadow-slate-600 [&>li]:hover:translate-x-1 [&>li]:translate-y-1  [&>li]:transition-all [&>li]:duration-500 flex flex-col gap-2 border-r-2 pr-10 border-white/50">
                    <li id="feature" class="cursor-pointer">Feature</li>
                    <li id=""><a href="#sdgs">SDGs</a></li>
                    <li id=""><a href="#equproject">Equity Projects</a></li>
                    <li id=""><a href="#about-us">About Us</a></li>
                    <li id=""><a href="#faq">FAQ</a></li>
                    <li id=""><a href="#contact">Contact Us</a></li>
                </ul>
                <ul id="featureMenu"
                    class="hidden tracking-wider font-semibold [&>li]:whitespace-nowrap [&>li]:text-white   [&>li]:translate-y-1  [&>li]:transition-all [&>li]:duration-500 flex flex-col gap-4 pl-10  pr=l-10 ">
                    <li class="text-3xl opacity-60 hover:opacity-100 hover:text-shadow-slate-600 hover:translate-x-1"><a
                            href="/">AQUAVISKA</a></li>
                    <li class="text-3xl opacity-60 hover:opacity-100 hover:text-shadow-slate-600 hover:translate-x-1"><a
                            href="/about">CLIMATE</a></li>
                    <li class="text-xs animate-pulse">---- Coming Soon... ----</li>
                </ul>
            </div>
            <div class="text-4xl text-white w-full">
                <i class="fa-solid fa-circle-user"></i>
            </div>
        </nav>

        <main class="w-full z-50 [&>section]:box-border relative flex-1 shrink-0 shadow-2xl shadow-sky-500 ">
            @yield('content')
        </main>

    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
<script>
    $(document).ready(function() {
        // Tampilkan menu saat hover di feature
        $("#feature").hover(function() {
            $("#featureMenu").removeClass("hidden");
        });

        // Menu hanya hilang saat hover di elemen navigasi lain (misal: home, about, contact)
        // $("li:not(#feature)").hover(function() {
        //     $("#featureMenu").addClass("hidden");
        // });
    });
</script>
@yield('script')

</html>
