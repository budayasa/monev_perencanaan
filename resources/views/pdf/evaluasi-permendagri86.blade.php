<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Evaluasi Hasil Renja Permendagri 86/2017 — {{ $pd->nama_instansi }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 10px; color: #111; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 12px; }
        .header h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .header h1 { margin: 2px 0; font-size: 16px; text-transform: uppercase; color: #005000; }
        .header p { margin: 2px 0; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #000; padding: 4px 5px; vertical-align: top; }
        th { background-color: #e2e8f0; font-weight: bold; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .box-grid { width: 100%; margin-top: 12px; }
        .sign-table { width: 100%; margin-top: 24px; border: none; }
        .sign-table td { border: none; text-align: center; width: 50%; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PEMERINTAH KABUPATEN BADUNG</h2>
        <h1>BADAN PERENCANAAN PEMBANGUNAN DAERAH (BAPPEDA)</h1>
        <p>Pusat Pemerintahan Mangupraja Mandala, Jl. Raya Sempidi, Mengwi, Kabupaten Badung - Bali</p>
        <p><strong>LAPORAN EVALUASI TERHADAP HASIL RENJA PERANGKAT DAERAH & RKPD (PERMENDAGRI NOMOR 86 TAHUN 2017)</strong></p>
    </div>

    <div class="text-center" style="margin-bottom: 10px;">
        <strong>PERANGKAT DAERAH: {{ strtoupper($pd->nama_instansi) }} ({{ $pd->kode_opd }})</strong><br>
        <span>TAHUN ANGGARAN {{ $tahun }} • TRIWULAN {{ $triwulan }} • NOMOR BA: {{ $ba?->nomor_ba ?? sprintf('050/%03d/BA-MONEV/BAPPEDA/%s', $pd->id, $tahun) }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="9%">Kode SIPD</th>
                <th width="20%">Program / Kegiatan / Sub-Kegiatan</th>
                <th width="16%">Indikator Kinerja</th>
                <th width="11%">Target Tahunan (Fisik & Rp)</th>
                <th width="11%">Realisasi TW {{ $triwulan }} (Fisik & Rp)</th>
                <th width="9%">Capaian (%)</th>
                <th width="8%">Predikat (86/2017)</th>
                <th width="13%">Faktor Penghambat & Tindak Lanjut</th>
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
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $r->kode_sub_kegiatan }}</td>
                    <td>
                        <div style="font-size: 9px; color: #444;">{{ $r->nama_program }}</div>
                        <strong>{{ $r->nama_sub_kegiatan }}</strong>
                    </td>
                    <td>{{ $r->indikator_sub_kegiatan }}</td>
                    <td class="text-right">
                        {{ number_format($r->target_kinerja_tahunan, 0, ',', '.') }} {{ $r->satuan }}<br>
                        <strong>{{ \App\Services\MonevService::formatRupiah($r->pagu_anggaran) }}</strong>
                    </td>
                    <td class="text-right">
                        {{ number_format($rel?->realisasi_kinerja ?? 0, 2, ',', '.') }} {{ $r->satuan }}<br>
                        <strong>{{ \App\Services\MonevService::formatRupiah($rel?->realisasi_anggaran ?? 0) }}</strong>
                    </td>
                    <td class="text-center">
                        Fisik: <strong>{{ number_format($pFisik, 2, ',', '.') }}%</strong><br>
                        Keu: <strong>{{ number_format($pUang, 2, ',', '.') }}%</strong>
                    </td>
                    <td class="text-center">
                        <strong>{{ $pred['label'] }}</strong>
                    </td>
                    <td>
                        <strong>Hambatan:</strong> {{ $rel?->faktor_penghambat ?? '-' }}<br>
                        <strong>TL:</strong> {{ $rel?->tindak_lanjut ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="4" class="text-right">RATA-RATA CAPAIAN & TOTAL REALISASI TRIWULAN {{ $triwulan }}:</td>
                <td class="text-right">{{ \App\Services\MonevService::formatRupiah($rekap['pagu_anggaran']) }}</td>
                <td class="text-right">{{ \App\Services\MonevService::formatRupiah($rekap['realisasi_anggaran']) }}</td>
                <td class="text-center">
                    Fisik: {{ number_format($rekap['capaian_fisik'], 2, ',', '.') }}%<br>
                    Keu: {{ number_format($rekap['serapan_keuangan'], 2, ',', '.') }}%
                </td>
                <td colspan="2" class="text-center">
                    PREDIKAT: {{ strtoupper($rekap['predikat']['label']) }} ({{ number_format($rekap['nilai_komposit'], 2, ',', '.') }}%)
                </td>
            </tr>
        </tfoot>
    </table>

    <table class="box-grid">
        <tr>
            <td width="50%">
                <strong>Catatan Evaluasi Keselarasan Bappeda:</strong><br>
                {{ $ba?->catatan_evaluasi ?? 'Pelaksanaan program dan kegiatan Renja berjalan selaras dengan target RKPD Kabupaten Badung.' }}
            </td>
            <td width="50%">
                <strong>Rekomendasi Tindak Lanjut Triwulan Berikutnya:</strong><br>
                {{ $ba?->rekomendasi_bappeda ?? 'Mempercepat penyerapan anggaran kas dan menindaklanjuti faktor penghambat pada Triwulan berikutnya.' }}
            </td>
        </tr>
    </table>

    <table class="sign-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Kepala {{ $pd->nama_instansi }}</strong>
                <br><br><br><br>
                <strong><u>{{ $pd->kepala_opd ?? 'Kepala Perangkat Daerah' }}</u></strong><br>
                NIP. {{ $pd->nip_kepala ?? '19750101 200003 1 001' }}
            </td>
            <td>
                Mangupura, {{ now()->locale('id')->translatedFormat('d F Y') }}<br>
                <strong>Kepala Bappeda Kabupaten Badung</strong>
                <br><br><br><br>
                <strong><u>Kepala Badan Perencanaan Pembangunan Daerah</u></strong><br>
                NIP. 19720515 199803 1 002
            </td>
        </tr>
    </table>
</body>
</html>
