<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'rpjmd_indikator_tujuans',
            'rpjmd_indikator_sasarans',
            'rpjmd_programs',
        ];

        $columns = [
            'capaian_awal',
            'target_t1',
            'target_t2',
            'target_t3',
            'target_t4',
            'target_t5',
            'target_akhir',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($columns): void {
                foreach ($columns as $col) {
                    $table->decimal($col, 15, 4)->default(0)->change();
                }
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'rpjmd_indikator_tujuans',
            'rpjmd_indikator_sasarans',
            'rpjmd_programs',
        ];

        $columns = [
            'capaian_awal',
            'target_t1',
            'target_t2',
            'target_t3',
            'target_t4',
            'target_t5',
            'target_akhir',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($columns): void {
                foreach ($columns as $col) {
                    $table->decimal($col, 15, 2)->default(0)->change();
                }
            });
        }
    }
};
