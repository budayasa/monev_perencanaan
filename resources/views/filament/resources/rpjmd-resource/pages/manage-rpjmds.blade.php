<x-filament-panels::page>
    <style>
        .rpjmd-wrap {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            color: #0f172a;
        }

        /* Hero Minimalis */
        .rpjmd-hero {
            background: linear-gradient(135deg, #005000 0%, #14532d 100%);
            border-bottom: 3px solid #FF6600;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            color: #ffffff;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            box-shadow: 0 4px 14px rgba(0, 80, 0, 0.12);
        }

        .rpjmd-hero-badges {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.4rem;
        }

        .rpjmd-pill-orange {
            background: #FF6600;
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 800;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .rpjmd-pill-glass {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
        }

        .rpjmd-hero h2 {
            font-size: 1.2rem;
            font-weight: 800;
            margin: 0;
            color: #ffffff;
        }

        .rpjmd-hero p {
            font-size: 0.78rem;
            color: #dcfce7;
            margin: 0.25rem 0 0 0;
        }

        /* 4 Kartu Ringkasan Horizontal */
        .rpjmd-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.85rem;
        }

        @media (max-width: 1024px) {
            .rpjmd-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .rpjmd-stats {
                grid-template-columns: 1fr;
            }
        }

        .rpjmd-stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.9rem 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        }

        .rpjmd-stat-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            margin: 0;
        }

        .rpjmd-stat-val {
            font-size: 1.3rem;
            font-weight: 900;
            color: #0f172a;
            margin: 0.15rem 0;
        }

        .rpjmd-stat-sub {
            font-size: 0.7rem;
            color: #64748b;
            margin: 0;
        }

        .rpjmd-stat-num {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        /* Tombol Minimalis */
        .btn-rpjmd {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.45rem 0.85rem;
            border-radius: 10px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-rpjmd-sm {
            font-size: 0.7rem;
            padding: 0.32rem 0.65rem;
            border-radius: 8px;
        }

        .btn-green {
            background: #005000;
            color: #ffffff;
        }
        .btn-green:hover {
            background: #003d00;
        }

        .btn-orange {
            background: #FF6600;
            color: #ffffff;
        }
        .btn-orange:hover {
            background: #e55b00;
        }

        .btn-outline {
            background: #ffffff;
            color: #334155;
            border-color: #cbd5e1;
        }
        .btn-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .btn-soft-green {
            background: #f0fdf4;
            color: #005000;
            border-color: #bbf7d0;
        }
        .btn-soft-green:hover {
            background: #dcfce7;
        }

        .btn-soft-orange {
            background: #fff7ed;
            color: #c2410c;
            border-color: #fed7aa;
        }
        .btn-soft-orange:hover {
            background: #ffedd5;
        }

        .btn-soft-blue {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }
        .btn-soft-blue:hover {
            background: #dbeafe;
        }

        .btn-soft-red {
            background: #fff1f2;
            color: #be123c;
            border-color: #fecdd3;
        }
        .btn-soft-red:hover {
            background: #ffe4e6;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.28);
        }
        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.26);
        }

        /* Kartu Hierarki Minimalis */
        .rpjmd-visi-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-top: 4px solid #005000;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }

        .rpjmd-visi-header {
            padding: 1.15rem 1.4rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .rpjmd-visi-text {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0.35rem 0 0 0;
            line-height: 1.4;
        }

        .rpjmd-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background: #f8fafc;
        }

        .rpjmd-misi-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #005000;
            border-radius: 12px;
            overflow: hidden;
        }

        .rpjmd-misi-header {
            padding: 0.85rem 1.15rem;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .rpjmd-badge-misi {
            background: #005000;
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 800;
            padding: 0.22rem 0.6rem;
            border-radius: 6px;
            flex-shrink: 0;
        }

        .rpjmd-misi-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.4;
        }

        .rpjmd-tujuan-wrap {
            padding: 0.9rem 1.15rem;
            background: #fcfcfd;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .rpjmd-tujuan-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #16a34a;
            border-radius: 10px;
            overflow: hidden;
        }

        .rpjmd-tujuan-header {
            padding: 0.75rem 1rem;
            background: #f0fdf4;
            border-bottom: 1px solid #dcfce7;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
        }

        .rpjmd-sasaran-wrap {
            margin-left: 0.75rem;
            padding-left: 0.85rem;
            border-left: 2px dashed #fdba74;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .rpjmd-sasaran-card {
            background: #ffffff;
            border: 1px solid #fed7aa;
            border-radius: 10px;
            overflow: hidden;
        }

        .rpjmd-sasaran-header {
            padding: 0.65rem 0.95rem;
            background: #fff7ed;
            border-bottom: 1px solid #ffedd5;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
        }

        .rpjmd-program-wrap {
            margin-left: 0.75rem;
            padding-left: 0.85rem;
            border-left: 2px dashed #93c5fd;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .rpjmd-program-card {
            background: #ffffff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            overflow: hidden;
        }

        .rpjmd-program-header {
            padding: 0.65rem 0.9rem;
            background: #eff6ff;
            border-bottom: 1px solid #dbeafe;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
        }

        /* Tabel Indikator Minimalis */
        .rpjmd-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.74rem;
            color: #1e293b;
        }

        .rpjmd-table th {
            background: #f8fafc;
            color: #475569;
            font-size: 0.66rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 0.5rem 0.65rem;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .rpjmd-table td {
            padding: 0.55rem 0.65rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .rpjmd-table tr:last-child td {
            border-bottom: none;
        }

        .rpjmd-empty-note {
            padding: 0.7rem 0.95rem;
            font-size: 0.75rem;
            color: #64748b;
            background: #ffffff;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
        }

        /* Modal Minimalis & Kontras Tinggi */
        .rpjmd-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .rpjmd-modal-box {
            background: #ffffff;
            color: #0f172a;
            width: 100%;
            max-width: 44rem;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .rpjmd-modal-head {
            background: #005000;
            border-bottom: 3px solid #FF6600;
            color: #ffffff;
            padding: 1rem 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .rpjmd-modal-body {
            padding: 1.35rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .rpjmd-input,
        .rpjmd-textarea,
        .rpjmd-select {
            width: 100%;
            background: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.55rem 0.75rem;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .rpjmd-input:focus,
        .rpjmd-textarea:focus,
        .rpjmd-select:focus {
            border-color: #005000;
            box-shadow: 0 0 0 3px rgba(0, 80, 0, 0.12);
        }

        .rpjmd-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.35rem;
        }

        .rpjmd-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.85rem;
        }

        .rpjmd-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .rpjmd-grid-6 {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 0.55rem;
        }

        @media (max-width: 768px) {
            .rpjmd-grid-2,
            .rpjmd-grid-3 {
                grid-template-columns: 1fr;
            }
            .rpjmd-grid-6 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>

    <div class="rpjmd-wrap">
        {{-- HERO BANNER --}}
        <div class="rpjmd-hero">
            <div>
                <div class="rpjmd-hero-badges">
                    <span class="rpjmd-pill-orange">Level 1 • Permendagri 86/2017</span>
                    <span class="rpjmd-pill-glass">Dokumen RPJMD 2025–2029 • Baseline {{ $tahunBaseline }} • Target {{ $tahunAwal }}–{{ $tahunAkhir }}</span>
                </div>
                <h2>Master RPJMD Kabupaten Badung</h2>
                <p>Alur Berjenjang: Visi (1) → Misi → Tujuan &amp; Indikator → Sasaran &amp; Indikator → Program, Indikator Program &amp; Pagu 5 Tahun</p>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <button type="button" wire:click="exportCsv" class="btn-rpjmd btn-glass">
                    📥 Unduh CSV
                </button>
                @if($canManage && !$visi)
                    <button type="button" wire:click="openVisiModal()" class="btn-rpjmd btn-orange">
                        + Input Visi RPJMD
                    </button>
                @endif
            </div>
        </div>

        {{-- 4 KARTU RINGKASAN SEJAJAR --}}
        <div class="rpjmd-stats">
            <div class="rpjmd-stat-card">
                <div>
                    <p class="rpjmd-stat-label">Visi &amp; Misi</p>
                    <p class="rpjmd-stat-val" style="color: #005000;">{{ $visi ? 1 : 0 }} Visi • {{ $totalMisi }} Misi</p>
                    <p class="rpjmd-stat-sub">Baseline {{ $tahunBaseline }} • Target {{ $tahunAwal }}–{{ $tahunAkhir }}</p>
                </div>
                <span class="rpjmd-stat-num" style="background: #f0fdf4; color: #005000; border: 1px solid #bbf7d0;">1</span>
            </div>

            <div class="rpjmd-stat-card">
                <div>
                    <p class="rpjmd-stat-label">Tujuan RPJMD</p>
                    <p class="rpjmd-stat-val" style="color: #FF6600;">{{ $totalTujuan }} <small style="font-size: 0.72rem; font-weight: 700;">Tujuan</small></p>
                    <p class="rpjmd-stat-sub">{{ $totalIndikatorTujuan }} Indikator Tujuan</p>
                </div>
                <span class="rpjmd-stat-num" style="background: #fff7ed; color: #FF6600; border: 1px solid #fed7aa;">2</span>
            </div>

            <div class="rpjmd-stat-card">
                <div>
                    <p class="rpjmd-stat-label">Sasaran RPJMD</p>
                    <p class="rpjmd-stat-val" style="color: #1d4ed8;">{{ $totalSasaran }} <small style="font-size: 0.72rem; font-weight: 700;">Sasaran</small></p>
                    <p class="rpjmd-stat-sub">{{ $totalIndikatorSasaran }} Indikator Sasaran</p>
                </div>
                <span class="rpjmd-stat-num" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">3</span>
            </div>

            <div class="rpjmd-stat-card">
                <div>
                    <p class="rpjmd-stat-label">Program &amp; Pagu 5 Tahun</p>
                    <p class="rpjmd-stat-val">{{ $totalProgram }} <small style="font-size: 0.72rem; font-weight: 700;">Program</small></p>
                    <p class="rpjmd-stat-sub" style="color: #005000; font-weight: 700;">{{ \App\Services\MonevService::formatRupiah($totalPagu5Tahun, true) }}</p>
                </div>
                <span class="rpjmd-stat-num" style="background: #fefce8; color: #a16207; border: 1px solid #fde047;">4</span>
            </div>
        </div>

        {{-- POHON KINERJA RPJMD --}}
        @if(!$visi)
            <div style="background: #ffffff; border: 2px dashed #86efac; border-radius: 16px; padding: 2.5rem 1.5rem; text-align: center;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">Belum Ada Visi RPJMD</h3>
                <p style="font-size: 0.8rem; color: #64748b; max-width: 34rem; margin: 0.4rem auto 1.25rem auto;">
                    Mulai dengan menginput <strong>Visi Kabupaten</strong> (hanya 1 Visi). Setelah itu Anda dapat menambahkan Misi, Tujuan, Indikator Tujuan, Sasaran, Indikator Sasaran, serta Program &amp; Pagu 5 Tahun.
                </p>
                @if($canManage)
                    <button type="button" wire:click="openVisiModal()" class="btn-rpjmd btn-green" style="padding: 0.65rem 1.25rem; font-size: 0.85rem;">
                        + Input Visi RPJMD Sekarang
                    </button>
                @endif
            </div>
        @else
            <div class="rpjmd-visi-card">
                {{-- LEVEL 1: VISI --}}
                <div class="rpjmd-visi-header">
                    <div style="flex: 1; min-width: 240px;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="background: #005000; color: #ffffff; font-size: 0.68rem; font-weight: 800; padding: 0.2rem 0.55rem; border-radius: 6px;">
                                VISI ({{ $visi->tahun_awal }}–{{ $visi->tahun_akhir }})
                            </span>
                            <span style="font-size: 0.72rem; font-weight: 700; color: #64748b;">{{ $visi->misis->count() }} Misi Terkait</span>
                        </div>
                        <p class="rpjmd-visi-text">“{{ $visi->visi }}”</p>
                    </div>

                    @if($canManage)
                        <div style="display: flex; flex-wrap: wrap; gap: 0.45rem;">
                            <button type="button" wire:click="openVisiModal({{ $visi->id }})" class="btn-rpjmd btn-outline">
                                ✏️ Edit Visi
                            </button>
                            <button type="button" wire:click="openMisiModal({{ $visi->id }})" class="btn-rpjmd btn-orange">
                                + Tambah Misi
                            </button>
                        </div>
                    @endif
                </div>

                {{-- LEVEL 2: DAFTAR MISI --}}
                <div class="rpjmd-body">
                    @forelse($visi->misis as $misi)
                        <div class="rpjmd-misi-card">
                            <div class="rpjmd-misi-header">
                                <div style="display: flex; align-items: flex-start; gap: 0.65rem; flex: 1; min-width: 220px;">
                                    <span class="rpjmd-badge-misi">MISI {{ $misi->urutan }}</span>
                                    <p class="rpjmd-misi-title">{{ $misi->misi }}</p>
                                </div>

                                @if($canManage)
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                                        <button type="button" wire:click="openMisiModal({{ $visi->id }}, {{ $misi->id }})" class="btn-rpjmd btn-rpjmd-sm btn-outline">
                                            ✏️ Edit Misi
                                        </button>
                                        <button type="button" wire:click="openTujuanModal({{ $misi->id }})" class="btn-rpjmd btn-rpjmd-sm btn-soft-green">
                                            + Tambah Tujuan
                                        </button>
                                        @if($canDelete)
                                            <button type="button"
                                                wire:click="deleteMisi({{ $misi->id }})"
                                                wire:confirm="Hapus Misi ini beserta seluruh Tujuan, Sasaran, dan Program di bawahnya?"
                                                class="btn-rpjmd btn-rpjmd-sm btn-soft-red">
                                                🗑️
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- LEVEL 3: DAFTAR TUJUAN --}}
                            <div class="rpjmd-tujuan-wrap">
                                @forelse($misi->tujuans as $tujuan)
                                    <div class="rpjmd-tujuan-card">
                                        <div class="rpjmd-tujuan-header">
                                            <div style="display: flex; align-items: flex-start; gap: 0.55rem; flex: 1; min-width: 220px;">
                                                <span style="background: #16a34a; color: #ffffff; font-size: 0.65rem; font-weight: 800; padding: 0.18rem 0.5rem; border-radius: 5px; flex-shrink: 0;">
                                                    TUJUAN {{ $misi->urutan }}.{{ $tujuan->urutan }}
                                                </span>
                                                <span style="font-size: 0.82rem; font-weight: 700; color: #0f172a;">{{ $tujuan->tujuan }}</span>
                                            </div>

                                            @if($canManage)
                                                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                                                    <button type="button" wire:click="openTujuanModal({{ $misi->id }}, {{ $tujuan->id }})" class="btn-rpjmd btn-rpjmd-sm btn-outline">
                                                        ✏️ Edit
                                                    </button>
                                                    <button type="button" wire:click="openIndikatorTujuanModal({{ $tujuan->id }})" class="btn-rpjmd btn-rpjmd-sm btn-green">
                                                        + Indikator Tujuan
                                                    </button>
                                                    <button type="button" wire:click="openSasaranModal({{ $tujuan->id }})" class="btn-rpjmd btn-rpjmd-sm btn-orange">
                                                        + Tambah Sasaran
                                                    </button>
                                                    @if($canDelete)
                                                        <button type="button"
                                                            wire:click="deleteTujuan({{ $tujuan->id }})"
                                                            wire:confirm="Hapus Tujuan ini beserta turunannya?"
                                                            class="btn-rpjmd btn-rpjmd-sm btn-soft-red">
                                                            🗑️
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <div style="padding: 0.85rem; display: flex; flex-direction: column; gap: 0.85rem;">
                                            {{-- INDIKATOR TUJUAN --}}
                                            <div style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                                                <div style="background: #f8fafc; padding: 0.4rem 0.75rem; border-bottom: 1px solid #e2e8f0; font-size: 0.68rem; font-weight: 800; color: #005000; text-transform: uppercase;">
                                                    Indikator Tujuan ({{ $tujuan->indikators->count() }})
                                                </div>

                                                @if($tujuan->indikators->isNotEmpty())
                                                    <div style="overflow-x: auto;">
                                                        <table class="rpjmd-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Indikator Tujuan</th>
                                                                    <th style="text-align: center;">Satuan</th>
                                                                    <th style="text-align: right;">Baseline ({{ $tahunBaseline }})</th>
                                                                    <th style="text-align: right;">{{ $tahunAwal }}</th>
                                                                    <th style="text-align: right;">{{ $tahunAwal + 1 }}</th>
                                                                    <th style="text-align: right;">{{ $tahunAwal + 2 }}</th>
                                                                    <th style="text-align: right;">{{ $tahunAwal + 3 }}</th>
                                                                    <th style="text-align: right;">{{ $tahunAwal + 4 }}</th>
                                                                    <th style="text-align: center;">Akhir</th>
                                                                    @if($canManage)<th style="text-align: right;">Aksi</th>@endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($tujuan->indikators as $indT)
                                                                    <tr>
                                                                        <td>
                                                                            <div style="font-weight: 700; color: #0f172a;">{{ $indT->indikator }}</div>
                                                                            <span style="font-size: 0.62rem; font-weight: 700; color: {{ $indT->sifat_target === 'akumulatif' ? '#c2410c' : '#15803d' }};">
                                                                                {{ $indT->sifat_target === 'akumulatif' ? '∑ Akumulatif' : '● Non-Akumulatif' }}
                                                                            </span>
                                                                        </td>
                                                                        <td style="text-align: center;">{{ $indT->satuan }}</td>
                                                                        <td style="text-align: right; color: #64748b;">{{ \App\Models\Rpjmd::displayTarget($indT->capaian_awal, $indT->capaian_awal_teks) }}</td>
                                                                        <td style="text-align: right;">{{ \App\Models\Rpjmd::displayTarget($indT->target_t1, $indT->target_t1_teks) }}</td>
                                                                        <td style="text-align: right;">{{ \App\Models\Rpjmd::displayTarget($indT->target_t2, $indT->target_t2_teks) }}</td>
                                                                        <td style="text-align: right;">{{ \App\Models\Rpjmd::displayTarget($indT->target_t3, $indT->target_t3_teks) }}</td>
                                                                        <td style="text-align: right;">{{ \App\Models\Rpjmd::displayTarget($indT->target_t4, $indT->target_t4_teks) }}</td>
                                                                        <td style="text-align: right;">{{ \App\Models\Rpjmd::displayTarget($indT->target_t5, $indT->target_t5_teks) }}</td>
                                                                        <td style="text-align: center;">
                                                                            <span style="background: #dcfce7; color: #005000; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 5px;">
                                                                                {{ \App\Models\Rpjmd::displayTarget($indT->target_akhir, $indT->target_akhir_teks) }}
                                                                            </span>
                                                                        </td>
                                                                        @if($canManage)
                                                                            <td style="text-align: right; white-space: nowrap;">
                                                                                <button type="button" wire:click="openIndikatorTujuanModal({{ $tujuan->id }}, {{ $indT->id }})" style="color: #1d4ed8; font-weight: 700; background: none; border: none; cursor: pointer;">Edit</button>
                                                                                @if($canDelete)
                                                                                    <button type="button" wire:click="deleteIndikatorTujuan({{ $indT->id }})" wire:confirm="Hapus indikator ini?" style="color: #be123c; font-weight: 700; background: none; border: none; cursor: pointer; margin-left: 0.4rem;">Hapus</button>
                                                                                @endif
                                                                            </td>
                                                                        @endif
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div style="padding: 0.6rem 0.75rem; font-size: 0.73rem; color: #64748b; font-style: italic;">
                                                        Belum ada Indikator Tujuan. Klik <strong>"+ Indikator Tujuan"</strong> (bisa 2 atau lebih indikator).
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- LEVEL 4: DAFTAR SASARAN --}}
                                            <div class="rpjmd-sasaran-wrap">
                                                @forelse($tujuan->sasarans as $sasaran)
                                                    <div class="rpjmd-sasaran-card">
                                                        <div class="rpjmd-sasaran-header">
                                                            <div style="display: flex; align-items: flex-start; gap: 0.5rem; flex: 1; min-width: 200px;">
                                                                <span style="background: #FF6600; color: #ffffff; font-size: 0.64rem; font-weight: 800; padding: 0.18rem 0.45rem; border-radius: 5px; flex-shrink: 0;">
                                                                    SASARAN {{ $misi->urutan }}.{{ $tujuan->urutan }}.{{ $sasaran->urutan }}
                                                                </span>
                                                                <span style="font-size: 0.8rem; font-weight: 700; color: #0f172a;">{{ $sasaran->sasaran }}</span>
                                                            </div>

                                                            @if($canManage)
                                                                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                                                                    <button type="button" wire:click="openSasaranModal({{ $tujuan->id }}, {{ $sasaran->id }})" class="btn-rpjmd btn-rpjmd-sm btn-outline">
                                                                        ✏️ Edit
                                                                    </button>
                                                                    <button type="button" wire:click="openIndikatorSasaranModal({{ $sasaran->id }})" class="btn-rpjmd btn-rpjmd-sm btn-soft-orange">
                                                                        + Indikator Sasaran
                                                                    </button>
                                                                    <button type="button" wire:click="openProgramModal({{ $sasaran->id }})" class="btn-rpjmd btn-rpjmd-sm btn-soft-blue">
                                                                        + Tambah Program
                                                                    </button>
                                                                    @if($canDelete)
                                                                        <button type="button"
                                                                            wire:click="deleteSasaran({{ $sasaran->id }})"
                                                                            wire:confirm="Hapus Sasaran ini beserta Indikator & Program di bawahnya?"
                                                                            class="btn-rpjmd btn-rpjmd-sm btn-soft-red">
                                                                            🗑️
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div style="padding: 0.75rem; display: flex; flex-direction: column; gap: 0.75rem;">
                                                            {{-- INDIKATOR SASARAN --}}
                                                            <div style="border: 1px solid #ffedd5; border-radius: 8px; overflow: hidden;">
                                                                <div style="background: #fffaf5; padding: 0.35rem 0.7rem; border-bottom: 1px solid #ffedd5; font-size: 0.67rem; font-weight: 800; color: #c2410c; text-transform: uppercase;">
                                                                    Indikator Sasaran ({{ $sasaran->indikators->count() }})
                                                                </div>

                                                                @if($sasaran->indikators->isNotEmpty())
                                                                    <div style="overflow-x: auto;">
                                                                        <table class="rpjmd-table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Indikator Sasaran</th>
                                                                                    <th style="text-align: center;">Satuan</th>
                                                                                    <th style="text-align: right;">Baseline ({{ $tahunBaseline }})</th>
                                                                                    <th style="text-align: right;">{{ $tahunAwal }}</th>
                                                                                    <th style="text-align: right;">{{ $tahunAwal + 1 }}</th>
                                                                                    <th style="text-align: right;">{{ $tahunAwal + 2 }}</th>
                                                                                    <th style="text-align: right;">{{ $tahunAwal + 3 }}</th>
                                                                                    <th style="text-align: right;">{{ $tahunAwal + 4 }}</th>
                                                                                    <th style="text-align: center;">Akhir</th>
                                                                                    @if($canManage)<th style="text-align: right;">Aksi</th>@endif
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($sasaran->indikators as $indS)
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="font-weight: 700; color: #0f172a;">{{ $indS->indikator }}</div>
                                                                                            <span style="font-size: 0.62rem; font-weight: 700; color: {{ $indS->sifat_target === 'akumulatif' ? '#c2410c' : '#15803d' }};">
                                                                                                {{ $indS->sifat_target === 'akumulatif' ? '∑ Akumulatif' : '● Non-Akumulatif' }}
                                                                                            </span>
                                                                                        </td>
                                                                                        <td style="text-align: center;">{{ $indS->satuan }}</td>
                                                                                        <td style="text-align: right; color: #64748b;">{{ \App\Models\Rpjmd::displayTarget($indS->capaian_awal, $indS->capaian_awal_teks) }}</td>
                                                                                        <td style="text-align: right;">{{ \App\Models\Rpjmd::displayTarget($indS->target_t1, $indS->target_t1_teks) }}</td>
                                                                                        <td style="text-align: right;">{{ \App\Models\Rpjmd::displayTarget($indS->target_t2, $indS->target_t2_teks) }}</td>
                                                                                        <td style="text-align: right;">{{ \App\Models\Rpjmd::displayTarget($indS->target_t3, $indS->target_t3_teks) }}</td>
                                                                                        <td style="text-align: right;">{{ \App\Models\Rpjmd::displayTarget($indS->target_t4, $indS->target_t4_teks) }}</td>
                                                                                        <td style="text-align: right;">{{ \App\Models\Rpjmd::displayTarget($indS->target_t5, $indS->target_t5_teks) }}</td>
                                                                                        <td style="text-align: center;">
                                                                                            <span style="background: #ffedd5; color: #9a3412; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 5px;">
                                                                                                {{ \App\Models\Rpjmd::displayTarget($indS->target_akhir, $indS->target_akhir_teks) }}
                                                                                            </span>
                                                                                        </td>
                                                                                        @if($canManage)
                                                                                            <td style="text-align: right; white-space: nowrap;">
                                                                                                <button type="button" wire:click="openIndikatorSasaranModal({{ $sasaran->id }}, {{ $indS->id }})" style="color: #1d4ed8; font-weight: 700; background: none; border: none; cursor: pointer;">Edit</button>
                                                                                                @if($canDelete)
                                                                                                    <button type="button" wire:click="deleteIndikatorSasaran({{ $indS->id }})" wire:confirm="Hapus indikator ini?" style="color: #be123c; font-weight: 700; background: none; border: none; cursor: pointer; margin-left: 0.4rem;">Hapus</button>
                                                                                @endif
                                                                                            </td>
                                                                                        @endif
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                @else
                                                                    <div style="padding: 0.55rem 0.7rem; font-size: 0.73rem; color: #64748b; font-style: italic;">
                                                                        Belum ada Indikator Sasaran. Klik <strong>"+ Indikator Sasaran"</strong> (bisa 2 atau lebih indikator).
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            {{-- LEVEL 5: PROGRAM (1 PROGRAM = 1 INDIKATOR & PAGU 5 TAHUN) --}}
                                                            <div class="rpjmd-program-wrap">
                                                                @forelse($sasaran->programs as $prog)
                                                                    <div class="rpjmd-program-card">
                                                                        <div class="rpjmd-program-header">
                                                                            <div style="flex: 1; min-width: 200px;">
                                                                                <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                                                                                    @if($prog->kode_program)
                                                                                        <span style="background: #1d4ed8; color: #ffffff; font-family: monospace; font-size: 0.66rem; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 5px;">
                                                                                            {{ $prog->kode_program }}
                                                                                        </span>
                                                                                    @endif
                                                                                    <span style="font-size: 0.78rem; font-weight: 800; color: #0f172a;">{{ $prog->nama_program }}</span>
                                                                                </div>
                                                                                @if($prog->opds->isNotEmpty())
                                                                                    <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; margin-top: 0.35rem;">
                                                                                        @foreach($prog->opds as $opd)
                                                                                            <span style="background: #dcfce7; color: #005000; border: 1px solid #86efac; font-size: 0.64rem; font-weight: 700; padding: 0.1rem 0.45rem; border-radius: 9999px;">
                                                                                                {{ $opd->singkatan ?: $opd->nama_instansi }}
                                                                                            </span>
                                                                                        @endforeach
                                                                                    </div>
                                                                                @endif
                                                                            </div>

                                                                            @if($canManage)
                                                                                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                                                                                    <button type="button" wire:click="openProgramModal({{ $sasaran->id }}, {{ $prog->id }})" class="btn-rpjmd btn-rpjmd-sm btn-outline">
                                                                                        ✏️ Edit Program
                                                                                    </button>
                                                                                    <button type="button" wire:click="openIndikatorProgramModal({{ $prog->id }})" class="btn-rpjmd btn-rpjmd-sm btn-green">
                                                                                        {{ $prog->indikator_program ? '⚙️ Indikator & Pagu 5 Th' : '+ Indikator & Pagu 5 Th' }}
                                                                                    </button>
                                                                                    @if($canDelete)
                                                                                        <button type="button" wire:click="deleteProgram({{ $prog->id }})" wire:confirm="Hapus Program ini?" class="btn-rpjmd btn-rpjmd-sm btn-soft-red">
                                                                                            🗑️
                                                                                        </button>
                                                                                    @endif
                                                                                </div>
                                                                            @endif
                                                                        </div>

                                                                        @if($prog->indikator_program)
                                                                            <div style="overflow-x: auto; background: #ffffff;">
                                                                                <table class="rpjmd-table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Indikator Program (1 Indikator)</th>
                                                                                            <th style="text-align: center;">Satuan</th>
                                                                                            <th style="text-align: right;">Baseline ({{ $tahunBaseline }})</th>
                                                                                            <th style="text-align: right;">{{ $tahunAwal }}</th>
                                                                                            <th style="text-align: right;">{{ $tahunAwal + 1 }}</th>
                                                                                            <th style="text-align: right;">{{ $tahunAwal + 2 }}</th>
                                                                                            <th style="text-align: right;">{{ $tahunAwal + 3 }}</th>
                                                                                            <th style="text-align: right;">{{ $tahunAwal + 4 }}</th>
                                                                                            <th style="text-align: center;">Akhir &amp; Pagu 5 Th</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td style="font-weight: 700; color: #0f172a;">{{ $prog->indikator_program }}</td>
                                                                                            <td style="text-align: center;">{{ $prog->satuan }}</td>
                                                                                            <td style="text-align: right; color: #64748b;">{{ \App\Models\Rpjmd::displayTarget($prog->capaian_awal, $prog->capaian_awal_teks) }}</td>
                                                                                            <td style="text-align: right;">
                                                                                                <div>{{ \App\Models\Rpjmd::displayTarget($prog->target_t1, $prog->target_t1_teks) }}</div>
                                                                                                <div style="font-size: 0.65rem; color: #005000;">{{ \App\Services\MonevService::formatRupiah($prog->pagu_t1, true) }}</div>
                                                                                            </td>
                                                                                            <td style="text-align: right;">
                                                                                                <div>{{ \App\Models\Rpjmd::displayTarget($prog->target_t2, $prog->target_t2_teks) }}</div>
                                                                                                <div style="font-size: 0.65rem; color: #005000;">{{ \App\Services\MonevService::formatRupiah($prog->pagu_t2, true) }}</div>
                                                                                            </td>
                                                                                            <td style="text-align: right;">
                                                                                                <div>{{ \App\Models\Rpjmd::displayTarget($prog->target_t3, $prog->target_t3_teks) }}</div>
                                                                                                <div style="font-size: 0.65rem; color: #005000;">{{ \App\Services\MonevService::formatRupiah($prog->pagu_t3, true) }}</div>
                                                                                            </td>
                                                                                            <td style="text-align: right;">
                                                                                                <div>{{ \App\Models\Rpjmd::displayTarget($prog->target_t4, $prog->target_t4_teks) }}</div>
                                                                                                <div style="font-size: 0.65rem; color: #005000;">{{ \App\Services\MonevService::formatRupiah($prog->pagu_t4, true) }}</div>
                                                                                            </td>
                                                                                            <td style="text-align: right;">
                                                                                                <div>{{ \App\Models\Rpjmd::displayTarget($prog->target_t5, $prog->target_t5_teks) }}</div>
                                                                                                <div style="font-size: 0.65rem; color: #005000;">{{ \App\Services\MonevService::formatRupiah($prog->pagu_t5, true) }}</div>
                                                                                            </td>
                                                                                            <td style="text-align: center;">
                                                                                                <span style="background: #dcfce7; color: #005000; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 5px;">
                                                                                                    {{ \App\Models\Rpjmd::displayTarget($prog->target_akhir, $prog->target_akhir_teks) }} {{ $prog->satuan }}
                                                                                                </span>
                                                                                                <div style="font-size: 0.68rem; font-weight: 800; color: #0f172a; margin-top: 0.2rem;">
                                                                                                    {{ \App\Services\MonevService::formatRupiah($prog->pagu_5_tahun, true) }}
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        @else
                                                                            <div style="padding: 0.55rem 0.85rem; background: #ffffff; font-size: 0.73rem; color: #b45309; display: flex; justify-content: space-between; align-items: center;">
                                                                                <span>⚠️ Indikator Program &amp; Pagu 5 Tahun belum diisi.</span>
                                                                                @if($canManage)
                                                                                    <button type="button" wire:click="openIndikatorProgramModal({{ $prog->id }})" style="color: #005000; font-weight: 800; background: none; border: none; cursor: pointer; text-decoration: underline;">
                                                                                        Isi Sekarang →
                                                                                    </button>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @empty
                                                                    <div class="rpjmd-empty-note">
                                                                        Belum ada Program pada Sasaran ini. Klik <strong>"+ Tambah Program"</strong>.
                                                                    </div>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="rpjmd-empty-note">
                                                        Belum ada Sasaran pada Tujuan ini. Klik <strong>"+ Tambah Sasaran"</strong>.
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rpjmd-empty-note">
                                        Belum ada Tujuan pada Misi ini. Klik <strong>"+ Tambah Tujuan"</strong> di sebelah kanan Misi.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="rpjmd-empty-note" style="text-align: center; padding: 1.5rem;">
                            Belum ada Misi terkait Visi ini. Klik <strong>"+ Tambah Misi"</strong> di samping Visi.
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>

    {{-- ==================== MODAL INPUT / EDIT ==================== --}}
    @if($activeModal)
        <div class="rpjmd-modal-backdrop">
            <div class="rpjmd-modal-box">
                <div class="rpjmd-modal-head">
                    <div>
                        <h3 style="font-weight: 800; font-size: 0.95rem; margin: 0; color: #ffffff;">
                            @switch($activeModal)
                                @case('visi') {{ $editingId ? 'Edit Visi RPJMD' : 'Input Visi RPJMD' }} @break
                                @case('misi') {{ $editingId ? 'Edit Misi RPJMD' : 'Tambah Misi RPJMD' }} @break
                                @case('tujuan') {{ $editingId ? 'Edit Tujuan RPJMD' : 'Tambah Tujuan RPJMD' }} @break
                                @case('indikator_tujuan') {{ $editingId ? 'Edit Indikator Tujuan' : 'Tambah Indikator Tujuan' }} @break
                                @case('sasaran') {{ $editingId ? 'Edit Sasaran RPJMD' : 'Tambah Sasaran RPJMD' }} @break
                                @case('indikator_sasaran') {{ $editingId ? 'Edit Indikator Sasaran' : 'Tambah Indikator Sasaran' }} @break
                                @case('program') {{ $editingId ? 'Edit Program RPJMD' : 'Tambah Program di Bawah Sasaran' }} @break
                                @case('indikator_program') Input / Edit Indikator Program (1 Indikator) &amp; Pagu 5 Tahun @break
                            @endswitch
                        </h3>
                        @if($parentLabel)
                            <p style="font-size: 0.72rem; color: #dcfce7; margin: 0.15rem 0 0 0;">{{ $parentLabel }}</p>
                        @endif
                    </div>
                    <button type="button" wire:click="closeModal" style="background: none; border: none; color: #ffffff; font-size: 1.15rem; font-weight: 900; cursor: pointer;">✕</button>
                </div>

                <div class="rpjmd-modal-body">
                    @if($activeModal === 'visi')
                        <div class="rpjmd-grid-2">
                            <div>
                                <label class="rpjmd-label">Tahun Awal Target (T1) *</label>
                                <input type="number" wire:model="form.tahun_awal" class="rpjmd-input">
                                <span style="font-size: 0.68rem; color: #64748b;">Dokumen 2025–2029: 2025 = Baseline, Tahun Awal Target = 2026</span>
                            </div>
                            <div>
                                <label class="rpjmd-label">Tahun Akhir Target (T5) *</label>
                                <input type="number" wire:model="form.tahun_akhir" class="rpjmd-input">
                                <span style="font-size: 0.68rem; color: #64748b;">Default Tahun Akhir Target = 2030</span>
                            </div>
                        </div>
                        <div>
                            <label class="rpjmd-label">Rumusan Visi Kabupaten *</label>
                            <textarea rows="3" wire:model="form.visi" placeholder="Masukkan Visi RPJMD Kabupaten Badung..." class="rpjmd-textarea"></textarea>
                            @error('form.visi') <span style="color: #dc2626; font-size: 0.75rem; font-weight: 700;">{{ $message }}</span> @enderror
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-rpjmd btn-outline">Batal</button>
                            <button type="button" wire:click="saveVisi" class="btn-rpjmd btn-green">💾 Simpan Visi</button>
                        </div>

                    @elseif($activeModal === 'misi')
                        <div class="rpjmd-grid-2" style="grid-template-columns: 1fr 3fr;">
                            <div>
                                <label class="rpjmd-label">No. Urut *</label>
                                <input type="number" wire:model="form.urutan" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">Uraian Misi *</label>
                                <textarea rows="3" wire:model="form.misi" placeholder="Masukkan uraian Misi..." class="rpjmd-textarea"></textarea>
                                @error('form.misi') <span style="color: #dc2626; font-size: 0.75rem; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-rpjmd btn-outline">Batal</button>
                            <button type="button" wire:click="saveMisi" class="btn-rpjmd btn-green">💾 Simpan Misi</button>
                        </div>

                    @elseif($activeModal === 'tujuan')
                        <div class="rpjmd-grid-2" style="grid-template-columns: 1fr 3fr;">
                            <div>
                                <label class="rpjmd-label">No. Urut *</label>
                                <input type="number" wire:model="form.urutan" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">Uraian Tujuan RPJMD *</label>
                                <textarea rows="3" wire:model="form.tujuan" placeholder="Masukkan uraian Tujuan..." class="rpjmd-textarea"></textarea>
                                @error('form.tujuan') <span style="color: #dc2626; font-size: 0.75rem; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-rpjmd btn-outline">Batal</button>
                            <button type="button" wire:click="saveTujuan" class="btn-rpjmd btn-green">💾 Simpan Tujuan</button>
                        </div>

                    @elseif(in_array($activeModal, ['indikator_tujuan', 'indikator_sasaran']))
                        <div>
                            <label class="rpjmd-label">
                                Nama {{ $activeModal === 'indikator_tujuan' ? 'Indikator Tujuan' : 'Indikator Sasaran' }} *
                            </label>
                            <textarea rows="2" wire:model="form.indikator" placeholder="Masukkan rumusan indikator..." class="rpjmd-textarea"></textarea>
                            @error('form.indikator') <span style="color: #dc2626; font-size: 0.75rem; font-weight: 700;">{{ $message }}</span> @enderror
                        </div>

                        <div class="rpjmd-grid-3">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <label class="rpjmd-label">Satuan *</label>
                                    <a href="{{ url('/admin/satuans') }}" target="_blank" style="font-size: 0.65rem; font-weight: 700; color: #005000; text-decoration: underline;">Kelola Satuan ↗</a>
                                </div>
                                <select wire:model="form.satuan" class="rpjmd-select">
                                    @foreach($daftarSatuan as $sat)
                                        <option value="{{ $sat }}">{{ $sat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="rpjmd-label">Sifat Target *</label>
                                <select wire:model.live="form.sifat_target" class="rpjmd-select">
                                    <option value="non_akumulatif">Non-Akumulatif (Nilai T5)</option>
                                    <option value="akumulatif">Akumulatif (Jumlah T1–T5)</option>
                                </select>
                            </div>
                            <div>
                                <label class="rpjmd-label">Baseline (Tahun {{ $tahunBaseline }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model="form.capaian_awal" class="rpjmd-input">
                            </div>
                        </div>

                        <div class="rpjmd-grid-6" style="background: #f8fafc; padding: 0.85rem; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <div>
                                <label class="rpjmd-label">T1 ({{ $tahunAwal }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model.live="form.target_t1" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">T2 ({{ $tahunAwal + 1 }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model.live="form.target_t2" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">T3 ({{ $tahunAwal + 2 }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model.live="form.target_t3" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">T4 ({{ $tahunAwal + 3 }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model.live="form.target_t4" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">T5 ({{ $tahunAwal + 4 }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model.live="form.target_t5" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label" style="color: #005000;">Target Akhir</label>
                                <input type="text" readonly value="{{ $this->previewTargetAkhir }}" class="rpjmd-input" style="background: #dcfce7 !important; color: #005000 !important; font-weight: 800; border-color: #86efac;">
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-rpjmd btn-outline">Batal</button>
                            <button type="button" wire:click="{{ $activeModal === 'indikator_tujuan' ? 'saveIndikatorTujuan' : 'saveIndikatorSasaran' }}" class="btn-rpjmd btn-green">💾 Simpan Indikator</button>
                        </div>

                    @elseif($activeModal === 'sasaran')
                        <div class="rpjmd-grid-2" style="grid-template-columns: 1fr 3fr;">
                            <div>
                                <label class="rpjmd-label">No. Urut *</label>
                                <input type="number" wire:model="form.urutan" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">Uraian Sasaran RPJMD *</label>
                                <textarea rows="3" wire:model="form.sasaran" placeholder="Masukkan uraian Sasaran..." class="rpjmd-textarea"></textarea>
                                @error('form.sasaran') <span style="color: #dc2626; font-size: 0.75rem; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-rpjmd btn-outline">Batal</button>
                            <button type="button" wire:click="saveSasaran" class="btn-rpjmd btn-green">💾 Simpan Sasaran</button>
                        </div>

                    @elseif($activeModal === 'program')
                        <div class="rpjmd-grid-2" style="grid-template-columns: 1fr 3fr;">
                            <div>
                                <label class="rpjmd-label">Kode Program</label>
                                <input type="text" wire:model="form.kode_program" placeholder="Otomatis / ketik kode" class="rpjmd-input">
                                @error('form.kode_program') <span style="color: #dc2626; font-size: 0.72rem; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                            <div style="position: relative;">
                                <label class="rpjmd-label">Nama Program RPJMD *</label>
                                <input type="text" wire:model.live.debounce.200ms="form.nama_program" autocomplete="off" placeholder="Ketik nama atau kode program untuk mencari dari Master..." class="rpjmd-input">
                                @error('form.nama_program') <span style="color: #dc2626; font-size: 0.75rem; font-weight: 700;">{{ $message }}</span> @enderror

                                @if($showProgramSuggestions && $programSuggestions->isNotEmpty())
                                    <div style="position: absolute; left: 0; right: 0; top: 100%; margin-top: 0.25rem; z-index: 50; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 10px; box-shadow: 0 12px 24px rgba(15, 23, 42, 0.14); max-height: 15rem; overflow-y: auto;">
                                        @foreach($programSuggestions as $mp)
                                            <button type="button" wire:click="selectMasterProgram({{ $mp->id }})"
                                                style="width: 100%; text-align: left; padding: 0.55rem 0.75rem; background: #ffffff; border: none; border-bottom: 1px solid #f1f5f9; cursor: pointer; display: flex; flex-direction: column; gap: 0.12rem;">
                                                <div style="display: flex; align-items: center; gap: 0.45rem;">
                                                    <span style="background: #f0fdf4; color: #005000; border: 1px solid #bbf7d0; font-family: monospace; font-size: 0.68rem; font-weight: 800; padding: 0.08rem 0.35rem; border-radius: 4px;">
                                                        {{ $mp->kode_program }}
                                                    </span>
                                                    <span style="font-size: 0.8rem; font-weight: 700; color: #0f172a;">
                                                        {{ $mp->nama_program }}
                                                    </span>
                                                </div>
                                                @if($mp->nama_bidang_urusan)
                                                    <span style="font-size: 0.66rem; color: #64748b;">
                                                        {{ $mp->nama_bidang_urusan }}
                                                    </span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="rpjmd-label">OPD Pengampu Program (bisa pilih 1 atau lebih dari dropdown)</label>
                            <select wire:model.live="form.selected_opd_id" class="rpjmd-select">
                                <option value="">-- Pilih OPD Pengampu Program --</option>
                                @foreach($daftarOpd as $opd)
                                    <option value="{{ $opd->id }}">[{{ $opd->singkatan }}] {{ $opd->nama_instansi }}</option>
                                @endforeach
                            </select>

                            @php
                                $selectedOpdIds = array_map('strval', (array) ($form['opd_ids'] ?? []));
                                $selectedOpds = $daftarOpd->whereIn('id', array_map('intval', $selectedOpdIds));
                            @endphp
                            @if($selectedOpds->isNotEmpty())
                                <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.55rem;">
                                    @foreach($selectedOpds as $sOpd)
                                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: #f0fdf4; color: #005000; border: 1px solid #86efac; font-size: 0.74rem; font-weight: 700; padding: 0.25rem 0.55rem; border-radius: 9999px;">
                                            [{{ $sOpd->singkatan }}] {{ $sOpd->nama_instansi }}
                                            <button type="button" wire:click="removeOpdFromForm('{{ $sOpd->id }}')" style="background: none; border: none; color: #be123c; font-weight: 900; cursor: pointer; padding: 0 0.15rem;">✕</button>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-rpjmd btn-outline">Batal</button>
                            <button type="button" wire:click="saveProgram" class="btn-rpjmd btn-green">💾 Simpan Program</button>
                        </div>

                    @elseif($activeModal === 'indikator_program')
                        <div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e3a8a; font-size: 0.75rem; font-weight: 600; padding: 0.65rem 0.85rem; border-radius: 10px;">
                            ℹ️ Setiap Program RPJMD hanya memiliki <strong>1 Indikator Program</strong> beserta Target Kinerja &amp; Pagu Indikatif 5 tahun ({{ $tahunAwal }}–{{ $tahunAkhir }}).
                        </div>

                        <div>
                            <label class="rpjmd-label">Rumusan Indikator Program (Tunggal) *</label>
                            <textarea rows="2" wire:model="form.indikator_program" placeholder="Masukkan 1 indikator utama untuk Program ini..." class="rpjmd-textarea"></textarea>
                            @error('form.indikator_program') <span style="color: #dc2626; font-size: 0.75rem; font-weight: 700;">{{ $message }}</span> @enderror
                        </div>

                        <div class="rpjmd-grid-3">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <label class="rpjmd-label">Satuan *</label>
                                    <a href="{{ url('/admin/satuans') }}" target="_blank" style="font-size: 0.65rem; font-weight: 700; color: #005000; text-decoration: underline;">Kelola Satuan ↗</a>
                                </div>
                                <select wire:model="form.satuan" class="rpjmd-select">
                                    @foreach($daftarSatuan as $sat)
                                        <option value="{{ $sat }}">{{ $sat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="rpjmd-label">Sifat Target *</label>
                                <select wire:model.live="form.sifat_target" class="rpjmd-select">
                                    <option value="non_akumulatif">Non-Akumulatif (Nilai T5)</option>
                                    <option value="akumulatif">Akumulatif (Jumlah T1–T5)</option>
                                </select>
                            </div>
                            <div>
                                <label class="rpjmd-label">Baseline (Tahun {{ $tahunBaseline }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model="form.capaian_awal" class="rpjmd-input">
                            </div>
                        </div>

                        <div class="rpjmd-grid-6" style="background: #f0fdf4; padding: 0.85rem; border-radius: 10px; border: 1px solid #bbf7d0;">
                            <div>
                                <label class="rpjmd-label">T1 ({{ $tahunAwal }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model.live="form.target_t1" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">T2 ({{ $tahunAwal + 1 }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model.live="form.target_t2" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">T3 ({{ $tahunAwal + 2 }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model.live="form.target_t3" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">T4 ({{ $tahunAwal + 3 }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model.live="form.target_t4" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">T5 ({{ $tahunAwal + 4 }})</label>
                                <input type="text" inputmode="decimal" placeholder="Angka / rentang" wire:model.live="form.target_t5" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label" style="color: #005000;">Target Akhir</label>
                                <input type="text" readonly value="{{ $this->previewTargetAkhir }}" class="rpjmd-input" style="background: #dcfce7 !important; color: #005000 !important; font-weight: 800; border-color: #86efac;">
                            </div>
                        </div>

                        <div class="rpjmd-grid-3" style="background: #fff7ed; padding: 0.85rem; border-radius: 10px; border: 1px solid #fed7aa;">
                            <div>
                                <label class="rpjmd-label">Pagu T1 ({{ $tahunAwal }})</label>
                                <input type="number" step="1000000" wire:model.live="form.pagu_t1" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">Pagu T2 ({{ $tahunAwal + 1 }})</label>
                                <input type="number" step="1000000" wire:model.live="form.pagu_t2" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">Pagu T3 ({{ $tahunAwal + 2 }})</label>
                                <input type="number" step="1000000" wire:model.live="form.pagu_t3" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">Pagu T4 ({{ $tahunAwal + 3 }})</label>
                                <input type="number" step="1000000" wire:model.live="form.pagu_t4" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label">Pagu T5 ({{ $tahunAwal + 4 }})</label>
                                <input type="number" step="1000000" wire:model.live="form.pagu_t5" class="rpjmd-input">
                            </div>
                            <div>
                                <label class="rpjmd-label" style="color: #c2410c;">Total Pagu 5 Tahun</label>
                                <input type="text" readonly value="{{ \App\Services\MonevService::formatRupiah($this->previewPagu5Tahun) }}" class="rpjmd-input" style="background: #ffedd5 !important; color: #9a3412 !important; font-weight: 800; border-color: #fdba74;">
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-rpjmd btn-outline">Batal</button>
                            <button type="button" wire:click="saveIndikatorProgram" class="btn-rpjmd btn-green">💾 Simpan Indikator &amp; Pagu 5 Tahun</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
