<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nama_satuan' => '%', 'keterangan' => 'Persentase capaian atau rasio'],
            ['nama_satuan' => 'Indeks', 'keterangan' => 'Nilai indeks (contoh: Indeks Harmoni Indonesia, SPBE)'],
            ['nama_satuan' => 'Poin', 'keterangan' => 'Skor atau poin penilaian (contoh: IPM, SAKIP, IKLH)'],
            ['nama_satuan' => 'Skor', 'keterangan' => 'Skor evaluasi atau indeks'],
            ['nama_satuan' => 'Dokumen', 'keterangan' => 'Jumlah dokumen perencanaan / laporan / regulasi'],
            ['nama_satuan' => 'Laporan', 'keterangan' => 'Jumlah laporan kinerja / monev / keuangan'],
            ['nama_satuan' => 'Kegiatan', 'keterangan' => 'Jumlah pelaksanaan kegiatan'],
            ['nama_satuan' => 'Paket', 'keterangan' => 'Jumlah paket pekerjaan atau pengadaan'],
            ['nama_satuan' => 'Unit', 'keterangan' => 'Jumlah unit sarana / prasarana / alat'],
            ['nama_satuan' => 'Orang', 'keterangan' => 'Jumlah penerima manfaat / peserta / SDM'],
            ['nama_satuan' => 'Tahun', 'keterangan' => 'Satuan waktu tahun (contoh: AHH, RLS)'],
            ['nama_satuan' => 'Km', 'keterangan' => 'Panjang jalan / jaringan dalam Kilometer'],
            ['nama_satuan' => 'Ha', 'keterangan' => 'Luas lahan / kawasan dalam Hektar'],
            ['nama_satuan' => 'Kawasan', 'keterangan' => 'Jumlah kawasan strategis / budaya'],
            ['nama_satuan' => 'Desa/Kelurahan', 'keterangan' => 'Jumlah desa/kelurahan terlayani'],
            ['nama_satuan' => 'OPD', 'keterangan' => 'Jumlah Perangkat Daerah'],
            ['nama_satuan' => 'Layanan', 'keterangan' => 'Jumlah layanan publik / digital'],
            ['nama_satuan' => 'Rupiah', 'keterangan' => 'Nominal nilai dalam Rupiah'],
            ['nama_satuan' => 'Kategori', 'keterangan' => 'Predikat atau kategori capaian'],
        ];

        foreach ($items as $item) {
            Satuan::updateOrCreate(
                ['nama_satuan' => $item['nama_satuan']],
                [
                    'keterangan' => $item['keterangan'],
                    'is_active' => true,
                ]
            );
        }
    }
}
