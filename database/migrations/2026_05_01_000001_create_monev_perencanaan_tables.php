<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. RPJMD (5 Tahunan Kabupaten - Dikelola Admin / Bappeda)
        Schema::create('rpjmds', function (Blueprint $table) {
            $table->id();
            $table->string('periode_awal', 4)->default('2025');
            $table->string('periode_akhir', 4)->default('2029');
            $table->string('kode_iku', 50)->nullable();
            $table->text('visi');
            $table->text('misi');
            $table->text('tujuan');
            $table->text('sasaran');
            $table->text('indikator_iku');
            $table->string('satuan', 50)->default('%');
            $table->decimal('kondisi_awal', 15, 2)->default(0);
            $table->decimal('target_t1', 15, 2)->default(0);
            $table->decimal('target_t2', 15, 2)->default(0);
            $table->decimal('target_t3', 15, 2)->default(0);
            $table->decimal('target_t4', 15, 2)->default(0);
            $table->decimal('target_t5', 15, 2)->default(0);
            $table->decimal('target_akhir', 15, 2)->default(0);
            $table->foreignId('penanggung_jawab_pd_id')->nullable()->constrained('perangkat_daerahs')->nullOnDelete();
            $table->timestamps();
        });

        // 2. RKPD (Tahunan Kabupaten - Turunan tahunan dari RPJMD)
        Schema::create('rkpds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rpjmd_id')->nullable()->constrained('rpjmds')->nullOnDelete();
            $table->string('tahun', 4)->default('2026')->index();
            $table->string('kode_prioritas', 50)->nullable();
            $table->string('prioritas_daerah');
            $table->text('sasaran_pembangunan');
            $table->text('indikator_sasaran');
            $table->decimal('target_kinerja', 15, 2)->default(0);
            $table->string('satuan', 50)->default('%');
            $table->decimal('pagu_indikatif', 18, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 3. RENSTRA (5 Tahunan OPD - Selaras dengan RPJMD)
        Schema::create('renstras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_daerah_id')->constrained('perangkat_daerahs')->cascadeOnDelete();
            $table->foreignId('rpjmd_id')->nullable()->constrained('rpjmds')->nullOnDelete();
            $table->string('periode_awal', 4)->default('2025');
            $table->string('periode_akhir', 4)->default('2029');
            $table->text('tujuan_pd');
            $table->text('sasaran_pd');
            $table->string('kode_program', 80)->nullable();
            $table->string('nama_program');
            $table->text('indikator_program');
            $table->string('satuan', 50)->default('%');
            $table->decimal('kondisi_awal', 15, 2)->default(0);
            $table->decimal('target_t1', 15, 2)->default(0);
            $table->decimal('target_t2', 15, 2)->default(0);
            $table->decimal('target_t3', 15, 2)->default(0);
            $table->decimal('target_t4', 15, 2)->default(0);
            $table->decimal('target_t5', 15, 2)->default(0);
            $table->decimal('pagu_5_tahun', 18, 2)->default(0);
            $table->string('status_keselarasan', 30)->default('selaras'); // selaras, perlu_revisi, belum_dicek
            $table->text('catatan_bappeda')->nullable();
            $table->timestamps();
        });

        // 4. RENJA (Tahunan OPD - Program -> Kegiatan -> Sub-Kegiatan SIPD)
        Schema::create('renjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_daerah_id')->constrained('perangkat_daerahs')->cascadeOnDelete();
            $table->foreignId('renstra_id')->nullable()->constrained('renstras')->nullOnDelete();
            $table->foreignId('rkpd_id')->nullable()->constrained('rkpds')->nullOnDelete();
            $table->string('tahun', 4)->default('2026')->index();
            $table->string('kode_program', 80)->nullable();
            $table->string('nama_program');
            $table->string('kode_kegiatan', 80)->nullable();
            $table->string('nama_kegiatan');
            $table->string('kode_sub_kegiatan', 80)->nullable();
            $table->string('nama_sub_kegiatan');
            $table->text('indikator_sub_kegiatan');
            $table->string('satuan', 50)->default('Dokumen');
            $table->decimal('target_kinerja_tahunan', 15, 2)->default(100);
            $table->decimal('pagu_anggaran', 18, 2)->default(0);
            // Target per Triwulan (opsional/terbagi)
            $table->decimal('target_tw1_fisik', 15, 2)->default(25);
            $table->decimal('target_tw2_fisik', 15, 2)->default(50);
            $table->decimal('target_tw3_fisik', 15, 2)->default(75);
            $table->decimal('target_tw4_fisik', 15, 2)->default(100);
            $table->string('lokasi', 150)->default('Kabupaten Badung');
            $table->string('sumber_dana', 100)->default('APBD Kabupaten Badung');
            $table->string('status_keselarasan', 30)->default('selaras'); // selaras, perlu_revisi, belum_dicek
            $table->text('catatan_bappeda')->nullable();
            $table->timestamps();
        });

        // 5. JADWAL MONEV TRIWULANAN (TW 1, TW 2, TW 3, TW 4)
        Schema::create('jadwal_monevs', function (Blueprint $table) {
            $table->id();
            $table->string('tahun', 4)->default('2026')->index();
            $table->unsignedTinyInteger('triwulan')->default(1); // 1, 2, 3, 4
            $table->string('nama_periode');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 6. TABEL TRANSAKSI MONEV TRIWULANAN (monev_realisasis)
        Schema::create('monev_realisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('renja_id')->constrained('renjas')->cascadeOnDelete();
            $table->foreignId('perangkat_daerah_id')->constrained('perangkat_daerahs')->cascadeOnDelete();
            $table->string('tahun', 4)->default('2026')->index();
            $table->unsignedTinyInteger('triwulan')->default(1)->index(); // 1, 2, 3, 4
            $table->decimal('realisasi_kinerja', 15, 2)->default(0);
            $table->decimal('persen_capaian_kinerja', 8, 2)->default(0);
            $table->decimal('realisasi_anggaran', 18, 2)->default(0);
            $table->decimal('persen_serapan_anggaran', 8, 2)->default(0);
            $table->text('faktor_pendorong')->nullable();
            $table->text('faktor_penghambat')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->string('link_bukti_dukung')->nullable();
            $table->string('file_bukti')->nullable();
            $table->string('status', 30)->default('draft')->index(); // draft, submitted, verified, revisi, final
            $table->text('catatan_verifikator')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['renja_id', 'triwulan']);
        });

        // 7. BERITA ACARA / LAPORAN HASIL MONEV TRIWULANAN OPD
        Schema::create('berita_acara_monevs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_daerah_id')->constrained('perangkat_daerahs')->cascadeOnDelete();
            $table->string('tahun', 4)->default('2026');
            $table->unsignedTinyInteger('triwulan')->default(1);
            $table->string('nomor_ba')->nullable();
            $table->date('tanggal_ba')->nullable();
            $table->longText('konten_html')->nullable();
            $table->text('catatan_evaluasi')->nullable();
            $table->text('rekomendasi_bappeda')->nullable();
            $table->string('status', 30)->default('draft'); // draft, terbit
            $table->foreignId('evaluator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['perangkat_daerah_id', 'tahun', 'triwulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acara_monevs');
        Schema::dropIfExists('monev_realisasis');
        Schema::dropIfExists('jadwal_monevs');
        Schema::dropIfExists('renjas');
        Schema::dropIfExists('renstras');
        Schema::dropIfExists('rkpds');
        Schema::dropIfExists('rpjmds');
    }
};
