<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. MASTER PROGRAM (Format Nomenklatur Permendagri)
        Schema::create('master_programs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_urusan', 20)->nullable();
            $table->string('nama_urusan')->nullable();
            $table->string('kode_bidang_urusan', 30)->nullable();
            $table->string('nama_bidang_urusan')->nullable();
            $table->string('kode_program', 50)->unique();
            $table->text('nama_program');
            $table->text('indikator_program')->nullable();
            $table->string('satuan', 80)->default('%');
            $table->timestamps();
        });

        // 2. MASTER KEGIATAN (Format Nomenklatur Permendagri)
        Schema::create('master_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_program_id')->constrained('master_programs')->cascadeOnDelete();
            $table->string('kode_kegiatan', 60)->unique();
            $table->text('nama_kegiatan');
            $table->text('indikator_kegiatan')->nullable();
            $table->string('satuan', 80)->default('Dokumen');
            $table->timestamps();
        });

        // 3. MASTER SUB KEGIATAN (Format Nomenklatur Permendagri)
        Schema::create('master_sub_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_kegiatan_id')->constrained('master_kegiatans')->cascadeOnDelete();
            $table->string('kode_sub_kegiatan', 80)->unique();
            $table->text('nama_sub_kegiatan');
            $table->text('kinerja')->nullable();
            $table->text('indikator_sub_kegiatan')->nullable();
            $table->string('satuan', 80)->default('Dokumen');
            $table->string('klasifikasi_belanja', 120)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_sub_kegiatans');
        Schema::dropIfExists('master_kegiatans');
        Schema::dropIfExists('master_programs');
    }
};
