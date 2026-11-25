<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_sub_kegiatans', function (Blueprint $table) {
            $table->string('spm', 30)->nullable()->after('klasifikasi_belanja');
            $table->string('aktivitas_layanan', 50)->nullable()->after('spm');
            $table->index('spm');
        });

        Schema::table('master_programs', function (Blueprint $table) {
            $table->index('kode_urusan');
            $table->index('kode_bidang_urusan');
        });
    }

    public function down(): void
    {
        Schema::table('master_sub_kegiatans', function (Blueprint $table) {
            $table->dropIndex(['spm']);
            $table->dropColumn(['spm', 'aktivitas_layanan']);
        });

        Schema::table('master_programs', function (Blueprint $table) {
            $table->dropIndex(['kode_urusan']);
            $table->dropIndex(['kode_bidang_urusan']);
        });
    }
};
