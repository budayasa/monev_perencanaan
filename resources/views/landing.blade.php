<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Monev Perencanaan — Pengendalian & Evaluasi Pembangunan Kabupaten Badung</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">
    <!-- Top Navbar -->
    <header class="bg-[#005000] text-white border-b-4 border-[#FF6600] sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <img src="{{ asset('images/logo-badung.png') }}" alt="Logo Pemkab Badung" class="h-12 w-auto bg-white p-1.5 rounded-xl shadow">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-black text-lg md:text-xl tracking-tight">E-MONEV PERENCANAAN BADUNG</span>
                        <span class="hidden sm:inline-block bg-[#FF6600] text-white text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase">Permendagri 86/2017</span>
                    </div>
                    <p class="text-xs text-emerald-100">Badan Perencanaan Pembangunan Daerah (Bappeda) Kabupaten Badung</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 bg-[#FF6600] hover:bg-orange-600 text-white font-extrabold text-xs md:text-sm px-5 py-2.5 rounded-xl shadow-md transition">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard ({{ auth()->user()->name }})
                    </a>
                @else
                    <a href="{{ route('sso.auth') }}" class="inline-flex items-center gap-2 bg-[#FF6600] hover:bg-orange-600 text-white font-extrabold text-xs md:text-sm px-5 py-2.5 rounded-xl shadow-md transition">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Masuk Sistem (SSO / Mock)
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-[#005000] via-[#0b5e18] to-[#043904] text-white py-16 md:py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 px-4 py-1.5 rounded-full text-xs font-bold text-orange-300">
                    <i class="bi bi-diagram-3-fill"></i>
                    <span>Cascading 4 Dokumen: RPJMD → RKPD → Renstra → Renja</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-black leading-tight">
                    Sistem Pengendalian & Evaluasi Perencanaan Pembangunan Daerah
                </h1>
                <p class="text-emerald-100 text-sm md:text-base leading-relaxed max-w-2xl">
                    Platform terintegrasi Bappeda dan 37 Perangkat Daerah Kabupaten Badung untuk pemantauan keselarasan dokumen perencanaan, pengukuran realisasi kinerja fisik & serapan anggaran Triwulan I–IV, analisis AI Eksekutif, serta pencetakan Laporan Evaluasi Resmi sesuai Lampiran Permendagri Nomor 86 Tahun 2017.
                </p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 bg-[#FF6600] hover:bg-orange-600 text-white font-extrabold text-sm px-6 py-3.5 rounded-xl shadow-lg transition">
                        <i class="bi bi-rocket-takeoff-fill"></i>
                        Buka Portal E-Monev
                    </a>
                    <a href="{{ route('sso.auth') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-bold text-sm px-6 py-3.5 rounded-xl transition">
                        <i class="bi bi-people-fill"></i>
                        Pilih Role / Mock Switcher (37 OPD)
                    </a>
                </div>
            </div>

            <!-- Cascading 4 Dokumen Card -->
            <div class="lg:col-span-5 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 space-y-4 shadow-2xl">
                <div class="flex items-center justify-between border-b border-white/15 pb-3">
                    <span class="font-extrabold text-sm uppercase tracking-wider text-orange-300">Alur Berjenjang (Permendagri 86/2017)</span>
                    <span class="text-xs bg-emerald-900/80 px-2.5 py-1 rounded-full font-bold">TA 2026</span>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="p-3.5 rounded-xl bg-white/10 border border-white/10 flex items-start gap-3">
                        <span class="w-7 h-7 rounded-lg bg-[#FF6600] text-white font-black flex items-center justify-center shrink-0">1</span>
                        <div>
                            <h4 class="font-extrabold text-white text-sm">RPJMD (5 Tahunan Kabupaten)</h4>
                            <p class="text-emerald-100 mt-0.5">Visi, Misi, Tujuan, Sasaran & IKU Kabupaten Badung (Target T1–T5)</p>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white/10 border border-white/10 flex items-start gap-3">
                        <span class="w-7 h-7 rounded-lg bg-emerald-500 text-white font-black flex items-center justify-center shrink-0">2</span>
                        <div>
                            <h4 class="font-extrabold text-white text-sm">RKPD (Tahunan Kabupaten)</h4>
                            <p class="text-emerald-100 mt-0.5">Prioritas Daerah, Sasaran Pembangunan Tahunan & Pagu Indikatif</p>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white/10 border border-white/10 flex items-start gap-3">
                        <span class="w-7 h-7 rounded-lg bg-blue-500 text-white font-black flex items-center justify-center shrink-0">3</span>
                        <div>
                            <h4 class="font-extrabold text-white text-sm">Renstra PD (5 Tahunan OPD)</h4>
                            <p class="text-emerald-100 mt-0.5">Tujuan, Sasaran & Program 37 Perangkat Daerah Selaras RPJMD</p>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white/10 border border-white/10 flex items-start gap-3">
                        <span class="w-7 h-7 rounded-lg bg-amber-500 text-white font-black flex items-center justify-center shrink-0">4</span>
                        <div>
                            <h4 class="font-extrabold text-white text-sm">Renja PD & Monev Triwulan I–IV</h4>
                            <p class="text-emerald-100 mt-0.5">Program → Kegiatan → Sub-Kegiatan SIPD, Realisasi Fisik & Keuangan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto bg-slate-900 text-slate-400 text-xs py-6 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>© {{ date('Y') }} Badan Perencanaan Pembangunan Daerah (Bappeda) Kabupaten Badung.</p>
            <p class="text-slate-500">Permendagri Nomor 86 Tahun 2017 • Tema Hijau (#005000) & Oranye (#FF6600)</p>
        </div>
    </footer>
</body>

</html>
