<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Toolbar Kontrol Cetakan & Pilihan OPD / Triwulan --}}
        <div class="rounded-2xl p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #005000 0%, #166534 100%); border-bottom: 4px solid #FF6600;">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider" style="background: #FF6600;">
                            Format Lampiran Permendagri 86/2017
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/15">
                            Status BA: {{ strtoupper($statusBa) }}
                        </span>
                    </div>
                    <h2 class="text-xl font-black mt-2">
                        Editor Kertas A4 & Cetakan Resmi Evaluasi Hasil Renja — {{ $pdAktif?->nama_instansi }}
                    </h2>
                    <p class="text-xs text-emerald-100 mt-0.5">
                        Sesuaikan Nomor Berita Acara, Catatan Evaluasi & Rekomendasi Bappeda lalu unduh dalam format PDF Resmi atau Word (.docx)
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    @if(!$isOpd)
                        <select wire:model.live="selectedPdId" class="text-xs font-bold text-slate-900 rounded-xl px-3.5 py-2 border-0 shadow">
                            @foreach($daftarOpd as $opd)
                                <option value="{{ $opd->id }}">#{{ $opd->id }} — {{ $opd->nama_instansi }}</option>
                            @endforeach
                        </select>
                    @endif

                    <select wire:model.live="tahun" class="text-xs font-bold text-slate-900 rounded-xl px-3 py-2 border-0 shadow">
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-white/15 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap gap-2">
                    @foreach([1 => 'Triwulan I', 2 => 'Triwulan II', 3 => 'Triwulan III', 4 => 'Triwulan IV'] as $twNum => $twLbl)
                        <button type="button" wire:click="pilihTriwulan({{ $twNum }})"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition"
                            style="{{ $triwulan === $twNum ? 'background: #FF6600; color: #fff;' : 'background: rgba(255,255,255,0.12); color: #e2e8f0;' }}">
                            {{ $twLbl }}
                        </button>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-2.5">
                    @if($isVerifikator)
                        <button type="button" wire:click="simpanBeritaAcara('terbit')"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold bg-emerald-400 hover:bg-emerald-300 text-emerald-950 shadow transition">
                            💾 Simpan & Terbitkan Berita Acara
                        </button>
                    @endif

                    @if($pdAktif)
                        <a href="{{ route('cetak.evaluasi.pdf', ['perangkat_daerah_id' => $pdAktif->id, 'tahun' => $tahun, 'triwulan' => $triwulan]) }}"
                            target="_blank"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold bg-white text-[#005000] hover:bg-emerald-50 shadow transition">
                            🖨️ Cetak PDF Resmi (A4)
                        </a>
                        <button type="button" wire:click="unduhWordDocx"
                            class="px-4 py-2 rounded-xl text-xs font-extrabold bg-blue-600 hover:bg-blue-500 text-white shadow transition">
                            📄 Unduh Word (.docx)
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form Editor Berita Acara Bappeda --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-black uppercase text-slate-900">1. Pengaturan Naskah Berita Acara & Rekomendasi Bappeda</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Berita Acara Evaluasi</label>
                    <input type="text" wire:model="nomorBa" @disabled(!$isVerifikator)
                        class="w-full rounded-xl border-slate-300 text-xs font-mono font-bold px-3.5 py-2">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Berita Acara</label>
                    <input type="date" wire:model="tanggalBa" @disabled(!$isVerifikator)
                        class="w-full rounded-xl border-slate-300 text-xs px-3.5 py-2">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Evaluasi Keselarasan & Capaian</label>
                    <textarea rows="3" wire:model="catatanEvaluasi" @disabled(!$isVerifikator)
                        class="w-full rounded-xl border-slate-300 text-xs p-3"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Rekomendasi Tindak Lanjut Bappeda</label>
                    <textarea rows="3" wire:model="rekomendasiBappeda" @disabled(!$isVerifikator)
                        class="w-full rounded-xl border-slate-300 text-xs p-3"></textarea>
                </div>
            </div>
        </div>

        {{-- Preview Kertas A4 Resmi (Format Lampiran Permendagri Nomor 86 Tahun 2017) --}}
        <div class="bg-slate-200/80 p-4 md:p-8 rounded-2xl border border-slate-300">
            <div class="bg-white mx-auto shadow-2xl p-8 md:p-10 text-slate-900 max-w-6xl border border-slate-300" style="font-family: 'Times New Roman', Times, serif;">
                {{-- Kop Surat Resmi Pemkab Badung --}}
                <div class="flex items-center justify-center gap-6 border-b-4 border-double border-black pb-4 mb-6">
                    <img src="{{ asset('images/logo-badung.png') }}" alt="Logo Badung" class="h-20 w-auto">
                    <div class="text-center">
                        <p class="text-base font-bold uppercase tracking-wide">PEMERINTAH KABUPATEN BADUNG</p>
                        <p class="text-xl font-black uppercase tracking-wide">BADAN PERENCANAAN PEMBANGUNAN DAERAH</p>
                        <p class="text-xs">Pusat Pemerintahan Mangupraja Mandala, Jl. Raya Sempidi, Mengwi, Badung - Bali</p>
                        <p class="text-xs font-bold mt-0.5">BERITA ACARA & LAPORAN EVALUASI HASIL RENJA PERANGKAT DAERAH (PERMENDAGRI 86/2017)</p>
                    </div>
                </div>

                {{-- Identitas Dokumen Evaluasi --}}
                <div class="text-center mb-5">
                    <p class="font-bold text-sm uppercase underline">
                        EVALUASI TERHADAP HASIL RENJA PERANGKAT DAERAH LINGKUP KABUPATEN BADUNG
                    </p>
                    <p class="text-xs font-bold mt-1">
                        PERANGKAT DAERAH: {{ strtoupper($pdAktif?->nama_instansi ?? '-') }} • TAHUN ANGGARAN {{ $tahun }} • TRIWULAN {{ $triwulan }}
                    </p>
                    <p class="text-xs mt-0.5">Nomor Berita Acara: <strong>{{ $nomorBa }}</strong></p>
                </div>

                {{-- Tabel Format Lampiran Permendagri 86/2017 --}}
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-black text-[11px]">
                        <thead>
                            <tr class="bg-slate-100 text-center font-bold">
                                <th class="border border-black p-1.5">No</th>
                                <th class="border border-black p-1.5">Kode</th>
                                <th class="border border-black p-1.5">Urusan / Program / Kegiatan / Sub-Kegiatan</th>
                                <th class="border border-black p-1.5">Indikator Kinerja</th>
                                <th class="border border-black p-1.5">Target Tahunan (Fisik & Rp)</th>
                                <th class="border border-black p-1.5">Realisasi TW {{ $triwulan }} (Fisik & Rp)</th>
                                <th class="border border-black p-1.5">Tingkat Capaian (%)</th>
                                <th class="border border-black p-1.5">Predikat (86/2017)</th>
                                <th class="border border-black p-1.5">Faktor Penghambat & Tindak Lanjut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($renjas as $idx => $r)
                                @php
                                    $rel = $realisasis->get($r->id);
                                    $pFisik = (float) ($rel?->persen_capaian_kinerja ?? 0);
                                    $pUang = (float) ($rel?->persen_serapan_anggaran ?? 0);
                                    $pred = \App\Services\MonevService::getPredikatPermendagri(($pFisik + $pUang) / 2);
                                @endphp
                                <tr>
                                    <td class="border border-black p-1.5 text-center">{{ $idx + 1 }}</td>
                                    <td class="border border-black p-1.5 font-mono">{{ $r->kode_sub_kegiatan }}</td>
                                    <td class="border border-black p-1.5">
                                        <div class="text-[10px] text-slate-600">{{ $r->nama_program }}</div>
                                        <div class="font-bold">{{ $r->nama_sub_kegiatan }}</div>
                                    </td>
                                    <td class="border border-black p-1.5">{{ $r->indikator_sub_kegiatan }}</td>
                                    <td class="border border-black p-1.5 text-right">
                                        <div>{{ number_format($r->target_kinerja_tahunan, 0, ',', '.') }} {{ $r->satuan }}</div>
                                        <div class="font-bold">{{ \App\Services\MonevService::formatRupiah($r->pagu_anggaran) }}</div>
                                    </td>
                                    <td class="border border-black p-1.5 text-right">
                                        <div>{{ number_format($rel?->realisasi_kinerja ?? 0, 2, ',', '.') }} {{ $r->satuan }}</div>
                                        <div class="font-bold">{{ \App\Services\MonevService::formatRupiah($rel?->realisasi_anggaran ?? 0) }}</div>
                                    </td>
                                    <td class="border border-black p-1.5 text-center font-bold">
                                        <div>Fisik: {{ number_format($pFisik, 2, ',', '.') }}%</div>
                                        <div>Keu: {{ number_format($pUang, 2, ',', '.') }}%</div>
                                    </td>
                                    <td class="border border-black p-1.5 text-center font-bold">
                                        {{ $pred['label'] }}
                                    </td>
                                    <td class="border border-black p-1.5 text-[10px]">
                                        <div><strong>Hambatan:</strong> {{ $rel?->faktor_penghambat ?? '-' }}</div>
                                        <div><strong>TL:</strong> {{ $rel?->tindak_lanjut ?? '-' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        @if(!empty($rekap))
                            <tfoot>
                                <tr class="bg-slate-100 font-bold">
                                    <td colspan="4" class="border border-black p-2 text-right">
                                        RATA-RATA CAPAIAN KINERJA & TOTAL REALISASI ANGGARAN TRIWULAN {{ $triwulan }}:
                                    </td>
                                    <td class="border border-black p-2 text-right">
                                        {{ \App\Services\MonevService::formatRupiah($rekap['pagu_anggaran']) }}
                                    </td>
                                    <td class="border border-black p-2 text-right">
                                        {{ \App\Services\MonevService::formatRupiah($rekap['realisasi_anggaran']) }}
                                    </td>
                                    <td class="border border-black p-2 text-center">
                                        Fisik: {{ number_format($rekap['capaian_fisik'], 2, ',', '.') }}%<br>
                                        Keu: {{ number_format($rekap['serapan_keuangan'], 2, ',', '.') }}%
                                    </td>
                                    <td colspan="2" class="border border-black p-2 text-center">
                                        PREDIKAT: {{ strtoupper($rekap['predikat']['label']) }} ({{ number_format($rekap['nilai_komposit'], 2, ',', '.') }}%)
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                {{-- Catatan & Rekomendasi Bappeda --}}
                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="border border-black p-3">
                        <p class="font-bold underline uppercase mb-1">Faktor Pendorong / Catatan Evaluasi Bappeda:</p>
                        <p class="whitespace-pre-line">{{ $catatanEvaluasi }}</p>
                    </div>
                    <div class="border border-black p-3">
                        <p class="font-bold underline uppercase mb-1">Rekomendasi Tindak Lanjut Triwulan Berikutnya:</p>
                        <p class="whitespace-pre-line">{{ $rekomendasiBappeda }}</p>
                    </div>
                </div>

                {{-- Blok Tanda Tangan Resmi --}}
                <div class="mt-8 grid grid-cols-2 text-center text-xs">
                    <div>
                        <p>Mengetahui,</p>
                        <p class="font-bold">Kepala {{ $pdAktif?->nama_instansi }}</p>
                        <div class="h-16"></div>
                        <p class="font-bold underline">{{ $pdAktif?->kepala_opd ?? 'Kepala Perangkat Daerah' }}</p>
                        <p>NIP. {{ $pdAktif?->nip_kepala ?? '19750101 200003 1 001' }}</p>
                    </div>
                    <div>
                        <p>Mangupura, {{ \Carbon\Carbon::parse($tanggalBa ?: now())->locale('id')->translatedFormat('d F Y') }}</p>
                        <p class="font-bold">Kepala Bappeda Kabupaten Badung</p>
                        <div class="h-16"></div>
                        <p class="font-bold underline">Kepala Badan Perencanaan Pembangunan Daerah</p>
                        <p>NIP. 19720515 199803 1 002</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
