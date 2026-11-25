<?php

namespace Database\Seeders;

use App\Models\RpjmdIndikatorSasaran;
use App\Models\RpjmdIndikatorTujuan;
use App\Models\RpjmdMisi;
use App\Models\RpjmdProgram;
use App\Models\RpjmdSasaran;
use App\Models\RpjmdTujuan;
use App\Models\RpjmdVisi;
use Illuminate\Database\Seeder;

class RpjmdHierarchySeeder extends Seeder
{
    public function run(): void
    {
        // 1. VISI (Dokumen RPJMD 2025-2029: 2025 Baseline, Tahun Awal Target = 2026, Tahun Akhir = 2030)
        $visi = RpjmdVisi::updateOrCreate(
            ['tahun_awal' => 2026, 'tahun_akhir' => 2030],
            ['visi' => 'Melanjutkan Kebahagiaan Masyarakat Badung Melalui Pembangunan yang Berlandaskan Tri Hita Karana']
        );

        $struktur = [
            [
                'urutan' => 1,
                'misi' => 'Memperkuat Tata Kelola Pemerintahan yang Bersih, Efektif, Akuntabel, dan Berbasis Digital (SPBE)',
                'tujuans' => [
                    [
                        'urutan' => 1,
                        'tujuan' => 'Meningkatnya Kualitas Akuntabilitas Perencanaan, Kinerja, dan Layanan Publik Digital',
                        'indikators' => [
                            ['indikator' => 'Indeks Reformasi Birokrasi Kabupaten Badung', 'satuan' => 'Poin', 'sifat' => 'non_akumulatif', 'awal' => 81.50, 't' => [83.0, 84.5, 86.0, 87.5, 89.0]],
                            ['indikator' => 'Indeks Sistem Pemerintahan Berbasis Elektronik (SPBE)', 'satuan' => 'Indeks', 'sifat' => 'non_akumulatif', 'awal' => 3.85, 't' => [4.00, 4.15, 4.30, 4.45, 4.60]],
                        ],
                        'sasarans' => [
                            [
                                'urutan' => 1,
                                'sasaran' => 'Terwujudnya Sinkronisasi Perencanaan Pembangunan Daerah dan Pengendalian Kinerja Berkualitas',
                                'indikators' => [
                                    ['indikator' => 'Persentase Keselarasan Program Renstra & Renja PD terhadap RPJMD/RKPD', 'satuan' => '%', 'sifat' => 'non_akumulatif', 'awal' => 92.0, 't' => [94.0, 95.5, 97.0, 98.5, 100.0]],
                                    ['indikator' => 'Nilai SAKIP Pemerintah Kabupaten Badung', 'satuan' => 'Poin', 'sifat' => 'non_akumulatif', 'awal' => 78.5, 't' => [80.0, 81.5, 83.0, 84.5, 86.0]],
                                ],
                                'programs' => [
                                    [
                                        'kode' => '5.01.02',
                                        'nama' => 'Program Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah',
                                        'indikator' => 'Persentase Dokumen Perencanaan dan Laporan Monev Daerah yang Ditetapkan Tepat Waktu & Berkualitas',
                                        'satuan' => '%',
                                        'sifat' => 'non_akumulatif',
                                        'awal' => 94.0,
                                        't' => [95.0, 96.5, 98.0, 99.0, 100.0],
                                        'pagu' => [18_500_000_000, 19_200_000_000, 20_100_000_000, 21_000_000_000, 22_500_000_000],
                                        'opds' => [1, 7], // BAPPEDA, BRIDA
                                    ],
                                    [
                                        'kode' => '2.16.03',
                                        'nama' => 'Program Pengelolaan Aplikasi Informatika dan Transformasi Digital Daerah',
                                        'indikator' => 'Persentase Layanan Pemerintahan & Perencanaan yang Terintegrasi Digital',
                                        'satuan' => '%',
                                        'sifat' => 'non_akumulatif',
                                        'awal' => 82.0,
                                        't' => [85.0, 88.0, 91.0, 95.0, 98.0],
                                        'pagu' => [32_000_000_000, 35_000_000_000, 38_000_000_000, 40_000_000_000, 42_000_000_000],
                                        'opds' => [2], // DISKOMINFO
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'urutan' => 2,
                'misi' => 'Meningkatkan Kualitas Sumber Daya Manusia yang Unggul, Sehat, Berkarakter, dan Berdaya Saing',
                'tujuans' => [
                    [
                        'urutan' => 1,
                        'tujuan' => 'Meningkatnya Derajat Pendidikan dan Kesehatan Masyarakat Badung secara Merata',
                        'indikators' => [
                            ['indikator' => 'Indeks Pembangunan Manusia (IPM) Kabupaten Badung', 'satuan' => 'Poin', 'sifat' => 'non_akumulatif', 'awal' => 83.10, 't' => [83.45, 83.80, 84.15, 84.50, 84.85]],
                            ['indikator' => 'Angka Harapan Hidup (AHH) Masyarakat Badung', 'satuan' => 'Tahun', 'sifat' => 'non_akumulatif', 'awal' => 75.40, 't' => [75.60, 75.85, 76.10, 76.35, 76.60]],
                        ],
                        'sasarans' => [
                            [
                                'urutan' => 1,
                                'sasaran' => 'Meningkatnya Aksesibilitas dan Mutu Pendidikan Dasar serta Layanan Kesehatan Semesta',
                                'indikators' => [
                                    ['indikator' => 'Rata-rata Lama Sekolah (RLS)', 'satuan' => 'Tahun', 'sifat' => 'non_akumulatif', 'awal' => 10.65, 't' => [10.80, 10.95, 11.10, 11.25, 11.40]],
                                    ['indikator' => 'Prevalensi Penurunan Stunting Balita', 'satuan' => '%', 'sifat' => 'non_akumulatif', 'awal' => 6.80, 't' => [6.10, 5.40, 4.80, 4.20, 3.50]],
                                ],
                                'programs' => [
                                    [
                                        'kode' => '1.01.02',
                                        'nama' => 'Program Pengelolaan Pendidikan Dasar dan Peningkatan Mutu Pendidik',
                                        'indikator' => 'Persentase Satuan Pendidikan Dasar Terakreditasi Minimal B',
                                        'satuan' => '%',
                                        'sifat' => 'non_akumulatif',
                                        'awal' => 91.5,
                                        't' => [93.0, 94.5, 96.0, 97.5, 99.0],
                                        'pagu' => [210_000_000_000, 225_000_000_000, 240_000_000_000, 255_000_000_000, 270_000_000_000],
                                        'opds' => [4], // DISDIKPORA
                                    ],
                                    [
                                        'kode' => '1.02.02',
                                        'nama' => 'Program Pemenuhan Upaya Kesehatan Perorangan dan Upaya Kesehatan Masyarakat',
                                        'indikator' => 'Persentase Cakupan Layanan Kesehatan Semesta (UHC) Kabupaten Badung',
                                        'satuan' => '%',
                                        'sifat' => 'non_akumulatif',
                                        'awal' => 97.2,
                                        't' => [98.0, 98.5, 99.0, 99.5, 100.0],
                                        'pagu' => [185_000_000_000, 195_000_000_000, 205_000_000_000, 215_000_000_000, 225_000_000_000],
                                        'opds' => [14, 25], // DINKES, DP2KBP3A
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'urutan' => 3,
                'misi' => 'Memantapkan Infrastruktur Wilayah, Penguatan Pariwisata Budaya, dan Kelestarian Lingkungan Hidup',
                'tujuans' => [
                    [
                        'urutan' => 1,
                        'tujuan' => 'Tersedianya Infrastruktur Publik yang Andal serta Pariwisata dan Lingkungan Lestari',
                        'indikators' => [
                            ['indikator' => 'Persentase Jalan Kabupaten dalam Kondisi Mantap', 'satuan' => '%', 'sifat' => 'non_akumulatif', 'awal' => 86.5, 't' => [88.0, 89.5, 91.0, 92.5, 94.0]],
                            ['indikator' => 'Indeks Kualitas Lingkungan Hidup (IKLH)', 'satuan' => 'Poin', 'sifat' => 'non_akumulatif', 'awal' => 72.4, 't' => [73.5, 74.5, 75.5, 76.5, 78.0]],
                        ],
                        'sasarans' => [
                            [
                                'urutan' => 1,
                                'sasaran' => 'Meningkatnya Konektivitas Wilayah, Pengelolaan Persampahan Terpadu, dan Daya Tarik Pariwisata Budaya',
                                'indikators' => [
                                    ['indikator' => 'Jumlah Kawasan Strategis Infrastruktur & Persampahan yang Terbangun', 'satuan' => 'Kawasan', 'sifat' => 'akumulatif', 'awal' => 5.0, 't' => [4.0, 5.0, 5.0, 6.0, 6.0]],
                                    ['indikator' => 'Laju Pertumbuhan Sektor Pariwisata & Ekonomi Kreatif', 'satuan' => '%', 'sifat' => 'non_akumulatif', 'awal' => 6.15, 't' => [6.35, 6.50, 6.65, 6.80, 7.00]],
                                ],
                                'programs' => [
                                    [
                                        'kode' => '1.03.10',
                                        'nama' => 'Program Penyelenggaraan Jalan dan Konektivitas Wilayah',
                                        'indikator' => 'Panjang Jalan Kabupaten yang Ditingkatkan / Direhabilitasi (Akumulatif 5 Tahun)',
                                        'satuan' => 'Km',
                                        'sifat' => 'akumulatif',
                                        'awal' => 45.0,
                                        't' => [35.0, 40.0, 42.0, 45.0, 48.0],
                                        'pagu' => [310_000_000_000, 325_000_000_000, 340_000_000_000, 355_000_000_000, 370_000_000_000],
                                        'opds' => [11, 3], // PUPR, DISHUB
                                    ],
                                    [
                                        'kode' => '2.11.11',
                                        'nama' => 'Program Pengelolaan Persampahan dan Pelestarian Lingkungan Hidup',
                                        'indikator' => 'Persentase Pengurangan dan Penanganan Sampah Berbasis Sumber',
                                        'satuan' => '%',
                                        'sifat' => 'non_akumulatif',
                                        'awal' => 76.0,
                                        't' => [80.0, 84.0, 88.0, 92.0, 96.0],
                                        'pagu' => [95_000_000_000, 105_000_000_000, 115_000_000_000, 125_000_000_000, 135_000_000_000],
                                        'opds' => [16], // DLHK
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($struktur as $mData) {
            $misi = RpjmdMisi::updateOrCreate(
                ['rpjmd_visi_id' => $visi->id, 'urutan' => $mData['urutan']],
                ['misi' => $mData['misi']]
            );

            foreach ($mData['tujuans'] as $tData) {
                $tujuan = RpjmdTujuan::updateOrCreate(
                    ['rpjmd_misi_id' => $misi->id, 'urutan' => $tData['urutan']],
                    ['tujuan' => $tData['tujuan']]
                );

                foreach ($tData['indikators'] as $indT) {
                    RpjmdIndikatorTujuan::updateOrCreate(
                        ['rpjmd_tujuan_id' => $tujuan->id, 'indikator' => $indT['indikator']],
                        [
                            'satuan' => $indT['satuan'],
                            'sifat_target' => $indT['sifat'],
                            'capaian_awal' => $indT['awal'],
                            'target_t1' => $indT['t'][0],
                            'target_t2' => $indT['t'][1],
                            'target_t3' => $indT['t'][2],
                            'target_t4' => $indT['t'][3],
                            'target_t5' => $indT['t'][4],
                        ]
                    );
                }

                foreach ($tData['sasarans'] as $sData) {
                    $sasaran = RpjmdSasaran::updateOrCreate(
                        ['rpjmd_tujuan_id' => $tujuan->id, 'urutan' => $sData['urutan']],
                        ['sasaran' => $sData['sasaran']]
                    );

                    foreach ($sData['indikators'] as $indS) {
                        RpjmdIndikatorSasaran::updateOrCreate(
                            ['rpjmd_sasaran_id' => $sasaran->id, 'indikator' => $indS['indikator']],
                            [
                                'satuan' => $indS['satuan'],
                                'sifat_target' => $indS['sifat'],
                                'capaian_awal' => $indS['awal'],
                                'target_t1' => $indS['t'][0],
                                'target_t2' => $indS['t'][1],
                                'target_t3' => $indS['t'][2],
                                'target_t4' => $indS['t'][3],
                                'target_t5' => $indS['t'][4],
                            ]
                        );
                    }

                    foreach ($sData['programs'] as $pData) {
                        $prog = RpjmdProgram::updateOrCreate(
                            ['rpjmd_sasaran_id' => $sasaran->id, 'kode_program' => $pData['kode']],
                            [
                                'nama_program' => $pData['nama'],
                                'indikator_program' => $pData['indikator'],
                                'satuan' => $pData['satuan'],
                                'sifat_target' => $pData['sifat'],
                                'capaian_awal' => $pData['awal'],
                                'target_t1' => $pData['t'][0],
                                'target_t2' => $pData['t'][1],
                                'target_t3' => $pData['t'][2],
                                'target_t4' => $pData['t'][3],
                                'target_t5' => $pData['t'][4],
                                'pagu_t1' => $pData['pagu'][0],
                                'pagu_t2' => $pData['pagu'][1],
                                'pagu_t3' => $pData['pagu'][2],
                                'pagu_t4' => $pData['pagu'][3],
                                'pagu_t5' => $pData['pagu'][4],
                            ]
                        );
                        $prog->opds()->sync($pData['opds']);
                    }
                }
            }
        }
    }
}
