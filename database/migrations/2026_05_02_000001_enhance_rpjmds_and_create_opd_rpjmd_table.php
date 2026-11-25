<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rpjmds', function (Blueprint $table) {
            if (! Schema::hasColumn('rpjmds', 'tahun_awal')) {
                $table->integer('tahun_awal')->default(2025)->after('id');
            }
            if (! Schema::hasColumn('rpjmds', 'tahun_akhir')) {
                $table->integer('tahun_akhir')->default(2029)->after('tahun_awal');
            }
            if (! Schema::hasColumn('rpjmds', 'nama_iku')) {
                $table->text('nama_iku')->nullable()->after('kode_iku');
            }
            if (! Schema::hasColumn('rpjmds', 'sifat_target')) {
                $table->string('sifat_target', 30)->default('non_akumulatif')->after('satuan');
            }
            if (! Schema::hasColumn('rpjmds', 'capaian_awal')) {
                $table->decimal('capaian_awal', 15, 2)->nullable()->default(0)->after('sifat_target');
            }
            if (! Schema::hasColumn('rpjmds', 'pagu_indikatif')) {
                $table->decimal('pagu_indikatif', 18, 2)->nullable()->default(0)->after('target_akhir');
            }

            // Buat kolom legacy menjadi nullable agar aman di PostgreSQL saat insert dari form baru
            $table->text('visi')->nullable()->change();
            $table->text('indikator_iku')->nullable()->change();
            $table->string('periode_awal', 4)->nullable()->default('2025')->change();
            $table->string('periode_akhir', 4)->nullable()->default('2029')->change();
            $table->decimal('kondisi_awal', 15, 2)->nullable()->default(0)->change();
            $table->index('kode_iku');
        });

        // Tabel Pivot Multi-OPD Pengampu: opd_rpjmd
        if (! Schema::hasTable('opd_rpjmd')) {
            Schema::create('opd_rpjmd', function (Blueprint $table) {
                $table->id();
                $table->foreignId('rpjmd_id')->constrained('rpjmds')->cascadeOnDelete();
                $table->foreignId('perangkat_daerah_id')->constrained('perangkat_daerahs')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['rpjmd_id', 'perangkat_daerah_id']);
            });
        }

        // Migrasikan data lama di tabel rpjmds secara aman (tanpa melanggar constraint NOT NULL)
        $existingRpjmds = DB::table('rpjmds')->get();
        foreach ($existingRpjmds as $row) {
            DB::table('rpjmds')
                ->where('id', $row->id)
                ->update([
                    'tahun_awal' => is_numeric($row->periode_awal ?? null) ? (int) $row->periode_awal : 2025,
                    'tahun_akhir' => is_numeric($row->periode_akhir ?? null) ? (int) $row->periode_akhir : 2029,
                    'nama_iku' => $row->nama_iku ?: ($row->indikator_iku ?: '-'),
                    'sifat_target' => $row->sifat_target ?: 'non_akumulatif',
                    'capaian_awal' => $row->capaian_awal ?? ($row->kondisi_awal ?? 0),
                    'pagu_indikatif' => $row->pagu_indikatif ?? 0,
                ]);

            if (! empty($row->penanggung_jawab_pd_id)) {
                DB::table('opd_rpjmd')->updateOrInsert(
                    [
                        'rpjmd_id' => $row->id,
                        'perangkat_daerah_id' => $row->penanggung_jawab_pd_id,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('opd_rpjmd');

        Schema::table('rpjmds', function (Blueprint $table) {
            $table->dropIndex(['kode_iku']);
            $table->dropColumn([
                'tahun_awal',
                'tahun_akhir',
                'nama_iku',
                'sifat_target',
                'capaian_awal',
                'pagu_indikatif',
            ]);
        });
    }
};
