<x-filament-panels::page>
    <style>
        .nom-wrap { display: flex; flex-direction: column; gap: 1.25rem; color: #0f172a; }

        .nom-hero {
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

        .nom-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.85rem;
        }
        @media (max-width: 1024px) { .nom-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 640px) { .nom-stats { grid-template-columns: 1fr; } }

        .nom-stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.9rem 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        }

        .nom-filter-bar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.85rem 1rem;
            display: grid;
            grid-template-columns: 2fr 1.2fr 1.4fr 0.8fr auto;
            gap: 0.65rem;
            align-items: center;
        }
        @media (max-width: 1024px) { .nom-filter-bar { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 640px) { .nom-filter-bar { grid-template-columns: 1fr; } }

        .nom-pager {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.6rem 1rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            font-size: 0.76rem;
            color: #475569;
        }

        .btn-nom {
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
            white-space: nowrap;
        }
        .btn-nom:disabled { opacity: 0.45; cursor: not-allowed; }
        .btn-nom-sm { font-size: 0.7rem; padding: 0.3rem 0.65rem; border-radius: 8px; }
        .btn-green { background: #005000; color: #ffffff; }
        .btn-green:hover { background: #003d00; }
        .btn-orange { background: #FF6600; color: #ffffff; }
        .btn-orange:hover { background: #e55b00; }
        .btn-glass { background: rgba(255,255,255,0.16); color: #ffffff; border-color: rgba(255,255,255,0.28); }
        .btn-outline { background: #ffffff; color: #334155; border-color: #cbd5e1; }
        .btn-outline:hover { background: #f8fafc; }
        .btn-soft-green { background: #f0fdf4; color: #005000; border-color: #bbf7d0; }
        .btn-soft-blue { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .btn-soft-red { background: #fff1f2; color: #be123c; border-color: #fecdd3; }

        .nom-prog-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #005000;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
        }

        .nom-prog-head {
            padding: 0.85rem 1.15rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .nom-keg-wrap {
            padding: 0.85rem 1.15rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .nom-keg-card {
            background: #ffffff;
            border: 1px solid #fed7aa;
            border-left: 3px solid #FF6600;
            border-radius: 10px;
            overflow: hidden;
        }

        .nom-keg-head {
            padding: 0.7rem 0.95rem;
            background: #fffaf5;
            border-bottom: 1px solid #ffedd5;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
        }

        .nom-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.74rem;
            color: #1e293b;
        }
        .nom-table th {
            background: #f8fafc;
            color: #475569;
            font-size: 0.66rem;
            font-weight: 800;
            text-transform: uppercase;
            padding: 0.5rem 0.65rem;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        .nom-table td {
            padding: 0.52rem 0.65rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        .nom-table tr:last-child td { border-bottom: none; }

        /* Modal */
        .nom-modal-backdrop {
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
        .nom-modal-box {
            background: #ffffff;
            color: #0f172a;
            width: 100%;
            max-width: 44rem;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }
        .nom-modal-head {
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
        .nom-modal-body { padding: 1.35rem; display: flex; flex-direction: column; gap: 0.9rem; }
        .nom-label { display: block; font-size: 0.75rem; font-weight: 700; color: #334155; margin-bottom: 0.3rem; }
        .nom-input, .nom-textarea, .nom-select {
            width: 100%;
            background: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.5rem 0.72rem;
            font-size: 0.82rem;
            outline: none;
        }
        .nom-input:focus, .nom-textarea:focus, .nom-select:focus {
            border-color: #005000;
            box-shadow: 0 0 0 3px rgba(0,80,0,0.12);
        }
        .nom-grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.85rem; }
        .nom-grid-1-3 { display: grid; grid-template-columns: 1fr 2.5fr; gap: 0.85rem; }
        @media (max-width: 768px) { .nom-grid-2, .nom-grid-1-3 { grid-template-columns: 1fr; } }
    </style>

    <div class="nom-wrap">
        {{-- HERO --}}
        <div class="nom-hero">
            <div>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.35rem;">
                    <span style="background: #FF6600; color: #fff; font-size: 0.68rem; font-weight: 800; padding: 0.2rem 0.65rem; border-radius: 9999px; text-transform: uppercase;">
                        Nomenklatur RKPD 2026 · Permendagri 90/2019 &amp; Kepmendagri
                    </span>
                </div>
                <h2 style="font-size: 1.2rem; font-weight: 800; margin: 0; color: #ffffff;">
                    Master Program, Kegiatan &amp; Sub Kegiatan
                </h2>
                <p style="font-size: 0.78rem; color: #dcfce7; margin: 0.2rem 0 0 0;">
                    Klasifikasi &amp; kodefikasi 6 segmen (Urusan · Bidang · Program · Kegiatan · Sub Kegiatan) beserta Kinerja, Indikator, Satuan &amp; SPM
                </p>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <button type="button" wire:click="exportCsv" class="btn-nom btn-glass">
                    📥 Unduh CSV
                </button>
                @if($canManage)
                    <button type="button" wire:click="openImportModal" class="btn-nom btn-glass">
                        📤 Import CSV
                    </button>
                    <button type="button" wire:click="openProgramModal()" class="btn-nom btn-orange">
                        + Tambah Program
                    </button>
                @endif
            </div>
        </div>

        {{-- 4 KARTU RINGKASAN --}}
        <div class="nom-stats">
            <div class="nom-stat-card">
                <div>
                    <p style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; color: #64748b; margin: 0;">Urusan &amp; Bidang</p>
                    <p style="font-size: 1.3rem; font-weight: 900; color: #0f172a; margin: 0.15rem 0;">{{ $totalUrusan }} <small style="font-size: 0.72rem; font-weight: 700; color: #64748b;">Urusan</small></p>
                    <p style="font-size: 0.7rem; color: #64748b; margin: 0;">{{ $totalBidang }} Bidang Urusan</p>
                </div>
                <span style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; width: 2.25rem; height: 2.25rem; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900;">U</span>
            </div>

            <div class="nom-stat-card">
                <div>
                    <p style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; color: #64748b; margin: 0;">Master Program</p>
                    <p style="font-size: 1.3rem; font-weight: 900; color: #005000; margin: 0.15rem 0;">{{ number_format($totalProgram, 0, ',', '.') }}</p>
                    <p style="font-size: 0.7rem; color: #64748b; margin: 0;">Program Kabupaten</p>
                </div>
                <span style="background: #f0fdf4; color: #005000; border: 1px solid #bbf7d0; width: 2.25rem; height: 2.25rem; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900;">1</span>
            </div>

            <div class="nom-stat-card">
                <div>
                    <p style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; color: #64748b; margin: 0;">Master Kegiatan</p>
                    <p style="font-size: 1.3rem; font-weight: 900; color: #FF6600; margin: 0.15rem 0;">{{ number_format($totalKegiatan, 0, ',', '.') }}</p>
                    <p style="font-size: 0.7rem; color: #64748b; margin: 0;">Kegiatan OPD</p>
                </div>
                <span style="background: #fff7ed; color: #FF6600; border: 1px solid #fed7aa; width: 2.25rem; height: 2.25rem; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900;">2</span>
            </div>

            <div class="nom-stat-card">
                <div>
                    <p style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; color: #64748b; margin: 0;">Master Sub Kegiatan</p>
                    <p style="font-size: 1.3rem; font-weight: 900; color: #1d4ed8; margin: 0.15rem 0;">{{ number_format($totalSubKegiatan, 0, ',', '.') }}</p>
                    <p style="font-size: 0.7rem; color: #64748b; margin: 0;">{{ number_format($totalSpm, 0, ',', '.') }} SPM · {{ number_format($totalSubKegiatan - $totalSpm, 0, ',', '.') }} Non-SPM</p>
                </div>
                <span style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; width: 2.25rem; height: 2.25rem; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900;">3</span>
            </div>
        </div>

        {{-- FILTER & SEARCH BAR --}}
        <div class="nom-filter-bar">
            <input type="text" wire:model.live.debounce.350ms="search"
                placeholder="Cari kode / nama Program, Kegiatan, Sub Kegiatan, atau Indikator..."
                class="nom-input">

            <select wire:model.live="filterUrusan" class="nom-select">
                <option value="">Semua Urusan ({{ $totalUrusan }})</option>
                @foreach($daftarUrusan as $u)
                    <option value="{{ $u->kode_urusan }}">{{ $u->kode_urusan }} — {{ \Illuminate\Support\Str::limit($u->nama_urusan, 42) }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterBidang" class="nom-select">
                <option value="">Semua Bidang Urusan</option>
                @foreach($daftarBidang as $b)
                    <option value="{{ $b->kode_bidang_urusan }}">{{ $b->kode_bidang_urusan }} — {{ \Illuminate\Support\Str::limit($b->nama_bidang_urusan, 42) }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterSpm" class="nom-select">
                <option value="">Semua SPM</option>
                <option value="SPM">Hanya SPM</option>
                <option value="Non-SPM">Non-SPM</option>
            </select>

            <button type="button" wire:click="resetFilters" class="btn-nom btn-outline">
                Reset
            </button>
        </div>

        {{-- PAGINATION TOP --}}
        <div class="nom-pager">
            <span>
                Menampilkan <strong>{{ $programs->count() }}</strong> dari <strong>{{ number_format($totalFiltered, 0, ',', '.') }}</strong> Program (Halaman <strong>{{ $currentPage }}</strong> / {{ $lastPage }})
            </span>
            <div style="display: flex; gap: 0.4rem;">
                <button type="button" wire:click="prevPage" @disabled($currentPage <= 1) class="btn-nom btn-nom-sm btn-outline">
                    ← Sebelumnya
                </button>
                <button type="button" wire:click="nextPage({{ $lastPage }})" @disabled($currentPage >= $lastPage) class="btn-nom btn-nom-sm btn-outline">
                    Berikutnya →
                </button>
            </div>
        </div>

        {{-- DAFTAR PROGRAM -> KEGIATAN -> SUB KEGIATAN --}}
        @forelse($programs as $prog)
            <div class="nom-prog-card">
                <div class="nom-prog-head">
                    <div style="flex: 1; min-width: 240px;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <span style="background: #005000; color: #ffffff; font-family: monospace; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.55rem; border-radius: 6px;">
                                {{ $prog->kode_program }}
                            </span>
                            @if($prog->kode_urusan)
                                <span style="background: #f1f5f9; color: #334155; font-size: 0.66rem; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 5px;">
                                    Urusan {{ $prog->kode_urusan }}
                                </span>
                            @endif
                            @if($prog->nama_bidang_urusan)
                                <span style="font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase;">
                                    {{ $prog->kode_bidang_urusan }} {{ $prog->nama_bidang_urusan }}
                                </span>
                            @endif
                        </div>
                        <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0.3rem 0 0 0;">
                            {{ $prog->nama_program }}
                        </h3>
                        @if($prog->indikator_program)
                            <p style="font-size: 0.74rem; color: #475569; margin: 0.2rem 0 0 0;">
                                <strong>Indikator Program:</strong> {{ $prog->indikator_program }}
                                <span style="background: #f1f5f9; color: #334155; font-weight: 700; padding: 0.1rem 0.4rem; border-radius: 4px; margin-left: 0.3rem;">{{ $prog->satuan }}</span>
                            </p>
                        @endif
                    </div>

                    @if($canManage)
                        <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                            <button type="button" wire:click="openProgramModal({{ $prog->id }})" class="btn-nom btn-nom-sm btn-outline">
                                ✏️ Edit Program
                            </button>
                            <button type="button" wire:click="openKegiatanModal({{ $prog->id }})" class="btn-nom btn-nom-sm btn-soft-green">
                                + Tambah Kegiatan
                            </button>
                            @if($canDelete)
                                <button type="button" wire:click="deleteProgram({{ $prog->id }})" wire:confirm="Hapus Program ini beserta seluruh Kegiatan & Sub Kegiatannya?" class="btn-nom btn-nom-sm btn-soft-red">
                                    🗑️
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- KEGIATAN --}}
                <div class="nom-keg-wrap">
                    @forelse($prog->kegiatans as $keg)
                        <div class="nom-keg-card">
                            <div class="nom-keg-head">
                                <div style="flex: 1; min-width: 220px;">
                                    <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                                        <span style="background: #FF6600; color: #ffffff; font-family: monospace; font-size: 0.68rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 5px;">
                                            {{ $keg->kode_kegiatan }}
                                        </span>
                                        <span style="font-size: 0.84rem; font-weight: 800; color: #0f172a;">
                                            {{ $keg->nama_kegiatan }}
                                        </span>
                                        <span style="font-size: 0.68rem; font-weight: 700; color: #64748b;">
                                            ({{ $keg->subKegiatans->count() }} Sub Kegiatan)
                                        </span>
                                    </div>
                                    @if($keg->indikator_kegiatan)
                                        <p style="font-size: 0.72rem; color: #475569; margin: 0.2rem 0 0 0;">
                                            <strong>Indikator Kegiatan:</strong> {{ $keg->indikator_kegiatan }}
                                            <span style="background: #ffedd5; color: #9a3412; font-weight: 700; padding: 0.08rem 0.35rem; border-radius: 4px; margin-left: 0.25rem;">{{ $keg->satuan }}</span>
                                        </p>
                                    @endif
                                </div>

                                @if($canManage)
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.35rem;">
                                        <button type="button" wire:click="openKegiatanModal({{ $prog->id }}, {{ $keg->id }})" class="btn-nom btn-nom-sm btn-outline">
                                            ✏️ Edit Kegiatan
                                        </button>
                                        <button type="button" wire:click="openSubKegiatanModal({{ $keg->id }})" class="btn-nom btn-nom-sm btn-soft-blue">
                                            + Tambah Sub Kegiatan
                                        </button>
                                        @if($canDelete)
                                            <button type="button" wire:click="deleteKegiatan({{ $keg->id }})" wire:confirm="Hapus Kegiatan ini beserta Sub Kegiatannya?" class="btn-nom btn-nom-sm btn-soft-red">
                                                🗑️
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- SUB KEGIATAN TABLE --}}
                            @if($keg->subKegiatans->isNotEmpty())
                                <div style="overflow-x: auto; background: #ffffff;">
                                    <table class="nom-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 140px;">Kode Sub</th>
                                                <th>Nomenklatur Sub Kegiatan</th>
                                                <th>Kinerja</th>
                                                <th>Indikator Sub Kegiatan</th>
                                                <th style="text-align: center;">Satuan</th>
                                                <th style="text-align: center;">SPM / Tipe</th>
                                                @if($canManage)<th style="text-align: right;">Aksi</th>@endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($keg->subKegiatans as $sub)
                                                <tr>
                                                    <td style="font-family: monospace; font-weight: 800; color: #1d4ed8; white-space: nowrap;">
                                                        {{ $sub->kode_sub_kegiatan }}
                                                    </td>
                                                    <td style="font-weight: 700; color: #0f172a;">
                                                        {{ $sub->nama_sub_kegiatan }}
                                                    </td>
                                                    <td style="color: #475569;">{{ $sub->kinerja ?: '-' }}</td>
                                                    <td style="color: #334155;">{{ $sub->indikator_sub_kegiatan ?: '-' }}</td>
                                                    <td style="text-align: center;">
                                                        <span style="background: #eff6ff; color: #1d4ed8; font-weight: 700; padding: 0.14rem 0.45rem; border-radius: 5px; display: inline-block;">
                                                            {{ $sub->satuan }}
                                                        </span>
                                                    </td>
                                                    <td style="text-align: center; white-space: nowrap;">
                                                        @if($sub->spm === 'SPM')
                                                            <span style="background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; font-size: 0.65rem; font-weight: 800; padding: 0.1rem 0.4rem; border-radius: 9999px;">SPM</span>
                                                        @else
                                                            <span style="background: #f1f5f9; color: #64748b; font-size: 0.65rem; font-weight: 700; padding: 0.1rem 0.4rem; border-radius: 9999px;">Non-SPM</span>
                                                        @endif
                                                        @if($sub->aktivitas_layanan)
                                                            <div style="font-size: 0.64rem; color: #64748b; margin-top: 0.18rem; font-weight: 600;">
                                                                {{ $sub->aktivitas_layanan }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    @if($canManage)
                                                        <td style="text-align: right; white-space: nowrap;">
                                                            <button type="button" wire:click="openSubKegiatanModal({{ $keg->id }}, {{ $sub->id }})" style="color: #1d4ed8; font-weight: 700; background: none; border: none; cursor: pointer;">Edit</button>
                                                            @if($canDelete)
                                                                <button type="button" wire:click="deleteSubKegiatan({{ $sub->id }})" wire:confirm="Hapus Sub Kegiatan ini?" style="color: #be123c; font-weight: 700; background: none; border: none; cursor: pointer; margin-left: 0.45rem;">Hapus</button>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div style="padding: 0.6rem 0.9rem; font-size: 0.74rem; color: #64748b; font-style: italic; background: #ffffff;">
                                    Belum ada Sub Kegiatan yang cocok dengan filter ini.
                                </div>
                            @endif
                        </div>
                    @empty
                        <div style="padding: 0.75rem; font-size: 0.75rem; color: #64748b; font-style: italic; border: 1px dashed #cbd5e1; border-radius: 8px;">
                            Belum ada Kegiatan pada Program ini. Klik <strong>"+ Tambah Kegiatan"</strong>.
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div style="background: #ffffff; border: 2px dashed #cbd5e1; border-radius: 16px; padding: 2.5rem; text-align: center;">
                <p style="font-size: 0.9rem; font-weight: 700; color: #334155;">Tidak ada data Master Program / Kegiatan / Sub Kegiatan yang sesuai filter.</p>
                <button type="button" wire:click="resetFilters" class="btn-nom btn-outline" style="margin-top: 0.65rem;">
                    Reset Filter
                </button>
            </div>
        @endforelse

        {{-- PAGINATION BOTTOM --}}
        @if($lastPage > 1)
            <div class="nom-pager">
                <span>Halaman <strong>{{ $currentPage }}</strong> dari <strong>{{ $lastPage }}</strong></span>
                <div style="display: flex; gap: 0.4rem;">
                    <button type="button" wire:click="prevPage" @disabled($currentPage <= 1) class="btn-nom btn-nom-sm btn-outline">
                        ← Sebelumnya
                    </button>
                    <button type="button" wire:click="nextPage({{ $lastPage }})" @disabled($currentPage >= $lastPage) class="btn-nom btn-nom-sm btn-outline">
                        Berikutnya →
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- ==================== MODAL INPUT / EDIT ==================== --}}
    @if($activeModal)
        <div class="nom-modal-backdrop">
            <div class="nom-modal-box">
                <div class="nom-modal-head">
                    <div>
                        <h3 style="font-weight: 800; font-size: 0.95rem; margin: 0; color: #ffffff;">
                            @switch($activeModal)
                                @case('program') {{ $editingId ? 'Edit Master Program (Format Permendagri)' : 'Tambah Master Program (Format Permendagri)' }} @break
                                @case('kegiatan') {{ $editingId ? 'Edit Master Kegiatan (Format Permendagri)' : 'Tambah Master Kegiatan (Format Permendagri)' }} @break
                                @case('sub_kegiatan') {{ $editingId ? 'Edit Master Sub Kegiatan (Format Permendagri)' : 'Tambah Master Sub Kegiatan (Format Permendagri)' }} @break
                                @case('import_csv') Import CSV Nomenklatur Permendagri @break
                            @endswitch
                        </h3>
                        @if($parentLabel)
                            <p style="font-size: 0.72rem; color: #dcfce7; margin: 0.15rem 0 0 0;">{{ $parentLabel }}</p>
                        @endif
                    </div>
                    <button type="button" wire:click="closeModal" style="background: none; border: none; color: #ffffff; font-size: 1.15rem; font-weight: 900; cursor: pointer;">✕</button>
                </div>

                <div class="nom-modal-body">
                    @if($activeModal === 'program')
                        <div class="nom-grid-2">
                            <div>
                                <label class="nom-label">Kode Urusan</label>
                                <input type="text" wire:model="form.kode_urusan" placeholder="Contoh: 1" class="nom-input">
                            </div>
                            <div>
                                <label class="nom-label">Nama Urusan</label>
                                <input type="text" wire:model="form.nama_urusan" placeholder="Contoh: URUSAN PEMERINTAHAN WAJIB..." class="nom-input">
                            </div>
                            <div>
                                <label class="nom-label">Kode Bidang Urusan</label>
                                <input type="text" wire:model="form.kode_bidang_urusan" placeholder="Contoh: 1.01" class="nom-input">
                            </div>
                            <div>
                                <label class="nom-label">Nama Bidang Urusan</label>
                                <input type="text" wire:model="form.nama_bidang_urusan" placeholder="Contoh: URUSAN PEMERINTAHAN BIDANG PENDIDIKAN" class="nom-input">
                            </div>
                        </div>
                        <div class="nom-grid-1-3">
                            <div>
                                <label class="nom-label">Kode Program *</label>
                                <input type="text" wire:model="form.kode_program" placeholder="Contoh: 1.01.02" class="nom-input">
                                @error('form.kode_program') <span style="color: #dc2626; font-size: 0.72rem; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="nom-label">Nama Program (Nomenklatur Permendagri) *</label>
                                <input type="text" wire:model="form.nama_program" placeholder="Contoh: PROGRAM PENGELOLAAN PENDIDIKAN" class="nom-input">
                                @error('form.nama_program') <span style="color: #dc2626; font-size: 0.72rem; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="nom-grid-1-3">
                            <div>
                                <label class="nom-label">Satuan Indikator *</label>
                                <select wire:model="form.satuan" class="nom-select">
                                    @foreach($daftarSatuan as $sat)
                                        <option value="{{ $sat }}">{{ $sat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="nom-label">Indikator Program (Outcome)</label>
                                <input type="text" wire:model="form.indikator_program" placeholder="Rumusan indikator capaian program..." class="nom-input">
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-nom btn-outline">Batal</button>
                            <button type="button" wire:click="saveProgram" class="btn-nom btn-green">💾 Simpan Program</button>
                        </div>

                    @elseif($activeModal === 'kegiatan')
                        <div class="nom-grid-1-3">
                            <div>
                                <label class="nom-label">Kode Kegiatan *</label>
                                <input type="text" wire:model="form.kode_kegiatan" placeholder="Contoh: 1.01.02.2.01" class="nom-input">
                                @error('form.kode_kegiatan') <span style="color: #dc2626; font-size: 0.72rem; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="nom-label">Nama Kegiatan (Nomenklatur Permendagri) *</label>
                                <input type="text" wire:model="form.nama_kegiatan" placeholder="Masukkan nama kegiatan sesuai Permendagri..." class="nom-input">
                                @error('form.nama_kegiatan') <span style="color: #dc2626; font-size: 0.72rem; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="nom-grid-1-3">
                            <div>
                                <label class="nom-label">Satuan Indikator *</label>
                                <select wire:model="form.satuan" class="nom-select">
                                    @foreach($daftarSatuan as $sat)
                                        <option value="{{ $sat }}">{{ $sat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="nom-label">Indikator Kegiatan (Output)</label>
                                <input type="text" wire:model="form.indikator_kegiatan" placeholder="Rumusan indikator keluaran kegiatan..." class="nom-input">
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-nom btn-outline">Batal</button>
                            <button type="button" wire:click="saveKegiatan" class="btn-nom btn-green">💾 Simpan Kegiatan</button>
                        </div>

                    @elseif($activeModal === 'sub_kegiatan')
                        <div class="nom-grid-1-3">
                            <div>
                                <label class="nom-label">Kode Sub Kegiatan *</label>
                                <input type="text" wire:model="form.kode_sub_kegiatan" placeholder="Contoh: 1.01.02.2.01.0001" class="nom-input">
                                @error('form.kode_sub_kegiatan') <span style="color: #dc2626; font-size: 0.72rem; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="nom-label">Nama Sub Kegiatan (Nomenklatur Permendagri) *</label>
                                <input type="text" wire:model="form.nama_sub_kegiatan" placeholder="Masukkan nama sub kegiatan sesuai Kepmendagri..." class="nom-input">
                                @error('form.nama_sub_kegiatan') <span style="color: #dc2626; font-size: 0.72rem; font-weight: 700;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="nom-label">Kinerja (Uraian Kinerja Sub Kegiatan)</label>
                            <textarea rows="2" wire:model="form.kinerja" placeholder="Uraian kinerja sub kegiatan sesuai lampiran Kepmendagri..." class="nom-textarea"></textarea>
                        </div>
                        <div class="nom-grid-1-3">
                            <div>
                                <label class="nom-label">Satuan Indikator *</label>
                                <select wire:model="form.satuan" class="nom-select">
                                    @foreach($daftarSatuan as $sat)
                                        <option value="{{ $sat }}">{{ $sat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="nom-label">Indikator Sub Kegiatan</label>
                                <input type="text" wire:model="form.indikator_sub_kegiatan" placeholder="Jumlah dokumen/laporan/kegiatan..." class="nom-input">
                            </div>
                        </div>
                        <div class="nom-grid-2">
                            <div>
                                <label class="nom-label">Status SPM</label>
                                <select wire:model="form.spm" class="nom-select">
                                    <option value="Non-SPM">Non-SPM</option>
                                    <option value="SPM">SPM (Standar Pelayanan Minimal)</option>
                                </select>
                            </div>
                            <div>
                                <label class="nom-label">Aktivitas / Layanan</label>
                                <select wire:model="form.aktivitas_layanan" class="nom-select">
                                    <option value="Layanan">Layanan</option>
                                    <option value="Aktifitas">Aktifitas</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="nom-label">Klasifikasi Belanja</label>
                            <input type="text" wire:model="form.klasifikasi_belanja" placeholder="Contoh: Belanja Operasi / Belanja Modal" class="nom-input">
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-nom btn-outline">Batal</button>
                            <button type="button" wire:click="saveSubKegiatan" class="btn-nom btn-green">💾 Simpan Sub Kegiatan</button>
                        </div>

                    @elseif($activeModal === 'import_csv')
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.85rem; font-size: 0.74rem; color: #334155;">
                            <strong>Mendukung 2 Format CSV:</strong>
                            <ul style="margin: 0.35rem 0 0 1.1rem; padding: 0;">
                                <li><strong>Format Nomenklatur RKPD / Permendagri (koma <code>,</code>):</strong> <code>No,Jenis Pemda,Kode,Urusan,Bidang Urusan,Program,Kegiatan,Sub Kegiatan,Kinerja,Indikator,Satuan,SPM,Aktivitas/Layanan</code></li>
                                <li><strong>Format Ekspor Sistem (titik koma <code>;</code>):</strong> hasil dari tombol <em>Unduh CSV</em>.</li>
                            </ul>
                        </div>
                        <div>
                            <label class="nom-label">Pilih File CSV *</label>
                            <input type="file" wire:model="csvFile" accept=".csv,.txt" class="nom-input">
                            @error('csvFile') <span style="color: #dc2626; font-size: 0.72rem; font-weight: 700;">{{ $message }}</span> @enderror
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
                            <button type="button" wire:click="closeModal" class="btn-nom btn-outline">Batal</button>
                            <button type="button" wire:click="importCsv" class="btn-nom btn-green">📤 Mulai Import CSV</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
