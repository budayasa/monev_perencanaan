<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Kinerja Harian - {{ $user->name }} ({{ $tanggal }})</title>
    <style>
        @page { margin: 1.2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #222; line-height: 1.45; }

        .kop-surat { border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 16px; text-align: center; position: relative; min-height: 60px; }
        .logo-badung { position: absolute; left: 0; top: 0; width: 55px; }
        .kop-text h2 { margin: 0; font-size: 14px; text-transform: uppercase; color: #005000; }
        .kop-text h3 { margin: 2px 0; font-size: 12px; text-transform: uppercase; }
        .kop-text p { margin: 0; font-size: 9px; color: #444; }

        .judul { text-align: center; margin-bottom: 14px; }
        .judul h4 { margin: 0; text-decoration: underline; text-transform: uppercase; font-size: 12px; letter-spacing: 0.4px; }
        .judul p { margin: 4px 0 0; font-size: 9.5px; color: #555; }

        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .meta-table td { padding: 3px 6px; border: none; vertical-align: top; font-size: 10px; }
        .meta-label { width: 150px; font-weight: bold; color: #333; }
        .meta-colon { width: 12px; text-align: center; }

        .badge-ok { display: inline-block; background: #dcfce7; color: #065f46; border: 1px solid #86efac; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 9px; }
        .badge-empty { display: inline-block; background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 9px; }

        .ringkasan-box { border: 1px solid #b7e4c7; background: #f0fdf4; padding: 10px 12px; border-radius: 6px; margin-bottom: 16px; }
        .ringkasan-title { font-weight: bold; text-transform: uppercase; font-size: 9.5px; color: #005000; margin-bottom: 6px; }
        .ringkasan-box p { margin: 0 0 6px; text-align: justify; }
        .ringkasan-box ul { margin: 4px 0 0; padding-left: 18px; }
        .ringkasan-box li { margin-bottom: 3px; }

        .log-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .log-table th { background-color: #005000; color: #ffffff; padding: 7px 6px; border: 1px solid #005000; font-weight: bold; text-transform: uppercase; font-size: 8.5px; text-align: left; }
        .log-table td { padding: 6px; border: 1px solid #cbd5e1; vertical-align: top; font-size: 9.5px; }
        .log-table tr:nth-child(even) td { background-color: #f8fafc; }
        .text-center { text-align: center !important; }

        .footer-note { margin-top: 14px; padding-top: 8px; border-top: 1px dashed #94a3b8; font-size: 8.5px; color: #475569; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <img src="{{ public_path('images/logo-badung.png') }}" class="logo-badung" alt="Logo Badung">
        <div class="kop-text">
            <h2>PEMERINTAH KABUPATEN BADUNG</h2>
            <h3>SISTEM EVALUASI SAKIP (E-SAKIP)</h3>
            <p>Pusat Pemerintahan Mangupraja Mandala, Jl. Raya Sempidi, Mengwi, Kabupaten Badung (80351)</p>
        </div>
    </div>

    <div class="judul">
        <h4>BUKTI AKTIVITAS KINERJA HARIAN APLIKASI E-SAKIP</h4>
        <p>Sebagai Lampiran Bukti Dukung Pelaporan Kinerja Harian ASN Pemerintah Kabupaten Badung</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Nama Pegawai / Akun</td>
            <td class="meta-colon">:</td>
            <td><strong>{{ $user->name }}</strong></td>
            <td class="meta-label">Tanggal Kinerja</td>
            <td class="meta-colon">:</td>
            <td><strong>{{ $tanggal_indo }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">NIP / Username</td>
            <td class="meta-colon">:</td>
            <td>{{ $user->nip ?: ($user->username ?? $user->email) }}</td>
            <td class="meta-label">Status Kinerja</td>
            <td class="meta-colon">:</td>
            <td>
                @if($sudah_berkinerja)
                    <span class="badge-ok">SUDAH BERKINERJA ({{ $total_aktivitas }} Aktivitas)</span>
                @else
                    <span class="badge-empty">BELUM ADA AKTIVITAS</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="meta-label">Peran (Role)</td>
            <td class="meta-colon">:</td>
            <td>{{ strtoupper(str_replace('_', ' ', $user->role)) }}</td>
            <td class="meta-label">Rentang Waktu Aktivitas</td>
            <td class="meta-colon">:</td>
            <td>{{ $sudah_berkinerja ? "{$waktu_mulai} – {$waktu_selesai} WITA" : '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Instansi / Unit Kerja</td>
            <td class="meta-colon">:</td>
            <td colspan="4">{{ $user->perangkatDaerah->nama_instansi ?? 'Tim Evaluator / Admin SAKIP Kabupaten Badung' }}</td>
        </tr>
    </table>

    <div class="ringkasan-box">
        <div class="ringkasan-title">Ringkasan Uraian Kinerja Harian</div>
        <p>{{ $uraian_paragraf }}</p>
        @if(!empty($uraian_poin))
            <ul>
                @foreach($uraian_poin as $poin)
                    <li>{{ $poin }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <h4 style="margin: 0 0 6px; font-size: 10px; text-transform: uppercase; color: #005000;">Rincian Log Aktivitas Harian ({{ $total_aktivitas }} Catatan)</h4>
    <table class="log-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="13%" class="text-center">Waktu (WITA)</th>
                <th width="20%">Kategori</th>
                <th width="39%">Uraian Aktivitas</th>
                <th width="23%">Instansi Terkait</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rincian_log as $index => $log)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $log['waktu'] }}</td>
                    <td><strong>{{ $log['kategori'] }}</strong></td>
                    <td>{{ $log['deskripsi'] }}</td>
                    <td>{{ $log['instansi'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="font-style: italic; color: #64748b; padding: 14px;">
                        Tidak ada aktivitas yang tercatat pada tanggal {{ $tanggal_indo }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">
        <span>Dokumen ini dihasilkan secara otomatis dari catatan aktivitas sistem E-SAKIP Kabupaten Badung.</span>
        <span style="float: right;">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} WITA</span>
    </div>
</body>
</html>

