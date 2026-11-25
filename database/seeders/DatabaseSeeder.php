<?php

namespace Database\Seeders;

use App\Models\BeritaAcaraMonev;
use App\Models\JadwalMonev;
use App\Models\MonevRealisasi;
use App\Models\PerangkatDaerah;
use App\Models\Renja;
use App\Models\Renstra;
use App\Models\Rkpd;
use App\Models\Rpjmd;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SatuanSeeder::class);

        // 1. Seed 37 Perangkat Daerah Kabupaten Badung (persis sesuai evaluasi_sakip)
        $opds = [
            1 => ['kode' => '5.01.01', 'singkatan' => 'BAPPEDA', 'nama' => 'Badan Perencanaan Pembangunan Daerah'],
            2 => ['kode' => '2.16.01', 'singkatan' => 'DISKOMINFO', 'nama' => 'Dinas Komunikasi dan Informatika'],
            3 => ['kode' => '2.15.01', 'singkatan' => 'DISHUB', 'nama' => 'Dinas Perhubungan'],
            4 => ['kode' => '1.01.01', 'singkatan' => 'DISDIKPORA', 'nama' => 'Dinas Pendidikan, Kepemudaan dan Olah Raga'],
            5 => ['kode' => '2.17.01', 'singkatan' => 'DISKOPUKMP', 'nama' => 'Dinas Koperasi, Usaha Kecil Menengah dan Perdagangan'],
            6 => ['kode' => '2.07.01', 'singkatan' => 'DISPERINAKER', 'nama' => 'Dinas Perindustrian dan Tenaga Kerja'],
            7 => ['kode' => '5.05.01', 'singkatan' => 'BRIDA', 'nama' => 'Badan Riset dan Inovasi Daerah'],
            8 => ['kode' => '1.06.01', 'singkatan' => 'DINSOS', 'nama' => 'Dinas Sosial'],
            9 => ['kode' => '5.02.01', 'singkatan' => 'BPKAD', 'nama' => 'Badan Pengelola Keuangan dan Aset Daerah'],
            10 => ['kode' => '5.03.01', 'singkatan' => 'BKPSDM', 'nama' => 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia'],
            11 => ['kode' => '1.03.01', 'singkatan' => 'PUPR', 'nama' => 'Dinas Pekerjaan Umum dan Penataan Ruang'],
            12 => ['kode' => '5.02.02', 'singkatan' => 'BAPENDA', 'nama' => 'Badan Pendapatan Daerah'],
            13 => ['kode' => '2.18.01', 'singkatan' => 'DPMPTSP', 'nama' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu'],
            14 => ['kode' => '1.02.01', 'singkatan' => 'DINKES', 'nama' => 'Dinas Kesehatan'],
            15 => ['kode' => '2.12.01', 'singkatan' => 'DISDUKCAPIL', 'nama' => 'Dinas Kependudukan dan Pencatatan Sipil'],
            16 => ['kode' => '2.11.01', 'singkatan' => 'DLHK', 'nama' => 'Dinas Lingkungan Hidup dan Kebersihan'],
            17 => ['kode' => '1.04.01', 'singkatan' => 'PERKIM', 'nama' => 'Dinas Perumahan Rakyat dan Kawasan Permukiman'],
            18 => ['kode' => '7.01.01', 'singkatan' => 'KEC-KUTA', 'nama' => 'Kecamatan Kuta'],
            19 => ['kode' => '2.22.01', 'singkatan' => 'DISBUD', 'nama' => 'Dinas Kebudayaan'],
            20 => ['kode' => '3.25.01', 'singkatan' => 'DISKAN', 'nama' => 'Dinas Perikanan'],
            21 => ['kode' => '3.26.01', 'singkatan' => 'DISPAR', 'nama' => 'Dinas Pariwisata'],
            22 => ['kode' => '6.01.01', 'singkatan' => 'INSPEKTORAT', 'nama' => 'Inspektorat'],
            23 => ['kode' => '4.02.01', 'singkatan' => 'SETWAN', 'nama' => 'Sekretariat Dewan'],
            24 => ['kode' => '2.13.01', 'singkatan' => 'DPMD', 'nama' => 'Dinas Pemberdayaan Masyarakat dan Desa'],
            25 => ['kode' => '2.14.01', 'singkatan' => 'DP2KBP3A', 'nama' => 'Dinas Pengendalian Penduduk, Keluarga Berencana, Pemberdayaan Perempuan dan Perlindungan Anak'],
            26 => ['kode' => '4.01.01', 'singkatan' => 'SETDA', 'nama' => 'Sekretariat Daerah'],
            27 => ['kode' => '1.05.02', 'singkatan' => 'DAMKAR', 'nama' => 'Dinas Kebakaran dan Penyelamatan'],
            28 => ['kode' => '1.05.01', 'singkatan' => 'SATPOLPP', 'nama' => 'Satuan Polisi Pamong Praja'],
            29 => ['kode' => '3.27.01', 'singkatan' => 'DISTANPANGAN', 'nama' => 'Dinas Pertanian dan Pangan'],
            30 => ['kode' => '2.23.01', 'singkatan' => 'DISARPUS', 'nama' => 'Dinas Kearsipan dan Perpustakaan'],
            31 => ['kode' => '8.01.01', 'singkatan' => 'KESBANGPOL', 'nama' => 'Badan Kesatuan Bangsa Dan Politik'],
            32 => ['kode' => '1.05.03', 'singkatan' => 'BPBD', 'nama' => 'Badan Penanggulangan Bencana Daerah'],
            33 => ['kode' => '7.01.02', 'singkatan' => 'KEC-KUTAUTARA', 'nama' => 'Kecamatan Kuta Utara'],
            34 => ['kode' => '7.01.03', 'singkatan' => 'KEC-KUTASELATAN', 'nama' => 'Kecamatan Kuta Selatan'],
            35 => ['kode' => '7.01.04', 'singkatan' => 'KEC-MENGWI', 'nama' => 'Kecamatan Mengwi'],
            36 => ['kode' => '7.01.05', 'singkatan' => 'KEC-ABIANSEMAL', 'nama' => 'Kecamatan Abiansemal'],
            37 => ['kode' => '7.01.06', 'singkatan' => 'KEC-PETANG', 'nama' => 'Kecamatan Petang'],
        ];

        $pdModels = [];
        foreach ($opds as $id => $info) {
            $pdModels[$id] = PerangkatDaerah::updateOrCreate(
                ['id' => $id],
                [
                    'kode_opd' => $info['kode'],
                    'nama_instansi' => $info['nama'],
                    'singkatan' => $info['singkatan'],
                    'kepala_opd' => 'Kepala '.$info['nama'],
                    'nip_kepala' => '197'.str_pad((string) $id, 2, '0', STR_PAD_LEFT).'0515199903100'.($id % 9 + 1),
                ]
            );
        }

        // 2. Seed Users untuk 4 Role Utama (Admin, Evaluator Bappeda, Perangkat Daerah, Pimpinan/Viewer)
        $defaultPass = Hash::make('1234');

        User::updateOrCreate(
            ['username' => 'admin_bappeda'],
            [
                'name' => 'Administrator Bappeda Badung',
                'email' => 'admin.bappeda@badungkab.go.id',
                'nip' => '198501012010011001',
                'nama_pejabat' => 'Kabid Perencanaan, Pengendalian & Evaluasi Pembangunan Daerah',
                'role' => 'admin',
                'perangkat_daerah_id' => 1,
                'password' => $defaultPass,
            ]
        );

        User::updateOrCreate(
            ['username' => 'asus'],
            [
                'name' => 'Super Admin Monev (Asus)',
                'email' => 'asus@badungkab.go.id',
                'nip' => '198808082012011002',
                'nama_pejabat' => 'Administrator Utama Sistem E-Monev',
                'role' => 'admin',
                'perangkat_daerah_id' => 1,
                'password' => $defaultPass,
            ]
        );

        User::updateOrCreate(
            ['username' => 'evaluator_ppepd'],
            [
                'name' => 'Verifikator Bappeda (Bidang PPEPD)',
                'email' => 'ppepd.bappeda@badungkab.go.id',
                'nip' => '199002152015031004',
                'nama_pejabat' => 'Perencana Ahli Muda — Bidang PPEPD Bappeda',
                'role' => 'evaluator',
                'jenis_evaluator' => 'Bidang PPEPD Bappeda',
                'perangkat_daerah_id' => 1,
                'password' => $defaultPass,
            ]
        );

        User::updateOrCreate(
            ['username' => 'evaluator_ekonomi'],
            [
                'name' => 'Verifikator Bappeda (Bidang Ekonomi & Infrastruktur)',
                'email' => 'ekonomi.bappeda@badungkab.go.id',
                'nip' => '199106202015032005',
                'nama_pejabat' => 'Perencana Ahli Muda — Bidang Infrastruktur & Ekonomi',
                'role' => 'evaluator',
                'jenis_evaluator' => 'Bidang Infrastruktur & Ekonomi Bappeda',
                'perangkat_daerah_id' => 1,
                'password' => $defaultPass,
            ]
        );

        User::updateOrCreate(
            ['username' => 'pimpinan_badung'],
            [
                'name' => 'Pimpinan Eksekutif Kabupaten Badung',
                'email' => 'pimpinan@badungkab.go.id',
                'nip' => '197001011995031001',
                'nama_pejabat' => 'Kepala Bappeda / Sekretaris Daerah Kabupaten Badung',
                'role' => 'viewer',
                'perangkat_daerah_id' => 26,
                'password' => $defaultPass,
            ]
        );

        // Buat akun operator untuk ke-37 OPD
        foreach ($pdModels as $id => $pd) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $pd->singkatan ?: 'opd_'.$id));
            User::updateOrCreate(
                ['username' => 'opd_'.$slug],
                [
                    'name' => 'Kasubag Perencanaan '.$pd->singkatan,
                    'email' => 'renja.'.$slug.'@badungkab.go.id',
                    'nip' => '1989'.str_pad((string) $id, 2, '0', STR_PAD_LEFT).'10201403100'.($id % 9 + 1),
                    'nama_pejabat' => 'Pejabat Fungsional Perencana '.$pd->nama_instansi,
                    'role' => 'perangkat_daerah',
                    'perangkat_daerah_id' => $pd->id,
                    'password' => $defaultPass,
                ]
            );
        }

        // 3. Seed Jadwal Monev Triwulanan (TW 1, TW 2, TW 3, TW 4 Tahun 2026)
        $jadwals = [
            1 => [
                'nama' => 'Evaluasi Renja & RKPD Triwulan I Tahun 2026',
                'mulai' => now()->subDays(30),
                'selesai' => now()->addDays(60),
                'active' => true,
                'ket' => 'Periode pengisian realisasi fisik & keuangan Triwulan I (Januari - Maret 2026) sedang dibuka.',
            ],
            2 => [
                'nama' => 'Evaluasi Renja & RKPD Triwulan II Tahun 2026',
                'mulai' => now()->subDays(15),
                'selesai' => now()->addDays(45),
                'active' => false,
                'ket' => 'Periode evaluasi Semester I / Triwulan II (April - Juni 2026).',
            ],
            3 => [
                'nama' => 'Evaluasi Renja & RKPD Triwulan III Tahun 2026',
                'mulai' => now()->addDays(30),
                'selesai' => now()->addDays(75),
                'active' => false,
                'ket' => 'Periode evaluasi Triwulan III (Juli - September 2026).',
            ],
            4 => [
                'nama' => 'Evaluasi Tahunan Renja & RKPD Triwulan IV Tahun 2026',
                'mulai' => now()->addDays(75),
                'selesai' => now()->addDays(120),
                'active' => false,
                'ket' => 'Periode evaluasi akhir tahun Triwulan IV (Oktober - Desember 2026).',
            ],
        ];

        foreach ($jadwals as $tw => $j) {
            JadwalMonev::updateOrCreate(
                ['tahun' => '2026', 'triwulan' => $tw],
                [
                    'nama_periode' => $j['nama'],
                    'waktu_mulai' => $j['mulai'],
                    'waktu_selesai' => $j['selesai'],
                    'is_active' => $j['active'],
                    'keterangan' => $j['ket'],
                ]
            );
        }

        // 4. Seed Master Level Kabupaten: RPJMD (5 Tahunan 2025-2029) & RKPD (Tahunan 2026)
        $this->call(RpjmdHierarchySeeder::class);

        $visiBadung = 'Melanjutkan Kebahagiaan Masyarakat Badung Melalui Pembangunan yang Berlandaskan Tri Hita Karana';
        $rpjmdItems = [
            [
                'kode_iku' => 'IKU-KAB-01',
                'misi' => 'Misi 1: Memperkuat Tata Kelola Pemerintahan yang Bersih, Efektif, dan Berbasis Digital (SPBE)',
                'tujuan' => 'Meningkatnya Kualitas Akuntabilitas Perencanaan, Kinerja, dan Layanan Publik Digital',
                'sasaran' => 'Terwujudnya Sinkronisasi Perencanaan Pembangunan Daerah dan Transformasi Digital Pemerintahan',
                'nama_iku' => 'Indeks Perencanaan Pembangunan Daerah & SPBE Kabupaten Badung',
                'satuan' => 'Indeks',
                'sifat_target' => 'non_akumulatif',
                'capaian_awal' => 3.85,
                't1' => 4.00, 't2' => 4.15, 't3' => 4.30, 't4' => 4.45, 't5' => 4.60,
                'pagu_indikatif' => 485_000_000_000,
                'pd_id' => 1,
                'opd_ids' => [1, 2, 7, 22, 26], // BAPPEDA, DISKOMINFO, BRIDA, INSPEKTORAT, SETDA
                'prioritas_rkpd' => 'Prioritas 1: Reformasi Birokrasi, Perencanaan Presisi & Digitalisasi Layanan Publik',
            ],
            [
                'kode_iku' => 'IKU-KAB-02',
                'misi' => 'Misi 2: Meningkatkan Kualitas Sumber Daya Manusia yang Unggul, Sehat, dan Berdaya Saing',
                'tujuan' => 'Meningkatnya Derajat Pendidikan dan Kesehatan Masyarakat Badung secara Merata',
                'sasaran' => 'Meningkatnya Rata-rata Lama Sekolah, Harapan Hidup, dan Penurunan Stunting',
                'nama_iku' => 'Indeks Pembangunan Manusia (IPM) Kabupaten Badung',
                'satuan' => 'Poin',
                'sifat_target' => 'non_akumulatif',
                'capaian_awal' => 83.10,
                't1' => 83.45, 't2' => 83.80, 't3' => 84.15, 't4' => 84.50, 't5' => 84.85,
                'pagu_indikatif' => 1_850_000_000_000,
                'pd_id' => 4,
                'opd_ids' => [4, 8, 14, 25, 30], // DISDIKPORA, DINSOS, DINKES, DP2KBP3A, DISARPUS
                'prioritas_rkpd' => 'Prioritas 2: Peningkatan Mutu Pendidikan, Kesehatan Semesta & Jaminan Sosial',
            ],
            [
                'kode_iku' => 'IKU-KAB-03',
                'misi' => 'Misi 3: Memantapkan Infrastruktur Wilayah, Penataan Ruang, dan Kualitas Lingkungan Hidup',
                'tujuan' => 'Tersedianya Infrastruktur Publik yang Andal, Konektivitas Wilayah, dan Lingkungan Lestari',
                'sasaran' => 'Meningkatnya Kemantapan Jalan, Layanan Air Bersih, dan Pengelolaan Persampahan Terpadu',
                'nama_iku' => 'Persentase Infrastruktur Wilayah dalam Kondisi Mantap & Indeks Kualitas Lingkungan Hidup',
                'satuan' => '%',
                'sifat_target' => 'non_akumulatif',
                'capaian_awal' => 86.50,
                't1' => 88.00, 't2' => 89.50, 't3' => 91.00, 't4' => 92.50, 't5' => 94.00,
                'pagu_indikatif' => 2_420_000_000_000,
                'pd_id' => 11,
                'opd_ids' => [3, 11, 16, 17], // DISHUB, PUPR, DLHK, PERKIM
                'prioritas_rkpd' => 'Prioritas 3: Akselerasi Infrastruktur Strategis, Transportasi & Pengelolaan Lingkungan',
            ],
            [
                'kode_iku' => 'IKU-KAB-04',
                'misi' => 'Misi 4: Menguatkan Ekonomi Daerah Berbasis Pariwisata Budaya, Pertanian, dan UMKM Kreatif',
                'tujuan' => 'Meningkatnya Pertumbuhan Ekonomi Inklusif dan Pendapatan Asli Daerah (PAD)',
                'sasaran' => 'Berkembangnya Pariwisata Berkualitas, Ketahanan Pangan, dan Daya Saing Koperasi/UMKM',
                'nama_iku' => 'Laju Pertumbuhan Ekonomi (LPE) & Kontribusi Sektor Pariwisata-UMKM terhadap PDRB',
                'satuan' => '%',
                'sifat_target' => 'non_akumulatif',
                'capaian_awal' => 6.15,
                't1' => 6.35, 't2' => 6.50, 't3' => 6.65, 't4' => 6.80, 't5' => 7.00,
                'pagu_indikatif' => 1_150_000_000_000,
                'pd_id' => 21,
                'opd_ids' => [5, 6, 12, 13, 20, 21, 29], // DISKOPUKMP, DISPERINAKER, BAPENDA, DPMPTSP, DISKAN, DISPAR, DISTANPANGAN
                'prioritas_rkpd' => 'Prioritas 4: Penguatan Pariwisata Berkelanjutan, Ekonomi Kreatif, Pangan & PAD',
            ],
            [
                'kode_iku' => 'IKU-KAB-05',
                'misi' => 'Misi 5: Memelihara Ketenteraman, Ketertiban Umum, Pelestarian Adat Budaya, dan Pelayanan Kewilayahan',
                'tujuan' => 'Terciptanya Kondusivitas Daerah, Ketangguhan Bencana, dan Harmoni Adat Budaya Bali',
                'sasaran' => 'Meningkatnya Ketertiban Umum, Mitigasi Bencana, dan Pelayanan Publik di Tingkat Kecamatan/Desa',
                'nama_iku' => 'Jumlah Desa/Kelurahan & Kawasan Budaya yang Mendapatkan Fasilitasi Pelestarian Adat dan Trantibumlinmas',
                'satuan' => 'Kawasan',
                'sifat_target' => 'akumulatif',
                'capaian_awal' => 12.00,
                't1' => 15.00, 't2' => 15.00, 't3' => 16.00, 't4' => 16.00, 't5' => 18.00,
                'pagu_indikatif' => 920_000_000_000,
                'pd_id' => 19,
                'opd_ids' => [18, 19, 24, 27, 28, 31, 32, 33, 34, 35, 36, 37],
                'prioritas_rkpd' => 'Prioritas 5: Pelestarian Adat Budaya, Trantibumlinmas & Pemerataan Pembangunan Kecamatan',
            ],
        ];

        $rpjmdModels = [];
        $rkpdModels = [];

        foreach ($rpjmdItems as $idx => $item) {
            $rpjmd = Rpjmd::updateOrCreate(
                ['kode_iku' => $item['kode_iku']],
                [
                    'tahun_awal' => 2025,
                    'tahun_akhir' => 2029,
                    'periode_awal' => '2025',
                    'periode_akhir' => '2029',
                    'visi' => $visiBadung,
                    'misi' => $item['misi'],
                    'tujuan' => $item['tujuan'],
                    'sasaran' => $item['sasaran'],
                    'nama_iku' => $item['nama_iku'],
                    'indikator_iku' => $item['nama_iku'],
                    'satuan' => $item['satuan'],
                    'sifat_target' => $item['sifat_target'],
                    'capaian_awal' => $item['capaian_awal'],
                    'kondisi_awal' => $item['capaian_awal'],
                    'target_t1' => $item['t1'],
                    'target_t2' => $item['t2'],
                    'target_t3' => $item['t3'],
                    'target_t4' => $item['t4'],
                    'target_t5' => $item['t5'],
                    'pagu_indikatif' => $item['pagu_indikatif'],
                    'penanggung_jawab_pd_id' => $item['pd_id'],
                ]
            );
            $rpjmd->opds()->sync($item['opd_ids']);
            $rpjmdModels[$idx] = $rpjmd;

            $rkpdModels[$idx] = Rkpd::updateOrCreate(
                ['tahun' => '2026', 'kode_prioritas' => 'PRIO-2026-0'.($idx + 1)],
                [
                    'rpjmd_id' => $rpjmd->id,
                    'prioritas_daerah' => $item['prioritas_rkpd'],
                    'sasaran_pembangunan' => $item['sasaran'],
                    'indikator_sasaran' => $item['nama_iku'],
                    'target_kinerja' => $item['t2'],
                    'satuan' => $item['satuan'],
                    'pagu_indikatif' => 450_000_000_000 + ($idx * 125_000_000_000),
                    'keterangan' => 'Target Tahun Ke-2 RPJMD (RKPD Kabupaten Badung Tahun Anggaran 2026)',
                ]
            );
        }

        // 5. Seed Renstra (5 Tahunan OPD), Renja (Tahunan OPD: Program -> Kegiatan -> Sub-Kegiatan),
        //    dan Transaksi Monev Realisasi Triwulan I & II untuk seluruh 37 OPD agar dashboard kaya data nyata!
        $faktorPendorongList = [
            'Komitmen pimpinan Perangkat Daerah dan koordinasi rutin Tim Perencana setiap awal bulan.',
            'Dukungan sistem informasi pemerintahan digital (SIPD-RI dan E-Monev Bappeda Badung) yang terintegrasi.',
            'Ketersediaan dokumen perencanaan teknis dan kerangka acuan kerja (KAK) sejak awal triwulan.',
            'Sinergi lintas bidang pada Perangkat Daerah serta percepatan kontrak e-Purchasing.',
        ];

        $faktorPenghambatList = [
            'Penyesuaian spesifikasi teknis pada Katalog Elektronik dan jadwal pengiriman dari penyedia.',
            'Beberapa rekanan belum mengajukan berkas tagihan termin pencairan SP2D tepat waktu.',
            'Sinkronisasi jadwal sosialisasi/pelaksanaan lapangan dengan pemangku kepentingan di kecamatan/desa.',
            'Revisi jadwal pelaksanaan akibat penyesuaian petunjuk teknis dari kementerian terkait.',
        ];

        $tindakLanjutList = [
            'Melaksanakan rapat evaluasi internal mingguan dipimpin Kepala OPD untuk percepatan penyerapan anggaran.',
            'Menerbitkan surat teguran/percepatan penagihan administrasi SPJ kepada PPTK dan rekanan penyedia.',
            'Melakukan optimalisasi jadwal kerja lapangan pada Triwulan berikutnya agar target 100% tercapai.',
            'Berkoordinasi intensif dengan Bidang PPEPD Bappeda dan BPKAD terkait percepatan proses verifikasi.',
        ];

        foreach ($pdModels as $id => $pd) {
            $mapIdx = ($id - 1) % count($rpjmdModels);
            $rpjmd = $rpjmdModels[$mapIdx];
            $rkpd = $rkpdModels[$mapIdx];
            $kodeOpd = $pd->kode_opd ?: '1.01.01';

            // Buat 2 Program di Renstra untuk setiap OPD
            $programs = [
                1 => [
                    'kode_prog' => $kodeOpd.'.01',
                    'nama_prog' => 'Program Penunjang Urusan Pemerintahan Daerah Kabupaten/Kota pada '.$pd->nama_instansi,
                    'indikator_prog' => 'Nilai Akuntabilitas Perencanaan & Kinerja Perangkat Daerah ('.$pd->singkatan.')',
                    'kegiatan' => [
                        'kode_keg' => $kodeOpd.'.01.2.01',
                        'nama_keg' => 'Perencanaan, Penganggaran, dan Evaluasi Kinerja Perangkat Daerah',
                        'subs' => [
                            [
                                'kode_sub' => $kodeOpd.'.01.2.01.0001',
                                'nama_sub' => 'Penyusunan Dokumen Perencanaan Perangkat Daerah (Renstra & Renja '.$pd->singkatan.')',
                                'indikator' => 'Jumlah Dokumen Perencanaan Renstra dan Renja PD yang Ditetapkan Tepat Waktu',
                                'satuan' => 'Dokumen',
                                'target' => 4,
                                'pagu' => 280_000_000 + ($id * 12_500_000),
                            ],
                            [
                                'kode_sub' => $kodeOpd.'.01.2.01.0007',
                                'nama_sub' => 'Evaluasi Kinerja Perangkat Daerah & Pelaporan Monev Triwulanan',
                                'indikator' => 'Jumlah Laporan Evaluasi Hasil Renja Triwulan I s/d IV yang Terverifikasi Bappeda',
                                'satuan' => 'Laporan',
                                'target' => 4,
                                'pagu' => 220_000_000 + ($id * 9_000_000),
                            ],
                        ],
                    ],
                ],
                2 => [
                    'kode_prog' => $kodeOpd.'.02',
                    'nama_prog' => 'Program Peningkatan Pelayanan Publik & Urusan Unggulan '.$pd->nama_instansi,
                    'indikator_prog' => 'Persentase Capaian Standar Pelayanan & Sasaran Strategis '.$pd->singkatan,
                    'kegiatan' => [
                        'kode_keg' => $kodeOpd.'.02.2.01',
                        'nama_keg' => 'Pelaksanaan Layanan Strategis dan Inovasi Daerah '.$pd->nama_instansi,
                        'subs' => [
                            [
                                'kode_sub' => $kodeOpd.'.02.2.01.0001',
                                'nama_sub' => 'Penyelenggaraan Layanan Utama & Peningkatan Kualitas Sarana Prasarana '.$pd->singkatan,
                                'indikator' => 'Persentase Pemenuhan Target Layanan Utama Masyarakat pada '.$pd->nama_instansi,
                                'satuan' => '%',
                                'target' => 100,
                                'pagu' => 2_450_000_000 + ($id * 135_000_000),
                            ],
                            [
                                'kode_sub' => $kodeOpd.'.02.2.01.0002',
                                'nama_sub' => 'Pengembangan Digitalisasi, Pembinaan Teknis, dan Monitoring Lapangan '.$pd->singkatan,
                                'indikator' => 'Persentase Cakupan Pembinaan Teknis dan Inovasi Layanan '.$pd->singkatan,
                                'satuan' => '%',
                                'target' => 100,
                                'pagu' => 1_350_000_000 + ($id * 85_000_000),
                            ],
                        ],
                    ],
                ],
            ];

            foreach ($programs as $pIdx => $prog) {
                $renstra = Renstra::updateOrCreate(
                    [
                        'perangkat_daerah_id' => $pd->id,
                        'kode_program' => $prog['kode_prog'],
                    ],
                    [
                        'rpjmd_id' => $rpjmd->id,
                        'periode_awal' => '2025',
                        'periode_akhir' => '2029',
                        'tujuan_pd' => 'Meningkatkan kualitas tata kelola dan kinerja '.$pd->nama_instansi.' dalam mendukung Visi RPJMD Badung',
                        'sasaran_pd' => 'Tercapainya target indikator kinerja program prioritas pada '.$pd->nama_instansi,
                        'nama_program' => $prog['nama_prog'],
                        'indikator_program' => $prog['indikator_prog'],
                        'satuan' => '%',
                        'kondisi_awal' => 85.0,
                        'target_t1' => 88.0,
                        'target_t2' => 91.0,
                        'target_t3' => 94.0,
                        'target_t4' => 97.0,
                        'target_t5' => 100.0,
                        'pagu_5_tahun' => ($prog['kegiatan']['subs'][0]['pagu'] + $prog['kegiatan']['subs'][1]['pagu']) * 5,
                        'status_keselarasan' => ($id % 11 === 0 && $pIdx === 2) ? 'perlu_revisi' : 'selaras',
                        'catatan_bappeda' => ($id % 11 === 0 && $pIdx === 2)
                            ? 'Mohon sesuaikan satuan indikator program dengan nomenklatur Kepmendagri 900.1.15.5.'
                            : 'Telah selaras dengan Sasaran & IKU RPJMD Kabupaten Badung 2025-2029.',
                    ]
                );

                foreach ($prog['kegiatan']['subs'] as $sIdx => $sub) {
                    $renja = Renja::updateOrCreate(
                        [
                            'perangkat_daerah_id' => $pd->id,
                            'tahun' => '2026',
                            'kode_sub_kegiatan' => $sub['kode_sub'],
                        ],
                        [
                            'renstra_id' => $renstra->id,
                            'rkpd_id' => $rkpd->id,
                            'kode_program' => $prog['kode_prog'],
                            'nama_program' => $prog['nama_prog'],
                            'kode_kegiatan' => $prog['kegiatan']['kode_keg'],
                            'nama_kegiatan' => $prog['kegiatan']['nama_keg'],
                            'nama_sub_kegiatan' => $sub['nama_sub'],
                            'indikator_sub_kegiatan' => $sub['indikator'],
                            'satuan' => $sub['satuan'],
                            'target_kinerja_tahunan' => $sub['target'],
                            'pagu_anggaran' => $sub['pagu'],
                            'target_tw1_fisik' => round($sub['target'] * 0.25, 2),
                            'target_tw2_fisik' => round($sub['target'] * 0.55, 2),
                            'target_tw3_fisik' => round($sub['target'] * 0.80, 2),
                            'target_tw4_fisik' => $sub['target'],
                            'lokasi' => 'Kabupaten Badung',
                            'sumber_dana' => 'APBD Kabupaten Badung',
                            'status_keselarasan' => 'selaras',
                            'catatan_bappeda' => 'Selaras dengan Prioritas RKPD Tahun 2026 dan Pagu Indikatif SIPD.',
                        ]
                    );

                    // Variasi performa tiap OPD agar Predikat Permendagri 86/2017 terdistribusi:
                    // Sangat Tinggi (>90%), Tinggi (75-90%), Sedang (65-75%), Rendah (50-65%), Sangat Rendah (<=50%)
                    $ratioBase = match ($id % 6) {
                        1 => 0.94, // Sangat Tinggi (termasuk Bappeda id=1)
                        2 => 0.84, // Tinggi (Diskominfo id=2)
                        3 => 0.78, // Tinggi
                        4 => 0.70, // Sedang
                        5 => 0.58, // Rendah
                        default => 0.46, // Sangat Rendah
                    };

                    $variation = (($pIdx + $sIdx) % 3) * 0.02;
                    $ratioFisikTw1 = min(1.0, $ratioBase + $variation);
                    $ratioUangTw1 = max(0.35, min(0.98, $ratioFisikTw1 - 0.04));

                    $statusTw1 = match ($id % 5) {
                        1, 2 => 'verified',
                        3 => 'final',
                        4 => 'submitted',
                        default => 'revisi',
                    };

                    MonevRealisasi::updateOrCreate(
                        [
                            'renja_id' => $renja->id,
                            'triwulan' => 1,
                        ],
                        [
                            'perangkat_daerah_id' => $pd->id,
                            'tahun' => '2026',
                            'realisasi_kinerja' => round($sub['target'] * $ratioFisikTw1, 2),
                            'realisasi_anggaran' => round($sub['pagu'] * $ratioUangTw1, 0),
                            'faktor_pendorong' => $faktorPendorongList[($id + $sIdx) % count($faktorPendorongList)],
                            'faktor_penghambat' => $faktorPenghambatList[($id + $pIdx) % count($faktorPenghambatList)],
                            'tindak_lanjut' => $tindakLanjutList[($id + $pIdx + $sIdx) % count($tindakLanjutList)],
                            'link_bukti_dukung' => 'https://drive.badungkab.go.id/monev-2026/tw1/'.strtolower($pd->singkatan),
                            'status' => $statusTw1,
                            'catatan_verifikator' => $statusTw1 === 'revisi'
                                ? 'Mohon lengkapi rincian realisasi SP2D pada link bukti dukung dan perjelas rencana aksi Triwulan II.'
                                : 'Data realisasi fisik dan keuangan Triwulan I telah sesuai dengan laporan SIPD dan bukti dukung.',
                            'verified_by' => in_array($statusTw1, ['verified', 'final', 'revisi']) ? 3 : null,
                            'verified_at' => in_array($statusTw1, ['verified', 'final', 'revisi']) ? now()->subDays(2) : null,
                        ]
                    );
                }
            }

            // Seed Berita Acara Monev untuk beberapa OPD utama
            if ($id <= 10) {
                BeritaAcaraMonev::updateOrCreate(
                    [
                        'perangkat_daerah_id' => $pd->id,
                        'tahun' => '2026',
                        'triwulan' => 1,
                    ],
                    [
                        'nomor_ba' => sprintf('050/%03d/BA-MONEV/BAPPEDA/2026', $id),
                        'tanggal_ba' => now()->toDateString(),
                        'catatan_evaluasi' => 'Pelaksanaan program dan kegiatan Renja '.$pd->nama_instansi.' pada Triwulan I Tahun 2026 secara umum telah berjalan selaras dengan target RKPD Kabupaten Badung.',
                        'rekomendasi_bappeda' => '1. Mempertahankan konsistensi pencapaian target fisik setiap sub-kegiatan.\n2. Mempercepat proses administrasi penagihan dan penyerapan anggaran kas pada Triwulan II.\n3. Melaporkan tindak lanjut faktor penghambat secara berkala melalui Sistem E-Monev Bappeda.',
                        'status' => $id <= 5 ? 'terbit' : 'draft',
                        'evaluator_id' => 3,
                    ]
                );
            }
        }
    }
}
