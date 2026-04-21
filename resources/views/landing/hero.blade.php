<section class="relative overflow-hidden rounded-[2rem] border border-white/20 bg-gradient-to-br from-[#122848] via-[#0055A0] to-[#8CC1E9] p-8 shadow-glass backdrop-blur-2xl sm:p-12">
    <div class="pointer-events-none absolute -right-16 top-0 h-64 w-64 rounded-full bg-[#8CC1E9]/20 blur-3xl"></div>
    <div class="relative max-w-3xl">
        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/60">Sistem Pemantauan Terintegrasi</p>
        <h1 class="mt-3 text-[48px] font-extrabold leading-tight tracking-tight text-white" style="font-weight:800">EQUAPP</h1>
        <p class="mt-4 text-lg text-sky-100/90">
            Integrated Environmental Monitoring,<br class="hidden sm:block">
            memantau kualitas air dan iklim mikro secara real-time untuk mendukung keputusan berbasis data di lapangan.
        </p>

        <div class="mt-8 flex flex-wrap gap-4">
            <div class="min-w-[140px] flex-1 rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur-md">
                <span class="block text-3xl font-bold text-emerald-200">{{ $totalNormal }}</span>
                <span class="text-sm text-white/75">Perangkat Normal</span>
            </div>
            <div class="min-w-[140px] flex-1 rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur-md">
                <span class="block text-3xl font-bold text-red-200">{{ $totalBroken }}</span>
                <span class="text-sm text-white/75">Rusak / Offline</span>
            </div>
        </div>
    </div>
</section>
