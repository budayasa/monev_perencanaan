@php
    $routeName = request()->route()?->getName() ?? '';
    $isRpjmd = str_contains($routeName, 'resources.rpjmds.index');
    $isRkpd = str_contains($routeName, 'resources.rkpds.index');
    $isRenstra = str_contains($routeName, 'resources.renstras.index');
    $isRenja = str_contains($routeName, 'resources.renjas.index');
    $isPd = str_contains($routeName, 'resources.perangkat-daerahs.index');
    $isUser = str_contains($routeName, 'resources.users.index');
    $isJadwal = str_contains($routeName, 'resources.jadwal-monevs.index');

    $showBanner = $isRpjmd || $isRkpd || $isRenstra || $isRenja || $isPd || $isUser || $isJadwal;
@endphp

@if($showBanner)
    @php
        $totalRpjmd = \App\Models\RpjmdSasaran::count() ?: \App\Models\Rpjmd::count();
        $totalRkpd = \App\Models\Rkpd::count();
        $totalRenstra = \App\Models\Renstra::count();
        $totalRenja = \App\Models\Renja::count();
        $totalPaguRenja = (float) \App\Models\Renja::sum('pagu_anggaran');
    @endphp

    <div class="rb-container">
        @if($isRpjmd || $isRkpd || $isRenstra || $isRenja)
            {{-- BANNER CASCADING 4 DOKUMEN PERENCANAAN PERMENDAGRI 86/2017 --}}
            @php
                $judulBanner = match(true) {
                    $isRpjmd => 'Level 1 (Kabupaten 5 Tahunan) — Master RPJMD Kabupaten Badung',
                    $isRkpd => 'Level 2 (Kabupaten Tahunan) — Master RKPD & Prioritas Pembangunan Daerah',
                    $isRenstra => 'Level 3 (OPD 5 Tahunan) — Master Renstra Perangkat Daerah & Keselarasan RPJMD',
                    default => 'Level 4 (OPD Tahunan) — Master Renja (Program → Kegiatan → Sub-Kegiatan SIPD)',
                };
                $subBanner = match(true) {
                    $isRpjmd => 'Pohon Kinerja Berjenjang: Visi → Misi → Tujuan & Indikator Tujuan → Sasaran & Indikator Sasaran → Program, Indikator Program & Pagu 5 Tahun.',
                    $isRkpd => 'Turunan tahunan dari RPJMD yang memuat Prioritas Daerah, Sasaran Pembangunan Tahun Berjalan, & Pagu Indikatif Kabupaten.',
                    $isRenstra => 'Tujuan & Sasaran Perangkat Daerah yang selaras dengan RPJMD, turun ke Program PD & target kinerja 5 tahunan.',
                    default => 'Turunan tahunan Renstra hingga level Program → Kegiatan → Sub-Kegiatan SIPD beserta Target Fisik & Pagu Anggaran.',
                };
            @endphp

            <div class="rb-hero">
                <div class="rb-hero-left">
                    <div class="rb-hero-icon">
                        <x-heroicon-o-document-duplicate class="w-7 h-7" />
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <h3 class="rb-hero-title">{{ $judulBanner }}</h3>
                            <span class="rb-badge-accent">Cascading Permendagri 86/2017</span>
                        </div>
                        <p class="rb-hero-sub">{{ $subBanner }}</p>
                    </div>
                </div>

                {{-- Navigasi Hirarki 4 Dokumen Berjenjang --}}
                <div class="rb-nav-pills">
                    <a href="{{ route('filament.admin.resources.rpjmds.index') }}"
                        class="rb-nav-pill {{ $isRpjmd ? 'active' : '' }}">
                        <span>1. RPJMD ({{ $totalRpjmd }})</span>
                    </a>
                    <span style="color: rgba(255,255,255,0.4); font-weight: 900;">›</span>
                    <a href="{{ route('filament.admin.resources.rkpds.index') }}"
                        class="rb-nav-pill {{ $isRkpd ? 'active' : '' }}">
                        <span>2. RKPD ({{ $totalRkpd }})</span>
                    </a>
                    <span style="color: rgba(255,255,255,0.4); font-weight: 900;">›</span>
                    <a href="{{ route('filament.admin.resources.renstras.index') }}"
                        class="rb-nav-pill {{ $isRenstra ? 'active' : '' }}">
                        <span>3. Renstra ({{ $totalRenstra }})</span>
                    </a>
                    <span style="color: rgba(255,255,255,0.4); font-weight: 900;">›</span>
                    <a href="{{ route('filament.admin.resources.renjas.index') }}"
                        class="rb-nav-pill {{ $isRenja ? 'active' : '' }}">
                        <span>4. Renja ({{ $totalRenja }})</span>
                    </a>
                </div>
            </div>

            <div class="rb-stats-grid">
                <div class="rb-stat-card">
                    <div class="rb-stat-icon" style="background: #ecfdf5; color: #005000; border: 1px solid #bbf7d0;">
                        <x-heroicon-o-flag class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="rb-stat-label">1. RPJMD (5 Tahunan Kab)</p>
                        <p class="rb-stat-value" style="color: #005000;">{{ $totalRpjmd }} <small>Sasaran</small></p>
                        <p class="rb-stat-sub">Periode 2025–2029</p>
                    </div>
                </div>

                <div class="rb-stat-card">
                    <div class="rb-stat-icon" style="background: #fff7ed; color: #FF6600; border: 1px solid #fed7aa;">
                        <x-heroicon-o-calendar-days class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="rb-stat-label">2. RKPD (Tahunan Kab)</p>
                        <p class="rb-stat-value" style="color: #FF6600;">{{ $totalRkpd }} <small>Prioritas</small></p>
                        <p class="rb-stat-sub">Sasaran Makro Tahunan</p>
                    </div>
                </div>

                <div class="rb-stat-card">
                    <div class="rb-stat-icon" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">
                        <x-heroicon-o-building-library class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="rb-stat-label">3. Renstra PD (5 Tahunan)</p>
                        <p class="rb-stat-value" style="color: #2563eb;">{{ $totalRenstra }} <small>Program</small></p>
                        <p class="rb-stat-sub">Tersebar di 37 OPD</p>
                    </div>
                </div>

                <div class="rb-stat-card">
                    <div class="rb-stat-icon" style="background: #fefce8; color: #ca8a04; border: 1px solid #fde047;">
                        <x-heroicon-o-banknotes class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="rb-stat-label">4. Renja PD (Sub-Kegiatan)</p>
                        <p class="rb-stat-value" style="color: #0f172a;">{{ $totalRenja }} <small>Sub-Keg</small></p>
                        <p class="rb-stat-sub">Total Pagu: {{ \App\Services\MonevService::formatRupiah($totalPaguRenja, true) }}</p>
                    </div>
                </div>
            </div>

        @elseif($isPd)
            @php
                $totalPdCount = \App\Models\PerangkatDaerah::count();
                $pdWithUserCount = \App\Models\PerangkatDaerah::has('users')->count();
                $totalUserPdCount = \App\Models\User::where('role', 'perangkat_daerah')->count();
            @endphp
            <div class="rb-hero">
                <div class="rb-hero-left">
                    <div class="rb-hero-icon">
                        <x-heroicon-o-building-office-2 class="w-7 h-7" />
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <h3 class="rb-hero-title">Master 37 Perangkat Daerah Kabupaten Badung</h3>
                            <span class="rb-badge-accent">{{ $totalPdCount }} OPD</span>
                        </div>
                        <p class="rb-hero-sub">
                            Daftar seluruh Organisasi Perangkat Daerah (Badan, Dinas, Sekretariat, Inspektorat, dan Kecamatan) pelaksana Renstra, Renja & Monev Triwulanan.
                        </p>
                    </div>
                </div>
            </div>

        @elseif($isJadwal)
            @php
                $jadwalAktif = \App\Models\JadwalMonev::getJadwalAktif();
                $isBuka = \App\Models\JadwalMonev::isBuka();
            @endphp
            <div class="rb-hero">
                <div class="rb-hero-left">
                    <div class="rb-hero-icon">
                        <x-heroicon-o-clock class="w-7 h-7" />
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <h3 class="rb-hero-title">Jadwal Buka-Tutup Pengisian Monev per Triwulan (TW I – IV)</h3>
                            <span class="rb-badge-accent" style="background: {{ $isBuka ? '#16a34a' : '#FF6600' }};">
                                {{ $isBuka ? '🟢 TRIWULAN ' . ($jadwalAktif?->triwulan ?? 1) . ' SEDANG DIBUKA' : '🔴 PENGISIAN TERKUNCI OTOMATIS' }}
                            </span>
                        </div>
                        <p class="rb-hero-sub">
                            Sistem secara otomatis mengunci pengisian dan pengiriman laporan realisasi OPD di backend apabila melewati batas waktu Triwulan.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
