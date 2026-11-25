<x-filament-panels::page>
    @php
        $stats = $this->getStatistikKabupaten();
        $counts = $this->getPredikatCounts();
        $filteredRows = $this->getFilteredRekapData();
        $user = auth()->user();
    @endphp

    <div class="space-y-6">
        {{-- HERO HEADER EKSEKUTIF KABUPATEN BADUNG --}}
        <div class="rounded-2xl p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #005000 0%, #14532d 100%); border-bottom: 4px solid #FF6600;">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider" style="background: #FF6600;">
                            Dashboard Eksekutif Kabupaten Badung
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/15">
                            Permendagri Nomor 86 Tahun 2017
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-400/20 text-emerald-200 border border-emerald-400/30">
                            Role Aktif: {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                    <h2 class="text-xl md:text-2xl font-black mt-2">
                        Monitoring & Evaluasi Capaian Kinerja Fisik dan Serapan Anggaran (RKPD & Renja 37 OPD)
                    </h2>
                    <p class="text-xs text-emerald-100 mt-1">
                        Data teragregasi real-time dari {{ $stats['total_opd'] }} Perangkat Daerah • {{ $stats['total_sub_kegiatan'] }} Sub-Kegiatan SIPD • {{ $stats['total_rpjmd'] }} IKU RPJMD • {{ $stats['total_rkpd'] }} Prioritas RKPD
                    </p>
                </div>

                {{-- Filter Tahun & Tombol Sinkronisasi + Export --}}
                <div class="flex flex-wrap items-center gap-2.5">
                    <select wire:model.live="tahunTerkini" class="text-xs font-extrabold text-slate-900 rounded-xl px-3.5 py-2 border-0 shadow">
                        <option value="2025">TA 2025</option>
                        <option value="2026">TA 2026</option>
                        <option value="2027">TA 2027</option>
                    </select>

                    <button type="button" wire:click="sinkronkanNilai"
                        class="px-3.5 py-2 rounded-xl text-xs font-extrabold bg-white/15 hover:bg-white/25 text-white border border-white/25 transition">
                        🔄 Sinkronkan Cache
                    </button>

                    <button type="button" wire:click="exportCsv"
                        class="px-3.5 py-2 rounded-xl text-xs font-extrabold bg-white text-[#005000] hover:bg-emerald-50 shadow transition">
                        📥 Export Ranking CSV
                    </button>
                </div>
            </div>

            {{-- Filter Triwulan I-IV & Tombol AI --}}
            <div class="mt-5 pt-4 border-t border-white/15 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap gap-2">
                    @foreach([1 => 'Triwulan I', 2 => 'Triwulan II', 3 => 'Triwulan III', 4 => 'Triwulan IV'] as $twNum => $twLbl)
                        <button type="button" wire:click="pilihTriwulan({{ $twNum }})"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold transition"
                            style="{{ $triwulanTerkini === $twNum ? 'background: #FF6600; color: #ffffff;' : 'background: rgba(255,255,255,0.12); color: #e2e8f0;' }}">
                            {{ $twLbl }}
                        </button>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-2.5">
                    <button type="button" wire:click="generateAiExecutiveSummary"
                        class="px-4 py-2 rounded-xl text-xs font-extrabold bg-amber-400 hover:bg-amber-300 text-slate-950 shadow-md transition">
                        ✨ AI Executive Summary
                    </button>
                    <button type="button" wire:click="generateAiAnalisisPenghambat"
                        class="px-4 py-2 rounded-xl text-xs font-extrabold bg-orange-500 hover:bg-orange-400 text-white shadow-md transition">
                        ✨ AI Analisis Faktor Penghambat
                    </button>
                </div>
            </div>
        </div>

        {{-- PANEL ✨ AI EXECUTIVE SUMMARY --}}
        @if($aiSummary)
            <div class="bg-gradient-to-br from-emerald-50 via-white to-amber-50 border-2 border-emerald-600 rounded-2xl p-6 shadow-md space-y-4">
                <div class="flex items-center justify-between border-b border-emerald-200 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="px-2.5 py-1 rounded-lg bg-[#005000] text-white text-xs font-black">✨ AI BAPPEDA</span>
                        <h3 class="font-black text-slate-900 text-base">{{ $aiSummary['judul'] }}</h3>
                    </div>
                    <button type="button" wire:click="$set('aiSummary', null)" class="text-xs font-bold text-slate-500 hover:text-red-600">
                        Tutup ✕
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-xs">
                    <div class="lg:col-span-2 space-y-2.5">
                        <p class="text-slate-800 leading-relaxed font-medium">{{ $aiSummary['ringkasan_utama'] }}</p>
                        <p class="text-slate-700 leading-relaxed bg-white p-3.5 rounded-xl border border-slate-200">
                            <strong>Analisis Korelasi Fisik vs Keuangan:</strong> {{ $aiSummary['analisis_deviasi'] }}
                        </p>
                        <div class="bg-white p-3.5 rounded-xl border border-slate-200">
                            <p class="font-extrabold text-[#005000] uppercase mb-1.5">Rekomendasi Strategis Pimpinan:</p>
                            <ul class="list-disc pl-4 space-y-1 text-slate-700">
                                @foreach($aiSummary['rekomendasi_pimpinan'] as $rek)
                                    <li>{{ $rek }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <div class="bg-emerald-100/70 border border-emerald-300 rounded-xl p-3.5">
                            <p class="font-extrabold text-emerald-950 uppercase text-[11px]">🏆 Top 3 OPD Berkinerja Terbaik</p>
                            <p class="text-emerald-900 mt-1 font-semibold">{{ $aiSummary['top_performer'] }}</p>
                        </div>
                        <div class="bg-rose-100/70 border border-rose-300 rounded-xl p-3.5">
                            <p class="font-extrabold text-rose-950 uppercase text-[11px]">⚠️ OPD Serapan Rendah / Perlu Percepatan</p>
                            <p class="text-rose-900 mt-1 font-semibold">{{ $aiSummary['perlu_atensi'] }}</p>
                        </div>
                        <div class="bg-amber-100/60 border border-amber-300 rounded-xl p-3.5">
                            <p class="font-extrabold text-amber-950 uppercase text-[11px]">Faktor Penghambat Dominan TW {{ $triwulanTerkini }}</p>
                            <ul class="list-disc pl-4 mt-1 space-y-1 text-amber-950">
                                @foreach($aiSummary['faktor_penghambat_dominan'] as $fh)
                                    <li>{{ $fh }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- PANEL ✨ AI ANALISIS FAKTOR PENGHAMBAT --}}
        @if($aiHambatan)
            <div class="bg-amber-50 border-2 border-amber-400 rounded-2xl p-5 shadow-md space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-black text-slate-900 text-sm uppercase">
                        ✨ AI Analisis Faktor Penghambat & Tindak Lanjut — {{ $aiHambatan['instansi'] }} ({{ $aiHambatan['periode'] }})
                    </h3>
                    <button type="button" wire:click="$set('aiHambatan', null)" class="text-xs font-bold text-slate-500 hover:text-red-600">
                        Tutup ✕
                    </button>
                </div>
                <p class="text-xs font-semibold text-slate-800">{{ $aiHambatan['kesimpulan_ai'] }}</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($aiHambatan['temuan_dan_solusi'] as $t)
                        <div class="bg-white rounded-xl p-3.5 border border-amber-200 text-xs space-y-1">
                            <p class="font-extrabold text-slate-900">{{ $t['sub_kegiatan'] }}</p>
                            <p class="text-[11px] font-bold text-amber-700">Capaian Fisik: {{ $t['fisik'] }}% • Serapan: {{ $t['keuangan'] }}%</p>
                            <p class="text-rose-700"><strong>Hambatan:</strong> {{ $t['hambatan'] }}</p>
                            <p class="text-emerald-800"><strong>Solusi AI:</strong> {{ $t['solusi_ai'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 4 KARTU KPI MAKRO KABUPATEN BADUNG --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase text-slate-500">Rata-rata Capaian Fisik Kab</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold"
                        style="background: {{ $stats['predikat_fisik']['bg'] }}; color: {{ $stats['predikat_fisik']['color'] }};">
                        {{ $stats['predikat_fisik']['label'] }}
                    </span>
                </div>
                <p class="text-3xl font-black text-[#005000] mt-2">{{ number_format($stats['rata_fisik'], 2, ',', '.') }}%</p>
                <p class="text-xs text-slate-500 mt-1">Dari {{ $stats['total_sub_kegiatan'] }} Sub-Kegiatan di 37 OPD</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase text-slate-500">Serapan Anggaran Kabupaten</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold"
                        style="background: {{ $stats['predikat_keuangan']['bg'] }}; color: {{ $stats['predikat_keuangan']['color'] }};">
                        {{ $stats['predikat_keuangan']['label'] }}
                    </span>
                </div>
                <p class="text-3xl font-black text-[#FF6600] mt-2">{{ number_format($stats['serapan_keuangan'], 2, ',', '.') }}%</p>
                <p class="text-xs text-slate-500 mt-1">
                    {{ \App\Services\MonevService::formatRupiah($stats['total_realisasi_rp'], true) }} dari Pagu {{ \App\Services\MonevService::formatRupiah($stats['total_pagu_rp'], true) }}
                </p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase text-slate-500">Predikat Permendagri 86/2017</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold"
                        style="background: {{ $stats['predikat_kabupaten']['bg'] }}; color: {{ $stats['predikat_kabupaten']['color'] }};">
                        {{ $stats['predikat_kabupaten']['kode'] }}
                    </span>
                </div>
                <p class="text-2xl font-black text-slate-900 mt-2">{{ $stats['predikat_kabupaten']['label'] }}</p>
                <p class="text-xs text-slate-500 mt-1">Nilai Komposit: {{ number_format($stats['nilai_komposit'], 2, ',', '.') }}% ({{ $stats['predikat_kabupaten']['rentang'] }})</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase text-slate-500">Kepatuhan & Verifikasi OPD</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-100 text-blue-800">
                        TW {{ $triwulanTerkini }}
                    </span>
                </div>
                <p class="text-3xl font-black text-blue-700 mt-2">{{ $stats['opd_sudah_input'] }} / {{ $stats['total_opd'] }} <small class="text-sm">OPD</small></p>
                <p class="text-xs text-slate-500 mt-1">
                    Terverifikasi Bappeda: {{ $stats['total_terverifikasi'] }} Sub-Kegiatan • Serapan ≤65%: {{ $stats['opd_serapan_rendah'] }} OPD
                </p>
            </div>
        </div>

        {{-- GRAFIK VISUAL PERBANDINGAN TARGET VS REALISASI FISIK & KEUANGAN PER OPD + DISTRIBUSI PREDIKAT --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Grafik Bar Horizontal Top & Bottom Perbandingan Fisik vs Keuangan --}}
            <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-black uppercase text-slate-900">
                            Grafik Perbandingan Realisasi Fisik (%) vs Serapan Keuangan (%) Perangkat Daerah
                        </h3>
                        <p class="text-xs text-slate-500">Menampilkan perbandingan langsung capaian kinerja fisik (Hijau) dan serapan anggaran (Oranye)</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold">
                        <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#005000] inline-block"></span> Fisik (%)</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#FF6600] inline-block"></span> Keuangan (%)</span>
                    </div>
                </div>

                <div class="space-y-3 max-h-[340px] overflow-y-auto pr-2">
                    @foreach(array_slice($this->rekapData, 0, 12) as $barRow)
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800 truncate max-w-md">{{ $barRow['nama'] }}</span>
                                <span class="font-mono text-[11px] font-bold text-slate-600">
                                    Fisik: <strong class="text-[#005000]">{{ $barRow['capaian_fisik'] }}%</strong> |
                                    Keuangan: <strong class="text-[#FF6600]">{{ $barRow['serapan_keuangan'] }}%</strong>
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden flex flex-col gap-0.5">
                                <div class="h-2.5 rounded-full" style="width: {{ min(100, $barRow['capaian_fisik']) }}%; background: #005000;"></div>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full" style="width: {{ min(100, $barRow['serapan_keuangan']) }}%; background: #FF6600;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Distribusi Predikat Permendagri 86/2017 --}}
            <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-black uppercase text-slate-900">
                        Distribusi Predikat 37 OPD (Permendagri 86/2017)
                    </h3>
                    <p class="text-xs text-slate-500 mb-4">Klik kategori predikat untuk memfilter tabel peringkat</p>

                    @php
                        $predikatMeta = [
                            'Sangat Tinggi' => ['rentang' => '> 90%', 'color' => '#16a34a', 'bg' => '#dcfce7'],
                            'Tinggi' => ['rentang' => '75,01% - 90%', 'color' => '#0284c7', 'bg' => '#e0f2fe'],
                            'Sedang' => ['rentang' => '65,01% - 75%', 'color' => '#ca8a04', 'bg' => '#fef9c3'],
                            'Rendah' => ['rentang' => '50,01% - 65%', 'color' => '#ea580c', 'bg' => '#ffedd5'],
                            'Sangat Rendah' => ['rentang' => '<= 50%', 'color' => '#dc2626', 'bg' => '#fee2e2'],
                        ];
                    @endphp

                    <div class="space-y-2.5">
                        @foreach($predikatMeta as $lbl => $meta)
                            @php
                                $jml = $counts[$lbl] ?? 0;
                                $pct = $stats['total_opd'] > 0 ? round(($jml / $stats['total_opd']) * 100, 1) : 0;
                            @endphp
                            <button type="button" wire:click="$set('filterPredikat', '{{ $filterPredikat === $lbl ? 'semua' : $lbl }}')"
                                class="w-full text-left p-3 rounded-xl border transition flex items-center justify-between {{ $filterPredikat === $lbl ? 'ring-2 ring-[#005000] border-[#005000]' : 'border-slate-200 hover:bg-slate-50' }}">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[11px] font-extrabold"
                                            style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }};">
                                            {{ $lbl }}
                                        </span>
                                        <span class="text-[11px] text-slate-500 font-medium">({{ $meta['rentang'] }})</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-black text-slate-900">{{ $jml }} OPD</span>
                                    <span class="text-[11px] text-slate-400 block">{{ $pct }}%</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                @if($filterPredikat !== 'semua')
                    <button type="button" wire:click="$set('filterPredikat', 'semua')"
                        class="mt-3 w-full py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-extrabold text-slate-700">
                        Tampilkan Semua Predikat ({{ $counts['semua'] }} OPD)
                    </button>
                @endif
            </div>
        </div>

        {{-- TABEL PERINGKAT 37 PERANGKAT DAERAH KABUPATEN BADUNG --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-black text-slate-900 uppercase">
                        Tabel Peringkat Capaian Kinerja & Serapan Anggaran 37 Perangkat Daerah — TW {{ $triwulanTerkini }} TA {{ $tahunTerkini }}
                    </h3>
                    <p class="text-xs text-slate-500">
                        Klik tombol "Kertas Kerja" atau "Cetak Evaluasi" pada masing-masing baris untuk memeriksa detail Sub-Kegiatan OPD
                    </p>
                </div>
                <div class="w-full sm:w-72">
                    <input type="text" wire:model.live.debounce.250ms="search"
                        placeholder="Cari nama Perangkat Daerah / singkatan..."
                        class="w-full rounded-xl border-slate-300 text-xs px-3.5 py-2">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="text-white uppercase text-[11px]" style="background: linear-gradient(135deg, #005000 0%, #166534 100%); border-bottom: 3px solid #FF6600;">
                            <th class="py-3.5 px-4 font-extrabold">Rank</th>
                            <th class="py-3.5 px-4 font-extrabold">Perangkat Daerah (OPD)</th>
                            <th class="py-3.5 px-3 font-extrabold text-center">Sub-Keg</th>
                            <th class="py-3.5 px-4 font-extrabold text-right">Pagu Anggaran</th>
                            <th class="py-3.5 px-4 font-extrabold text-right">Realisasi (Rp)</th>
                            <th class="py-3.5 px-3 font-extrabold text-center">% Fisik</th>
                            <th class="py-3.5 px-3 font-extrabold text-center">% Keuangan</th>
                            <th class="py-3.5 px-3 font-extrabold text-center">Predikat (86/2017)</th>
                            <th class="py-3.5 px-3 font-extrabold text-center">Status Bappeda</th>
                            <th class="py-3.5 px-4 font-extrabold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($filteredRows as $row)
                            <tr class="hover:bg-emerald-50/40 transition">
                                <td class="py-3 px-4 font-black text-slate-800">
                                    @if($row['rank'] === 1)
                                        <span class="px-2.5 py-1 rounded-full bg-amber-400 text-slate-950 font-black">🥇 #1</span>
                                    @elseif($row['rank'] === 2)
                                        <span class="px-2.5 py-1 rounded-full bg-slate-300 text-slate-900 font-black">🥈 #2</span>
                                    @elseif($row['rank'] === 3)
                                        <span class="px-2.5 py-1 rounded-full bg-amber-700 text-white font-black">🥉 #3</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold">#{{ $row['rank'] }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-extrabold text-slate-900">{{ $row['nama'] }}</div>
                                    <div class="text-[11px] text-slate-500">
                                        Kode: <span class="font-mono">{{ $row['kode_opd'] }}</span> • {{ $row['singkatan'] }}
                                        • {{ $row['total_program'] }} Program / {{ $row['total_kegiatan'] }} Kegiatan
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-center font-bold text-slate-700">
                                    {{ $row['total_sub_kegiatan'] }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono text-slate-700">
                                    {{ \App\Services\MonevService::formatRupiah($row['pagu_anggaran'], true) }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                                    {{ \App\Services\MonevService::formatRupiah($row['realisasi_anggaran'], true) }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="font-extrabold text-[#005000]">{{ number_format($row['capaian_fisik'], 2, ',', '.') }}%</span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="font-extrabold text-[#FF6600]">{{ number_format($row['serapan_keuangan'], 2, ',', '.') }}%</span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold"
                                        style="background: {{ $row['predikat']['bg'] }}; color: {{ $row['predikat']['color'] }};">
                                        {{ $row['predikat']['label'] }} ({{ number_format($row['nilai_komposit'], 1, ',', '.') }}%)
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700">
                                        {{ $row['status_laporan'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <a href="{{ url('/admin/lembar-monev-triwulan?pd_id=' . $row['id']) }}"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#005000] hover:bg-emerald-800 text-white text-[11px] font-bold transition">
                                        Kertas Kerja
                                    </a>
                                    <a href="{{ route('cetak.evaluasi.pdf', ['perangkat_daerah_id' => $row['id'], 'tahun' => $tahunTerkini, 'triwulan' => $triwulanTerkini]) }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#FF6600] hover:bg-orange-600 text-white text-[11px] font-bold transition ml-1">
                                        PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
