<x-filament-panels::page>
    {{-- Header Banner & Filter OPD / Triwulan --}}
    <div class="space-y-6">
        <div class="rounded-2xl p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #005000 0%, #166534 100%); border-bottom: 4px solid #FF6600;">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider" style="background: #FF6600;">
                            Permendagri 86/2017
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/15">
                            Tahun Anggaran {{ $tahun }} • Triwulan {{ $triwulan }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold {{ $isBuka ? 'bg-emerald-400 text-emerald-950' : 'bg-rose-500 text-white' }}">
                            {{ $isBuka ? '🟢 JADWAL PENGISIAN DIBUKA' : '🔴 JADWAL PENGISIAN TERKUNCI' }}
                        </span>
                    </div>
                    <h2 class="text-xl md:text-2xl font-black mt-2">
                        {{ $pdAktif?->nama_instansi ?? 'Pilih Perangkat Daerah' }}
                    </h2>
                    <p class="text-xs text-emerald-100 mt-1">
                        Kertas Kerja Pengendalian & Evaluasi Hasil Renja Perangkat Daerah (Program → Kegiatan → Sub-Kegiatan SIPD)
                    </p>
                </div>

                {{-- Pilih OPD (untuk Admin / Evaluator / Viewer) & Pilih Tahun --}}
                <div class="flex flex-wrap items-center gap-3">
                    @if(!$isOpd)
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-emerald-200 mb-1">Pilih Perangkat Daerah (37 OPD)</label>
                            <select wire:model.live="selectedPdId" class="text-xs font-bold text-slate-800 rounded-xl px-3.5 py-2 border-0 shadow">
                                @foreach($daftarOpd as $opd)
                                    <option value="{{ $opd->id }}">#{{ $opd->id }} — {{ $opd->nama_instansi }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-emerald-200 mb-1">Tahun</label>
                        <select wire:model.live="tahun" class="text-xs font-bold text-slate-800 rounded-xl px-3 py-2 border-0 shadow">
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Tab Selector Triwulan I, II, III, IV --}}
            <div class="mt-5 pt-4 border-t border-white/15 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap gap-2">
                    @foreach([1 => 'Triwulan I (Jan–Mar)', 2 => 'Triwulan II (Apr–Jun)', 3 => 'Triwulan III (Jul–Sep)', 4 => 'Triwulan IV (Okt–Des)'] as $twNum => $twLabel)
                        <button type="button" wire:click="pilihTriwulan({{ $twNum }})"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold transition shadow-sm"
                            style="{{ $triwulan === $twNum ? 'background: #FF6600; color: #fff;' : 'background: rgba(255,255,255,0.12); color: #e2e8f0;' }}">
                            {{ $twLabel }}
                        </button>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-2">
                    <button type="button" wire:click="jalankanAiAnalisisHambatan"
                        class="px-3.5 py-2 rounded-xl text-xs font-extrabold bg-amber-400 hover:bg-amber-300 text-slate-950 shadow transition">
                        ✨ AI Analisis Faktor Penghambat
                    </button>

                    @if(!$isViewer && ($isOpd || $isVerifikator))
                        <button type="button" wire:click="kirimSemuaKeBappeda"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold bg-white text-[#005000] hover:bg-emerald-50 shadow transition">
                            📤 Simpan & Kirim Laporan TW {{ $triwulan }} ke Bappeda
                        </button>
                    @endif

                    @if($isVerifikator)
                        <button type="button" wire:click="verifikasiSemuaBappeda('verified')"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold bg-emerald-400 text-emerald-950 hover:bg-emerald-300 shadow transition">
                            ✅ Verifikasi Semua (Bappeda)
                        </button>
                        <button type="button" wire:click="verifikasiSemuaBappeda('final')"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold bg-sky-400 text-sky-950 hover:bg-sky-300 shadow transition">
                            🏅 Tetapkan Final (Terbit BA)
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kartu Ringkasan Rumus Otomatis MonevService untuk OPD Ini --}}
        @if(!empty($rekapOpd))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                    <p class="text-[11px] font-bold uppercase text-slate-500">Rata-rata Capaian Kinerja (Fisik)</p>
                    <div class="flex items-baseline justify-between mt-1">
                        <span class="text-2xl font-black text-[#005000]">{{ number_format($rekapOpd['capaian_fisik'], 2, ',', '.') }}%</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold"
                            style="background: {{ $rekapOpd['predikat_fisik']['bg'] }}; color: {{ $rekapOpd['predikat_fisik']['color'] }};">
                            {{ $rekapOpd['predikat_fisik']['label'] }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">{{ $rekapOpd['total_sub_kegiatan'] }} Sub-Kegiatan Renja</p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                    <p class="text-[11px] font-bold uppercase text-slate-500">Realisasi Serapan Anggaran</p>
                    <div class="flex items-baseline justify-between mt-1">
                        <span class="text-2xl font-black text-[#FF6600]">{{ number_format($rekapOpd['serapan_keuangan'], 2, ',', '.') }}%</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold"
                            style="background: {{ $rekapOpd['predikat_keuangan']['bg'] }}; color: {{ $rekapOpd['predikat_keuangan']['color'] }};">
                            {{ $rekapOpd['predikat_keuangan']['label'] }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">
                        {{ \App\Services\MonevService::formatRupiah($rekapOpd['realisasi_anggaran'], true) }} / {{ \App\Services\MonevService::formatRupiah($rekapOpd['pagu_anggaran'], true) }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                    <p class="text-[11px] font-bold uppercase text-slate-500">Nilai & Predikat Permendagri 86/2017</p>
                    <div class="flex items-baseline justify-between mt-1">
                        <span class="text-2xl font-black text-slate-900">{{ number_format($rekapOpd['nilai_komposit'], 2, ',', '.') }}%</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold"
                            style="background: {{ $rekapOpd['predikat']['bg'] }}; color: {{ $rekapOpd['predikat']['color'] }};">
                            {{ $rekapOpd['predikat']['label'] }} ({{ $rekapOpd['predikat']['kode'] }})
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">Skala: {{ $rekapOpd['predikat']['rentang'] }}</p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                    <p class="text-[11px] font-bold uppercase text-slate-500">Status Verifikasi Bappeda</p>
                    <div class="flex items-baseline justify-between mt-1">
                        <span class="text-lg font-black text-blue-700">{{ $rekapOpd['status_laporan'] }}</span>
                        <span class="text-xs font-extrabold text-slate-600">{{ $rekapOpd['progress_verifikasi'] }}%</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Terverifikasi: {{ $rekapOpd['verified_count'] }}/{{ $rekapOpd['total_sub_kegiatan'] }} Sub-Kegiatan
                    </p>
                </div>
            </div>
        @endif

        {{-- Box Hasil ✨ AI Analisis Faktor Penghambat --}}
        @if($aiAnalisis)
            <div class="bg-amber-50/90 border-2 border-amber-300 rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">✨</span>
                        <h3 class="font-black text-slate-900 text-sm uppercase">
                            AI Analisis Faktor Penghambat & Rekomendasi Tindak Lanjut — {{ $aiAnalisis['instansi'] }} ({{ $aiAnalisis['periode'] }})
                        </h3>
                    </div>
                    <button type="button" wire:click="$set('aiAnalisis', null)" class="text-xs font-bold text-slate-500 hover:text-red-600">Tutup ✕</button>
                </div>
                <p class="text-xs text-slate-700 font-medium leading-relaxed">{{ $aiAnalisis['kesimpulan_ai'] }}</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                    @foreach($aiAnalisis['temuan_dan_solusi'] as $temuan)
                        <div class="bg-white rounded-xl p-3.5 border border-amber-200 text-xs space-y-1.5">
                            <div class="flex items-center justify-between font-extrabold text-slate-800">
                                <span>{{ $temuan['sub_kegiatan'] }}</span>
                                <span class="text-[11px] text-amber-700">Fisik {{ $temuan['fisik'] }}% | Uang {{ $temuan['keuangan'] }}%</span>
                            </div>
                            <p class="text-rose-700"><strong>Penghambat:</strong> {{ $temuan['hambatan'] }}</p>
                            <p class="text-emerald-800"><strong>Rekomendasi AI:</strong> {{ $temuan['solusi_ai'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Daftar Kertas Kerja Sub-Kegiatan Renja & Form Realisasi Triwulan --}}
        <div class="space-y-4">
            @forelse($renjas as $index => $renja)
                @php
                    $row = $inputs[$renja->id] ?? [];
                    $realFisik = (float) ($row['realisasi_kinerja'] ?? 0);
                    $realUang = (float) ($row['realisasi_anggaran'] ?? 0);
                    $persenFisik = \App\Services\MonevService::hitungPersenKinerja($realFisik, (float) $renja->target_kinerja_tahunan);
                    $persenUang = \App\Services\MonevService::hitungPersenAnggaran($realUang, (float) $renja->pagu_anggaran);
                    $predFisik = \App\Services\MonevService::getPredikatPermendagri($persenFisik);
                    $predUang = \App\Services\MonevService::getPredikatPermendagri($persenUang);
                    $statusItem = $row['status'] ?? 'draft';

                    $statusBadge = match($statusItem) {
                        'final' => ['label' => 'FINAL (BA TERBIT)', 'bg' => '#dbeafe', 'color' => '#1d4ed8'],
                        'verified' => ['label' => 'VERIFIED BAPPEDA', 'bg' => '#dcfce7', 'color' => '#15803d'],
                        'revisi' => ['label' => 'PERLU REVISI OPD', 'bg' => '#fee2e2', 'color' => '#b91c1c'],
                        'submitted' => ['label' => 'SUBMITTED KE BAPPEDA', 'bg' => '#fef3c7', 'color' => '#b45309'],
                        default => ['label' => 'DRAFT', 'bg' => '#f1f5f9', 'color' => '#475569'],
                    };

                    $disabledOpd = $isViewer || ($isOpd && (!$isBuka || in_array($statusItem, ['verified', 'final'])));
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    {{-- Header Cascading Sub-Kegiatan --}}
                    <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2.5 py-0.5 rounded bg-[#005000] text-white font-mono text-xs font-extrabold">
                                    {{ $renja->kode_sub_kegiatan }}
                                </span>
                                <span class="text-xs font-bold text-slate-500">
                                    Program: {{ $renja->nama_program }} → Kegiatan: {{ $renja->nama_kegiatan }}
                                </span>
                            </div>
                            <h3 class="text-sm md:text-base font-extrabold text-slate-900">
                                {{ $index + 1 }}. {{ $renja->nama_sub_kegiatan }}
                            </h3>
                            <p class="text-xs text-slate-600">
                                <strong>Indikator:</strong> {{ $renja->indikator_sub_kegiatan }}
                                • <strong>Target Tahunan:</strong> {{ number_format($renja->target_kinerja_tahunan, 0, ',', '.') }} {{ $renja->satuan }}
                                • <strong>Pagu Anggaran:</strong> {{ \App\Services\MonevService::formatRupiah($renja->pagu_anggaran) }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold"
                                style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }};">
                                {{ $statusBadge['label'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Form Input Realisasi Fisik, Keuangan, Faktor Pendorong/Penghambat/TL & Verifikasi Bappeda --}}
                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            {{-- Realisasi Kinerja Fisik --}}
                            <div class="p-3.5 rounded-xl bg-emerald-50/50 border border-emerald-200">
                                <label class="block text-xs font-extrabold text-emerald-950 mb-1">
                                    Realisasi Kinerja Fisik ({{ $renja->satuan }})
                                </label>
                                <input type="number" step="0.01"
                                    wire:model.blur="inputs.{{ $renja->id }}.realisasi_kinerja"
                                    @disabled($disabledOpd)
                                    class="w-full rounded-lg border-slate-300 text-sm font-bold text-slate-900 px-3 py-1.5">
                                <div class="flex items-center justify-between mt-2 text-xs">
                                    <span class="font-bold text-slate-600">% Capaian: <strong class="text-[#005000]">{{ $persenFisik }}%</strong></span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold"
                                        style="background: {{ $predFisik['bg'] }}; color: {{ $predFisik['color'] }};">
                                        {{ $predFisik['label'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Realisasi Anggaran (Rp) --}}
                            <div class="p-3.5 rounded-xl bg-orange-50/50 border border-orange-200">
                                <label class="block text-xs font-extrabold text-orange-950 mb-1">
                                    Realisasi Anggaran TW {{ $triwulan }} (Rp)
                                </label>
                                <input type="number" step="1000"
                                    wire:model.blur="inputs.{{ $renja->id }}.realisasi_anggaran"
                                    @disabled($disabledOpd)
                                    class="w-full rounded-lg border-slate-300 text-sm font-bold text-slate-900 px-3 py-1.5">
                                <div class="flex items-center justify-between mt-2 text-xs">
                                    <span class="font-bold text-slate-600">% Serapan: <strong class="text-[#FF6600]">{{ $persenUang }}%</strong></span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold"
                                        style="background: {{ $predUang['bg'] }}; color: {{ $predUang['color'] }};">
                                        {{ $predUang['label'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Link Bukti Dukung --}}
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 md:col-span-2">
                                <label class="block text-xs font-extrabold text-slate-800 mb-1">
                                    Link Bukti Dukung (Google Drive / SIPD / Dokumen Output)
                                </label>
                                <div class="flex gap-2">
                                    <input type="url"
                                        wire:model.blur="inputs.{{ $renja->id }}.link_bukti_dukung"
                                        @disabled($disabledOpd)
                                        placeholder="https://drive.badungkab.go.id/..."
                                        class="w-full rounded-lg border-slate-300 text-xs text-slate-800 px-3 py-1.5">
                                    @if(!empty($row['link_bukti_dukung']))
                                        <a href="{{ $row['link_bukti_dukung'] }}" target="_blank"
                                            class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-bold shrink-0 hover:bg-blue-700">
                                            Buka Link ↗
                                        </a>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1.5">
                                    Sisa Pagu: <strong>{{ \App\Services\MonevService::formatRupiah(max(0, $renja->pagu_anggaran - $realUang)) }}</strong>
                                </p>
                            </div>
                        </div>

                        {{-- Faktor Pendorong, Faktor Penghambat & Tindak Lanjut --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Faktor Pendorong</label>
                                <textarea rows="2"
                                    wire:model.blur="inputs.{{ $renja->id }}.faktor_pendorong"
                                    @disabled($disabledOpd)
                                    placeholder="Faktor pendukung keberhasilan..."
                                    class="w-full rounded-xl border-slate-300 text-xs text-slate-800 p-2.5"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Faktor Penghambat / Kendala</label>
                                <textarea rows="2"
                                    wire:model.blur="inputs.{{ $renja->id }}.faktor_penghambat"
                                    @disabled($disabledOpd)
                                    placeholder="Kendala teknis / administrasi di Triwulan ini..."
                                    class="w-full rounded-xl border-slate-300 text-xs text-slate-800 p-2.5"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Rencana Tindak Lanjut Triwulan Berikutnya</label>
                                <textarea rows="2"
                                    wire:model.blur="inputs.{{ $renja->id }}.tindak_lanjut"
                                    @disabled($disabledOpd)
                                    placeholder="Langkah percepatan yang akan ditempuh..."
                                    class="w-full rounded-xl border-slate-300 text-xs text-slate-800 p-2.5"></textarea>
                            </div>
                        </div>

                        {{-- Panel Verifikator Bappeda & Tombol Simpan --}}
                        <div class="pt-3 border-t border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="flex-1">
                                <label class="block text-[11px] font-extrabold uppercase text-orange-800 mb-1">
                                    Catatan Evaluasi / Revisi dari Verifikator Bappeda:
                                </label>
                                <input type="text"
                                    wire:model.blur="inputs.{{ $renja->id }}.catatan_verifikator"
                                    @disabled(!$isVerifikator)
                                    placeholder="Diisi oleh Evaluator Bidang Perencanaan/Monev Bappeda..."
                                    class="w-full rounded-xl border-orange-200 bg-orange-50/40 text-xs text-slate-800 px-3 py-1.5">
                            </div>

                            <div class="flex flex-wrap items-center gap-2 shrink-0">
                                @if(!$disabledOpd)
                                    <button type="button"
                                        wire:click="simpanSubKegiatan({{ $renja->id }}, 'draft')"
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold transition">
                                        💾 Simpan Draft
                                    </button>
                                    <button type="button"
                                        wire:click="simpanSubKegiatan({{ $renja->id }}, 'submitted')"
                                        class="px-3.5 py-1.5 rounded-xl bg-[#005000] hover:bg-emerald-800 text-white text-xs font-bold transition">
                                        📤 Submit ke Bappeda
                                    </button>
                                @endif

                                @if($isVerifikator)
                                    <button type="button"
                                        wire:click="simpanSubKegiatan({{ $renja->id }}, 'verified')"
                                        class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold transition">
                                        ✅ Set Verified
                                    </button>
                                    <button type="button"
                                        wire:click="simpanSubKegiatan({{ $renja->id }}, 'revisi')"
                                        class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold transition">
                                        ↩ Kembalikan Revisi
                                    </button>
                                    <button type="button"
                                        wire:click="simpanSubKegiatan({{ $renja->id }}, 'final')"
                                        class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold transition">
                                        🏅 Final
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 text-center border border-slate-200">
                    <p class="text-sm font-bold text-slate-600">Belum ada data Sub-Kegiatan Renja untuk Perangkat Daerah ini pada Tahun {{ $tahun }}.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
