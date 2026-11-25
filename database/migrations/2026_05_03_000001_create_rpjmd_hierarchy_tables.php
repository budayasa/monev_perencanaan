<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. VISI RPJMD (Pasti 1 Visi per periode RPJMD)
        Schema::create('rpjmd_visis', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun_awal')->default(2025);
            $table->integer('tahun_akhir')->default(2029);
            $table->text('visi');
            $table->timestamps();
        });

        // 2. MISI RPJMD (1 Visi -> Banyak Misi)
        Schema::create('rpjmd_misis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rpjmd_visi_id')->constrained('rpjmd_visis')->cascadeOnDelete();
            $table->integer('urutan')->default(1);
            $table->text('misi');
            $table->timestamps();
        });

        // 3. TUJUAN RPJMD (1 Misi -> Banyak Tujuan)
        Schema::create('rpjmd_tujuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rpjmd_misi_id')->constrained('rpjmd_misis')->cascadeOnDelete();
            $table->integer('urutan')->default(1);
            $table->text('tujuan');
            $table->timestamps();
        });

        // 4. INDIKATOR TUJUAN (1 Tujuan -> 2 atau lebih Indikator Tujuan)
        Schema::create('rpjmd_indikator_tujuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rpjmd_tujuan_id')->constrained('rpjmd_tujuans')->cascadeOnDelete();
            $table->text('indikator');
            $table->string('satuan', 50)->default('%');
            $table->string('sifat_target', 30)->default('non_akumulatif');
            $table->decimal('capaian_awal', 15, 2)->default(0);
            $table->decimal('target_t1', 15, 2)->default(0);
            $table->decimal('target_t2', 15, 2)->default(0);
            $table->decimal('target_t3', 15, 2)->default(0);
            $table->decimal('target_t4', 15, 2)->default(0);
            $table->decimal('target_t5', 15, 2)->default(0);
            $table->decimal('target_akhir', 15, 2)->default(0);
            $table->timestamps();
        });

        // 5. SASARAN RPJMD (1 Tujuan -> Banyak Sasaran)
        Schema::create('rpjmd_sasarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rpjmd_tujuan_id')->constrained('rpjmd_tujuans')->cascadeOnDelete();
            $table->integer('urutan')->default(1);
            $table->text('sasaran');
            $table->timestamps();
        });

        // 6. INDIKATOR SASARAN (1 Sasaran -> 2 atau lebih Indikator Sasaran)
        Schema::create('rpjmd_indikator_sasarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rpjmd_sasaran_id')->constrained('rpjmd_sasarans')->cascadeOnDelete();
            $table->text('indikator');
            $table->string('satuan', 50)->default('%');
            $table->string('sifat_target', 30)->default('non_akumulatif');
            $table->decimal('capaian_awal', 15, 2)->default(0);
            $table->decimal('target_t1', 15, 2)->default(0);
            $table->decimal('target_t2', 15, 2)->default(0);
            $table->decimal('target_t3', 15, 2)->default(0);
            $table->decimal('target_t4', 15, 2)->default(0);
            $table->decimal('target_t5', 15, 2)->default(0);
            $table->decimal('target_akhir', 15, 2)->default(0);
            $table->timestamps();
        });

        // 7. PROGRAM RPJMD (1 Sasaran -> Banyak Program; 1 Program PASTI hanya 1 Indikator Program + Pagu 5 Tahun)
        Schema::create('rpjmd_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rpjmd_sasaran_id')->constrained('rpjmd_sasarans')->cascadeOnDelete();
            $table->string('kode_program', 80)->nullable();
            $table->text('nama_program');
            $table->text('indikator_program')->nullable();
            $table->string('satuan', 50)->nullable()->default('%');
            $table->string('sifat_target', 30)->default('non_akumulatif');
            $table->decimal('capaian_awal', 15, 2)->default(0);
            $table->decimal('target_t1', 15, 2)->default(0);
            $table->decimal('target_t2', 15, 2)->default(0);
            $table->decimal('target_t3', 15, 2)->default(0);
            $table->decimal('target_t4', 15, 2)->default(0);
            $table->decimal('target_t5', 15, 2)->default(0);
            $table->decimal('target_akhir', 15, 2)->default(0);
            $table->decimal('pagu_t1', 18, 2)->default(0);
            $table->decimal('pagu_t2', 18, 2)->default(0);
            $table->decimal('pagu_t3', 18, 2)->default(0);
            $table->decimal('pagu_t4', 18, 2)->default(0);
            $table->decimal('pagu_t5', 18, 2)->default(0);
            $table->decimal('pagu_5_tahun', 18, 2)->default(0);
            $table->timestamps();
        });

        // 8. Pivot Multi-OPD Pengampu Program RPJMD
        Schema::create('opd_rpjmd_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rpjmd_program_id')->constrained('rpjmd_programs')->cascadeOnDelete();
            $table->foreignId('perangkat_daerah_id')->constrained('perangkat_daerahs')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['rpjmd_program_id', 'perangkat_daerah_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opd_rpjmd_program');
        Schema::dropIfExists('rpjmd_programs');
        Schema::dropIfExists('rpjmd_indikator_sasarans');
        Schema::dropIfExists('rpjmd_sasarans');
        Schema::dropIfExists('rpjmd_indikator_tujuans');
        Schema::dropIfExists('rpjmd_tujuans');
        Schema::dropIfExists('rpjmd_misis');
        Schema::dropIfExists('rpjmd_visis');
    }
};
