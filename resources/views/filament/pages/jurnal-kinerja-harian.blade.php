<x-filament-panels::page>
    <style>
        .jkh-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* Hero Banner & Filter */
        .jkh-hero-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .jkh-hero-top {
            background: linear-gradient(135deg, #005000 0%, #166534 100%);
            border-bottom: 3px solid #FF6600;
            padding: 1.25rem 1.5rem;
            color: #ffffff;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .jkh-user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .jkh-avatar {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid #FF6600;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 900;
            color: #ffffff;
            flex-shrink: 0;
        }
        .jkh-user-name {
            font-size: 1.1rem;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            line-height: 1.3;
        }
        .jkh-user-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.35rem;
            font-size: 0.75rem;
            color: #dcfce7;
        }
        .jkh-role-pill {
            background: #FF6600;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.68rem;
            padding: 2px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .jkh-nip-pill {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.72rem;
            padding: 2px 10px;
            border-radius: 6px;
        }

        /* Filter Bar inside Hero */
        .jkh-filter-bar {
            padding: 1.1rem 1.5rem;
            background: #ffffff;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
        }
        .jkh-filter-group {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 0.75rem;
            flex: 1;
        }
        .jkh-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #475569;
            margin-bottom: 0.35rem;
        }
        .jkh-input {
            box-sizing: border-box;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.5rem 0.85rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: #1e293b;
            background-color: #f8fafc !important;
            background-image: none !important;
            outline: none;
            transition: border-color 0.2s, background-color 0.2s;
        }
        .jkh-input:focus {
            border-color: #005000;
            background-color: #ffffff !important;
        }
        select.jkh-input {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-color: #f8fafc !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23005000' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.65rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.15rem 1.15rem !important;
            padding-right: 2.25rem !important;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
        select.jkh-input:focus {
            background-color: #ffffff !important;
        }
        .jkh-btn-quick {
            padding: 0.52rem 0.95rem;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 800;
            border: 1.5px solid #cbd5e1;
            background: #f8fafc;
            color: #334155;
            cursor: pointer;
            transition: all 0.18s;
        }
        .jkh-btn-quick:hover {
            border-color: #005000;
            color: #005000;
            background: #f0fdf4;
        }
        .jkh-btn-quick.active {
            background: #005000;
            color: #ffffff;
            border-color: #005000;
            box-shadow: 0 2px 6px rgba(0, 80, 0, 0.25);
        }
        .jkh-btn-pdf {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #FF6600 0%, #ea580c 100%);
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(255, 102, 0, 0.28);
            transition: filter 0.2s, transform 0.15s;
        }
        .jkh-btn-pdf:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }

        /* Recent Active Date Chips */
        .jkh-recent-dates {
            padding: 0.65rem 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
        }
        .jkh-chip-date {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s;
        }
        .jkh-chip-date:hover {
            border-color: #005000;
            color: #005000;
            background: #f0fdf4;
        }
        .jkh-chip-date.active {
            background: #dcfce7;
            border-color: #16a34a;
            color: #065f46;
        }

        /* 4 KPI Stat Cards Grid */
        .jkh-stats-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1rem;
        }
        @media (min-width: 640px) {
            .jkh-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .jkh-stats-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
        .jkh-stat-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1.5px solid #e2e8f0;
            padding: 1.15rem 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
        }
        .jkh-stat-card.status-ok {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-color: #86efac;
        }
        .jkh-stat-card.status-empty {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-color: #fcd34d;
        }
        .jkh-stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .jkh-stat-label {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin: 0;
        }
        .jkh-stat-value {
            font-size: 1.65rem;
            font-weight: 900;
            line-height: 1.2;
            margin: 0.2rem 0;
            color: #0f172a;
        }
        .jkh-stat-sub {
            font-size: 0.72rem;
            color: #64748b;
            margin: 0;
            line-height: 1.35;
        }

        /* Role Guide & Quick Actions Card */
        .jkh-guide-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 1.1rem 1.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }
        .jkh-shortcut-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 800;
            text-decoration: none;
            background: #f0fdf4;
            color: #005000;
            border: 1px solid #bbf7d0;
            transition: all 0.15s;
        }
        .jkh-shortcut-btn:hover {
            background: #005000;
            color: #ffffff;
            border-color: #005000;
        }

        /* Copy Section */
        .jkh-copy-section {
            background: #ffffff;
            border-radius: 16px;
            border: 1.5px solid #bbf7d0;
            box-shadow: 0 4px 12px rgba(0, 80, 0, 0.05);
            overflow: hidden;
        }
        .jkh-copy-header {
            background: linear-gradient(90deg, #f0fdf4 0%, #fff7ed 100%);
            border-bottom: 1px solid #bbf7d0;
            padding: 1rem 1.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .jkh-steps {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.72rem;
            font-weight: 700;
            color: #065f46;
        }
        .jkh-step-badge {
            background: #ffffff;
            border: 1px solid #86efac;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .jkh-copy-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.25rem;
            padding: 1.25rem 1.5rem;
        }
        @media (min-width: 1024px) {
            .jkh-copy-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .jkh-copy-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .jkh-copy-box-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .jkh-copy-content {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.9rem 1rem;
            font-size: 0.85rem;
            color: #1e293b;
            line-height: 1.6;
            min-height: 92px;
        }
        .jkh-btn-copy-green {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #005000;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 0.45rem 0.9rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background 0.15s;
        }
        .jkh-btn-copy-green:hover {
            background: #14532d;
        }
        .jkh-btn-copy-orange {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #FF6600;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 0.45rem 0.9rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: filter 0.15s;
        }
        .jkh-btn-copy-orange:hover {
            filter: brightness(1.08);
        }
        .jkh-btn-copy-orange:disabled,
        .jkh-btn-copy-green:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        /* Log Table */
        .jkh-table-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .jkh-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .jkh-table thead tr {
            background: linear-gradient(135deg, #005000 0%, #166534 100%);
            border-bottom: 3px solid #FF6600;
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .jkh-table th {
            padding: 0.95rem 1rem;
        }
        .jkh-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.82rem;
            color: #334155;
            vertical-align: middle;
        }
        .jkh-table tbody tr:hover {
            background: #f0fdf4;
        }
    </style>

    <div class="jkh-wrapper">
        {{-- 1. HERO IDENTITAS PEGAWAI & FILTER BAR --}}
        <div class="jkh-hero-card">
            <div class="jkh-hero-top">
                <div class="jkh-user-info">
                    <div class="jkh-avatar">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="jkh-user-name">{{ $user->name }}</h2>
                        <div class="jkh-user-meta">
                            <span class="jkh-role-pill">{{ str_replace('_', ' ', $user->role) }}</span>
                            @if($user->nip)
                                <span class="jkh-nip-pill">NIP: {{ $user->nip }}</span>
                            @endif
                            <span>•</span>
                            <span style="font-weight: 700; color: #ffffff;">
                                {{ $user->perangkatDaerah->nama_instansi ?? 'Tim Evaluasi / Admin SAKIP Kabupaten Badung' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: #bbf7d0; font-weight: 700;">
                        Periode Jurnal Kinerja
                    </div>
                    <div style="font-size: 1rem; font-weight: 900; color: #ffffff; margin-top: 2px;">
                        {{ $tanggal_indo }}
                    </div>
                </div>
            </div>

            <div class="jkh-filter-bar">
                <div class="jkh-filter-group">
                    <div>
                        <label for="filter-tanggal" class="jkh-label">Pilih Tanggal Kinerja</label>
                        <input id="filter-tanggal" type="date" wire:model.live="tanggal" class="jkh-input">
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.45rem;">
                        <button type="button" wire:click="setHariIni"
                            class="jkh-btn-quick {{ $tanggal === now()->toDateString() ? 'active' : '' }}">
                            Hari Ini
                        </button>
                        <button type="button" wire:click="setKemarin"
                            class="jkh-btn-quick {{ $tanggal === now()->subDay()->toDateString() ? 'active' : '' }}">
                            Kemarin
                        </button>
                    </div>

                    @if($isAdmin)
                        <div style="flex: 1; min-width: 240px; max-width: 420px;">
                            <label for="filter-pegawai" class="jkh-label">Pantau Pegawai / Operator OPD (Mode Admin)</label>
                            <select id="filter-pegawai" wire:model.live="user_id" class="jkh-input" style="width: 100%;">
                                @foreach($daftarPegawai as $pegawai)
                                    <option value="{{ $pegawai->id }}">
                                        {{ $pegawai->name }} — [{{ strtoupper(str_replace('_', ' ', $pegawai->role)) }}] {{ $pegawai->perangkatDaerah ? '• ' . $pegawai->perangkatDaerah->nama_instansi : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div>
                    <button type="button" wire:click="cetakBuktiPdf" wire:loading.attr="disabled" class="jkh-btn-pdf">
                        <x-heroicon-o-printer class="w-4 h-4" />
                        <span wire:loading.remove wire:target="cetakBuktiPdf">Cetak Bukti Kinerja (PDF)</span>
                        <span wire:loading wire:target="cetakBuktiPdf">Menyiapkan PDF...</span>
                    </button>
                </div>
            </div>

            {{-- Riwayat Tanggal Aktif Terakhir --}}
            @if(!empty($tanggal_aktif_terakhir))
                <div class="jkh-recent-dates">
                    <span style="font-weight: 800; color: #475569; display: inline-flex; align-items: center; gap: 4px;">
                        <x-heroicon-o-calendar-days class="w-4 h-4" style="color: #005000;" />
                        Riwayat Tanggal Berkinerja Terakhir:
                    </span>
                    @foreach($tanggal_aktif_terakhir as $itemTgl)
                        <button type="button" wire:click="pilihTanggal('{{ $itemTgl['tanggal'] }}')"
                            class="jkh-chip-date {{ $tanggal === $itemTgl['tanggal'] ? 'active' : '' }}">
                            <span>{{ $itemTgl['label'] }}</span>
                            <span style="background: #005000; color: #fff; padding: 1px 6px; border-radius: 999px; font-size: 0.65rem;">
                                {{ $itemTgl['total'] }} aksi
                            </span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 2. EMPAT KARTU INDIKATOR KINERJA HARIAN (HORIZONTAL 4 KOLOM) --}}
        <div class="jkh-stats-grid">
            {{-- Kartu 1: Status Kinerja --}}
            <div class="jkh-stat-card {{ $sudah_berkinerja ? 'status-ok' : 'status-empty' }}">
                <div class="jkh-stat-icon" style="background: {{ $sudah_berkinerja ? '#16a34a' : '#f59e0b' }}; color: #ffffff;">
                    @if($sudah_berkinerja)
                        <x-heroicon-s-check-badge class="w-6 h-6" />
                    @else
                        <x-heroicon-s-exclamation-triangle class="w-6 h-6" />
                    @endif
                </div>
                <div>
                    <p class="jkh-stat-label" style="color: {{ $sudah_berkinerja ? '#065f46' : '#92400e' }};">Status Kinerja Harian</p>
                    <p class="jkh-stat-value" style="font-size: 1.15rem; color: {{ $sudah_berkinerja ? '#065f46' : '#b45309' }}; margin-top: 0.3rem;">
                        {{ $sudah_berkinerja ? '✓ SUDAH BERKINERJA' : '⏳ BELUM BERKINERJA' }}
                    </p>
                    <p class="jkh-stat-sub" style="color: {{ $sudah_berkinerja ? '#047857' : '#92400e' }};">
                        {{ $sudah_berkinerja ? 'Aktivitas Anda telah terekam otomatis di sistem.' : 'Belum ada penyimpanan/verifikasi pada tanggal ini.' }}
                    </p>
                </div>
            </div>

            {{-- Kartu 2: Total Aktivitas --}}
            <div class="jkh-stat-card">
                <div class="jkh-stat-icon" style="background: #ecfdf5; color: #005000; border: 1px solid #bbf7d0;">
                    <x-heroicon-o-bolt class="w-6 h-6" />
                </div>
                <div>
                    <p class="jkh-stat-label">Total Aktivitas Terekam</p>
                    <p class="jkh-stat-value" style="color: #005000;">
                        {{ $total_aktivitas }} <span style="font-size: 0.85rem; font-weight: 700; color: #64748b;">Aksi</span>
                    </p>
                    <p class="jkh-stat-sub">Jumlah simpan, update, & finalisasi data</p>
                </div>
            </div>

            {{-- Kartu 3: Kriteria LKE Dikerjakan --}}
            <div class="jkh-stat-card">
                <div class="jkh-stat-icon" style="background: #fff7ed; color: #FF6600; border: 1px solid #fed7aa;">
                    <x-heroicon-o-clipboard-document-check class="w-6 h-6" />
                </div>
                <div>
                    <p class="jkh-stat-label">Cakupan Evaluasi LKE</p>
                    <p class="jkh-stat-value" style="color: #FF6600;">
                        {{ $jumlah_kriteria_lke }} <span style="font-size: 0.85rem; font-weight: 700; color: #64748b;">Kriteria</span>
                    </p>
                    <p class="jkh-stat-sub">
                        @if($user->role === 'perangkat_daerah')
                            Pada {{ $jumlah_sub_komponen ?? 0 }} Sub-Komponen LKE
                        @else
                            Mencakup {{ $jumlah_instansi }} Perangkat Daerah
                        @endif
                    </p>
                </div>
            </div>

            {{-- Kartu 4: Rentang Waktu Kerja --}}
            <div class="jkh-stat-card">
                <div class="jkh-stat-icon" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">
                    <x-heroicon-o-clock class="w-6 h-6" />
                </div>
                <div>
                    <p class="jkh-stat-label">Rentang Waktu Aktivitas</p>
                    <p class="jkh-stat-value" style="font-size: 1.25rem; color: #1e293b; margin-top: 0.3rem;">
                        @if($sudah_berkinerja)
                            {{ $waktu_mulai }} – {{ $waktu_selesai }} <span style="font-size: 0.72rem; font-weight: 800; color: #2563eb;">WITA</span>
                        @else
                            Belum Tercatat
                        @endif
                    </p>
                    <p class="jkh-stat-sub">Waktu aksi pertama hingga terakhir</p>
                </div>
            </div>
        </div>

        {{-- 3. PANDUAN INDIKATOR KINERJA & PINTASAN KERJA SESUAI ROLE --}}
        <div class="jkh-guide-card">
            <div style="flex: 1; min-width: 260px;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="background: #005000; color: #fff; font-size: 0.65rem; font-weight: 800; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                        Info Kinerja {{ str_replace('_', ' ', $user->role) }}
                    </span>
                    <span style="font-size: 0.82rem; font-weight: 800; color: #1e293b;">
                        Aktivitas apa saja yang dihitung sebagai Kinerja Harian Anda?
                    </span>
                </div>
                <p style="font-size: 0.76rem; color: #475569; margin: 0.35rem 0 0; line-height: 1.45;">
                    @if($user->role === 'perangkat_daerah')
                        Setiap kali Anda <strong>mengisi jawaban penilaian mandiri LKE</strong>, <strong>mengunggah link bukti dukung</strong>, <strong>mengirim laporan ke Evaluator</strong>, atau <strong>mengisi Tindak Lanjut LHE</strong>, sistem otomatis mencatatnya sebagai bukti kinerja harian Anda.
                    @elseif($user->role === 'evaluator')
                        Setiap kali Anda <strong>memverifikasi & menilai kriteria LKE OPD</strong>, <strong>memberikan catatan rekomendasi</strong>, <strong>memfinalisasi verifikasi</strong>, <strong>menyusun LHE</strong>, atau <strong>memverifikasi Tindak Lanjut</strong>, sistem otomatis merekam kinerja harian Anda.
                    @else
                        Setiap kali Anda <strong>mengelola jadwal evaluasi</strong>, <strong>memperbarui instrumen komponen/kriteria LKE</strong>, <strong>memverifikasi LKE</strong>, atau <strong>menerbitkan dokumen LHE resmi</strong>, sistem otomatis merekam kinerja harian Anda.
                    @endif
                </p>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                @if($user->role === 'perangkat_daerah')
                    <a href="{{ route('filament.admin.pages.evaluasi-internal') }}" class="jkh-shortcut-btn">
                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                        Isi Evaluasi Mandiri
                    </a>
                    <a href="{{ route('filament.admin.pages.upload-dokumen') }}" class="jkh-shortcut-btn">
                        <x-heroicon-o-cloud-arrow-up class="w-4 h-4" />
                        Upload Bukti Dukung
                    </a>
                    <a href="{{ route('filament.admin.resources.tindak-lanjuts.index') }}" class="jkh-shortcut-btn">
                        <x-heroicon-o-clipboard-document-check class="w-4 h-4" />
                        Tindak Lanjut LHE
                    </a>
                @else
                    <a href="{{ route('filament.admin.pages.lembar-evaluasi') }}" class="jkh-shortcut-btn">
                        <x-heroicon-o-clipboard-document-check class="w-4 h-4" />
                        Buka Lembar Evaluasi (LKE)
                    </a>
                    <a href="{{ route('filament.admin.pages.laporan-hasil-evaluasi') }}" class="jkh-shortcut-btn">
                        <x-heroicon-o-document-text class="w-4 h-4" />
                        Susun Laporan LHE
                    </a>
                    <a href="{{ route('filament.admin.resources.tindak-lanjuts.index') }}" class="jkh-shortcut-btn">
                        <x-heroicon-o-check-circle class="w-4 h-4" />
                        Verifikasi Tindak Lanjut
                    </a>
                @endif
            </div>
        </div>

        {{-- 4. KOTAK URAIAN KEGIATAN SIAP SALIN (2 KOLOM BERDAMPINGAN) --}}
        @php
            $teksPoin = collect($uraian_poin)->map(fn ($p) => '- ' . $p)->implode("\n");
        @endphp
        <div class="jkh-copy-section"
            x-data="{
                copiedParagraf: false,
                copiedPoin: false,
                paragrafText: @js($uraian_paragraf),
                poinText: @js($teksPoin),
                copyText(text, type) {
                    if (!text) return;
                    navigator.clipboard.writeText(text).then(() => {
                        if (type === 'paragraf') {
                            this.copiedParagraf = true;
                            setTimeout(() => this.copiedParagraf = false, 2000);
                        } else {
                            this.copiedPoin = true;
                            setTimeout(() => this.copiedPoin = false, 2000);
                        }
                    });
                }
            }">
            <div class="jkh-copy-header">
                <div>
                    <h3 style="font-size: 0.9rem; font-weight: 900; color: #005000; text-transform: uppercase; margin: 0;">
                        📋 Ringkasan Uraian Kinerja Harian (Siap Salin ke Aplikasi Kinerja Harian Badung)
                    </h3>
                    <p style="font-size: 0.75rem; color: #475569; margin: 2px 0 0;">
                        Uraian di bawah ini disusun otomatis dari aktivitas kerja Anda pada tanggal <strong>{{ $tanggal_indo }}</strong>.
                    </p>
                </div>

                <div class="jkh-steps">
                    <span class="jkh-step-badge">1. Klik Tombol Salin</span>
                    <span>→</span>
                    <span class="jkh-step-badge">2. Paste di Kinerja Harian</span>
                    <span>→</span>
                    <span class="jkh-step-badge">3. Lampirkan PDF Bukti Kinerja</span>
                </div>
            </div>

            <div class="jkh-copy-grid">
                {{-- Kolom Kiri: Format Paragraf --}}
                <div class="jkh-copy-box">
                    <div>
                        <div class="jkh-copy-box-head">
                            <div>
                                <span style="font-size: 0.76rem; font-weight: 800; text-transform: uppercase; color: #005000;">
                                    Opsi 1: Format Paragraf Naratif
                                </span>
                                <span style="display: block; font-size: 0.68rem; color: #64748b;">
                                    Cocok untuk kolom uraian tugas harian berbentuk kalimat utuh
                                </span>
                            </div>
                            <button type="button"
                                @click="copyText(paragrafText, 'paragraf')"
                                @if(!$sudah_berkinerja) disabled @endif
                                class="jkh-btn-copy-green">
                                <x-heroicon-o-clipboard-document class="w-4 h-4" />
                                <span x-show="!copiedParagraf">Salin Paragraf</span>
                                <span x-show="copiedParagraf" x-cloak>✓ Berhasil Disalin!</span>
                            </button>
                        </div>

                        <div class="jkh-copy-content">
                            {{ $uraian_paragraf }}
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Format Poin-Poin --}}
                <div class="jkh-copy-box">
                    <div>
                        <div class="jkh-copy-box-head">
                            <div>
                                <span style="font-size: 0.76rem; font-weight: 800; text-transform: uppercase; color: #ea580c;">
                                    Opsi 2: Format Poin Rincian Output
                                </span>
                                <span style="display: block; font-size: 0.68rem; color: #64748b;">
                                    Cocok untuk pelaporan per butir kegiatan / rincian sub-komponen
                                </span>
                            </div>
                            <button type="button"
                                @click="copyText(poinText, 'poin')"
                                @if(empty($uraian_poin)) disabled @endif
                                class="jkh-btn-copy-orange">
                                <x-heroicon-o-list-bullet class="w-4 h-4" />
                                <span x-show="!copiedPoin">Salin Poin-Poin</span>
                                <span x-show="copiedPoin" x-cloak>✓ Berhasil Disalin!</span>
                            </button>
                        </div>

                        <div class="jkh-copy-content">
                            @if(!empty($uraian_poin))
                                <ul style="margin: 0; padding-left: 1.2rem; display: flex; flex-direction: column; gap: 0.35rem;">
                                    @foreach($uraian_poin as $poin)
                                        <li>{{ $poin }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <div style="color: #94a3b8; font-style: italic; text-align: center; padding: 1.25rem 0;">
                                    Belum ada butir rincian kegiatan yang tercatat pada tanggal ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. TABEL RINCIAN LOG AKTIVITAS HARIAN --}}
        <div class="jkh-table-card">
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.5rem;">
                <div>
                    <h3 style="font-size: 0.9rem; font-weight: 900; color: #0f172a; text-transform: uppercase; margin: 0;">
                        🕒 Riwayat Detail Log Aktivitas ({{ $total_aktivitas }} Catatan)
                    </h3>
                    <p style="font-size: 0.74rem; color: #64748b; margin: 2px 0 0;">
                        Rekaman waktu nyata (real-time audit log) seluruh aktivitas pada {{ $tanggal_indo }}
                    </p>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="jkh-table">
                    <thead>
                        <tr>
                            <th style="width: 52px; text-align: center;">NO</th>
                            <th style="width: 110px; text-align: center;">WAKTU (WITA)</th>
                            <th style="width: 170px;">KATEGORI PEKERJAAN</th>
                            <th style="width: 110px; text-align: center;">JENIS AKSI</th>
                            <th>URAIAN AKTIVITAS & KRITERIA</th>
                            <th style="width: 220px;">INSTANSI TERKAIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rincian_log as $index => $log)
                            @php
                                $eventBadgeStyle = match($log['event'] ?? '') {
                                    'created' => 'background: #dcfce7; color: #065f46; border: 1px solid #86efac;',
                                    'updated' => 'background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd;',
                                    'submitted', 'finalized' => 'background: #ffedd5; color: #9a3412; border: 1px solid #fdba74;',
                                    'deleted', 'reopened' => 'background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;',
                                    default => 'background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;',
                                };
                            @endphp
                            <tr>
                                <td style="text-align: center; font-weight: 800; color: #64748b;">{{ $index + 1 }}</td>
                                <td style="text-align: center; font-family: monospace; font-weight: 800; color: #0f172a; font-size: 0.82rem;">
                                    {{ $log['waktu'] }}
                                </td>
                                <td>
                                    <span style="display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; background: #f0fdf4; color: #005000; border: 1px solid #bbf7d0;">
                                        {{ $log['kategori'] }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 0.68rem; font-weight: 800; {{ $eventBadgeStyle }}">
                                        {{ $log['event_label'] ?? ucfirst($log['event']) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #1e293b; line-height: 1.4;">
                                        {{ $log['deskripsi'] }}
                                    </div>
                                    @if(!empty($log['sub_detail']))
                                        <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">
                                            📌 {{ $log['sub_detail'] }}
                                        </div>
                                    @endif
                                </td>
                                <td style="font-weight: 700; color: #334155; font-size: 0.78rem;">
                                    {{ $log['instansi'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 2.5rem 1.5rem; text-align: center;">
                                    <div style="max-width: 460px; margin: 0 auto;">
                                        <div style="width: 48px; height: 48px; border-radius: 999px; background: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; border: 1px solid #fde68a;">
                                            <x-heroicon-o-clock class="w-6 h-6" />
                                        </div>
                                        <div style="font-size: 0.9rem; font-weight: 800; color: #1e293b;">
                                            Belum Ada Aktivitas Tercatat pada {{ $tanggal_indo }}
                                        </div>
                                        <p style="font-size: 0.78rem; color: #64748b; margin: 0.35rem 0 0; line-height: 1.5;">
                                            Silakan lakukan pengisian/pembaruan LKE, upload bukti dukung, verifikasi nilai, atau tindak lanjut rekomendasi agar aktivitas Anda langsung terekam otomatis di halaman ini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
