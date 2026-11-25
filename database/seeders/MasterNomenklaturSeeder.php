<?php

namespace Database\Seeders;

use App\Models\MasterKegiatan;
use App\Models\MasterProgram;
use App\Models\MasterSubKegiatan;
use App\Models\Satuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterNomenklaturSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/nomenklatur_rkpd_2026_badung.csv');

        if (! file_exists($csvPath)) {
            $this->command?->warn("File CSV tidak ditemukan di: {$csvPath}");

            return;
        }

        $handle = fopen($csvPath, 'r');
        if (! $handle) {
            return;
        }

        $now = now();
        $programs = [];
        $kegiatans = [];
        $subKegiatans = [];
        $uniqueSatuans = [];

        $lineNumber = 0;
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $lineNumber++;

            // Lewati baris judul (baris 1-2) dan header (baris 3)
            if ($lineNumber <= 3 || count($row) < 8) {
                continue;
            }

            $kode = trim((string) ($row[2] ?? ''));
            $parts = explode('.', $kode);
            if (count($parts) < 6) {
                continue;
            }

            // Pemecahan kode sesuai kaidah Permendagri / SIPD:
            // Contoh: 1.01.02.2.01.0001
            // - 1         : Urusan
            // - 1.01      : Bidang Urusan (01)
            // - 1.01.02   : Program (02)
            // - 1.01.02.2.01 : Kegiatan (2.01)
            // - 1.01.02.2.01.0001 : Sub Kegiatan (0001)
            $kodeUrusan = $parts[0];
            $kodeBidang = $parts[0].'.'.$parts[1];
            $kodeProgram = $parts[0].'.'.$parts[1].'.'.$parts[2];
            $kodeKegiatan = $parts[0].'.'.$parts[1].'.'.$parts[2].'.'.$parts[3].'.'.$parts[4];
            $kodeSub = $kode;

            $namaUrusan = trim((string) ($row[3] ?? ''));
            $namaBidang = trim((string) ($row[4] ?? ''));
            $namaProgram = trim((string) ($row[5] ?? ''));
            $namaKegiatan = trim((string) ($row[6] ?? ''));
            $namaSub = trim((string) ($row[7] ?? ''));
            $kinerja = trim((string) ($row[8] ?? '')) ?: null;
            $indikator = trim((string) ($row[9] ?? '')) ?: null;
            $satuan = trim((string) ($row[10] ?? '')) ?: 'Dokumen';
            $spmRaw = trim((string) ($row[11] ?? ''));
            $spm = ($spmRaw === '' || $spmRaw === '-') ? 'Non-SPM' : $spmRaw;
            $aktivitas = trim((string) ($row[12] ?? '')) ?: null;

            if ($satuan !== '') {
                $satKey = mb_strtolower($satuan);
                if (! isset($uniqueSatuans[$satKey])) {
                    $uniqueSatuans[$satKey] = $satuan;
                }
            }

            if (! isset($programs[$kodeProgram])) {
                $programs[$kodeProgram] = [
                    'kode_urusan' => $kodeUrusan,
                    'nama_urusan' => $namaUrusan,
                    'kode_bidang_urusan' => $kodeBidang,
                    'nama_bidang_urusan' => $namaBidang,
                    'kode_program' => $kodeProgram,
                    'nama_program' => $namaProgram,
                    'satuan' => '%',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (! isset($kegiatans[$kodeKegiatan])) {
                $kegiatans[$kodeKegiatan] = [
                    'kode_program' => $kodeProgram,
                    'kode_kegiatan' => $kodeKegiatan,
                    'nama_kegiatan' => $namaKegiatan,
                    'satuan' => 'Dokumen',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $subKegiatans[$kodeSub] = [
                'kode_kegiatan' => $kodeKegiatan,
                'kode_sub_kegiatan' => $kodeSub,
                'nama_sub_kegiatan' => $namaSub,
                'kinerja' => $kinerja,
                'indikator_sub_kegiatan' => $indikator,
                'satuan' => $satuan,
                'spm' => $spm,
                'aktivitas_layanan' => $aktivitas,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        fclose($handle);

        DB::transaction(function () use ($programs, $kegiatans, $subKegiatans, $uniqueSatuans, $now): void {
            // 1. Daftarkan seluruh Satuan baru dari Nomenklatur ke tabel satuans (case-insensitive)
            $existingSatuans = Satuan::query()
                ->pluck('nama_satuan')
                ->map(fn (string $v) => mb_strtolower(trim($v)))
                ->flip()
                ->all();

            $newSatuans = [];
            foreach ($uniqueSatuans as $satKey => $satLabel) {
                if (! isset($existingSatuans[$satKey])) {
                    $newSatuans[] = [
                        'nama_satuan' => $satLabel,
                        'keterangan' => 'Nomenklatur RKPD Permendagri',
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            foreach (array_chunk($newSatuans, 100) as $chunk) {
                DB::table('satuans')->insertOrIgnore($chunk);
            }

            // 2. Upsert Master Program
            foreach (array_chunk(array_values($programs), 200) as $chunk) {
                MasterProgram::upsert(
                    $chunk,
                    ['kode_program'],
                    ['kode_urusan', 'nama_urusan', 'kode_bidang_urusan', 'nama_bidang_urusan', 'nama_program', 'updated_at']
                );
            }

            $programIdMap = MasterProgram::query()->pluck('id', 'kode_program')->all();

            // 3. Upsert Master Kegiatan
            $kegRows = [];
            foreach ($kegiatans as $keg) {
                $progId = $programIdMap[$keg['kode_program']] ?? null;
                if (! $progId) {
                    continue;
                }
                $kegRows[] = [
                    'master_program_id' => $progId,
                    'kode_kegiatan' => $keg['kode_kegiatan'],
                    'nama_kegiatan' => $keg['nama_kegiatan'],
                    'satuan' => $keg['satuan'],
                    'created_at' => $keg['created_at'],
                    'updated_at' => $keg['updated_at'],
                ];
            }

            foreach (array_chunk($kegRows, 300) as $chunk) {
                MasterKegiatan::upsert(
                    $chunk,
                    ['kode_kegiatan'],
                    ['master_program_id', 'nama_kegiatan', 'updated_at']
                );
            }

            $kegiatanIdMap = MasterKegiatan::query()->pluck('id', 'kode_kegiatan')->all();

            // 4. Upsert Master Sub Kegiatan
            $subRows = [];
            foreach ($subKegiatans as $sub) {
                $kegId = $kegiatanIdMap[$sub['kode_kegiatan']] ?? null;
                if (! $kegId) {
                    continue;
                }
                $subRows[] = [
                    'master_kegiatan_id' => $kegId,
                    'kode_sub_kegiatan' => $sub['kode_sub_kegiatan'],
                    'nama_sub_kegiatan' => $sub['nama_sub_kegiatan'],
                    'kinerja' => $sub['kinerja'],
                    'indikator_sub_kegiatan' => $sub['indikator_sub_kegiatan'],
                    'satuan' => $sub['satuan'],
                    'spm' => $sub['spm'],
                    'aktivitas_layanan' => $sub['aktivitas_layanan'],
                    'created_at' => $sub['created_at'],
                    'updated_at' => $sub['updated_at'],
                ];
            }

            foreach (array_chunk($subRows, 500) as $chunk) {
                MasterSubKegiatan::upsert(
                    $chunk,
                    ['kode_sub_kegiatan'],
                    ['master_kegiatan_id', 'nama_sub_kegiatan', 'kinerja', 'indikator_sub_kegiatan', 'satuan', 'spm', 'aktivitas_layanan', 'updated_at']
                );
            }
        });
    }
}
