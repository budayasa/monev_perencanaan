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

        $textColumns = [
            'capaian_awal_teks' => 'capaian_awal',
            'target_t1_teks' => 'target_t1',
            'target_t2_teks' => 'target_t2',
            'target_t3_teks' => 'target_t3',
            'target_t4_teks' => 'target_t4',
            'target_t5_teks' => 'target_t5',
            'target_akhir_teks' => 'target_akhir',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($textColumns): void {
                foreach ($textColumns as $col => $after) {
                    $table->string($col, 80)->nullable()->after($after);
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

        $cols = [
            'capaian_awal_teks',
            'target_t1_teks',
            'target_t2_teks',
            'target_t3_teks',
            'target_t4_teks',
            'target_t5_teks',
            'target_akhir_teks',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($cols): void {
                $table->dropColumn($cols);
            });
        }
    }
};
