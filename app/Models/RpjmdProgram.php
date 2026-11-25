<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RpjmdProgram extends Model
{
    use LogsActivity;

    protected $fillable = [
        'rpjmd_sasaran_id',
        'kode_program',
        'nama_program',
        'indikator_program',
        'satuan',
        'sifat_target',
        'capaian_awal',
        'capaian_awal_teks',
        'target_t1',
        'target_t1_teks',
        'target_t2',
        'target_t2_teks',
        'target_t3',
        'target_t3_teks',
        'target_t4',
        'target_t4_teks',
        'target_t5',
        'target_t5_teks',
        'target_akhir',
        'target_akhir_teks',
        'pagu_t1',
        'pagu_t2',
        'pagu_t3',
        'pagu_t4',
        'pagu_t5',
        'pagu_5_tahun',
    ];

    protected $casts = [
        'capaian_awal' => 'float',
        'target_t1' => 'float',
        'target_t2' => 'float',
        'target_t3' => 'float',
        'target_t4' => 'float',
        'target_t5' => 'float',
        'target_akhir' => 'float',
        'pagu_t1' => 'float',
        'pagu_t2' => 'float',
        'pagu_t3' => 'float',
        'pagu_t4' => 'float',
        'pagu_t5' => 'float',
        'pagu_5_tahun' => 'float',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            $model->sifat_target = $model->sifat_target ?: 'non_akumulatif';
            $model->target_akhir = Rpjmd::hitungTargetAkhir(
                $model->sifat_target,
                (float) ($model->target_t1 ?? 0),
                (float) ($model->target_t2 ?? 0),
                (float) ($model->target_t3 ?? 0),
                (float) ($model->target_t4 ?? 0),
                (float) ($model->target_t5 ?? 0)
            );
            $model->target_akhir_teks = Rpjmd::hitungTargetAkhirTeks($model->sifat_target, [
                ['numeric' => (float) ($model->target_t1 ?? 0), 'text' => $model->target_t1_teks],
                ['numeric' => (float) ($model->target_t2 ?? 0), 'text' => $model->target_t2_teks],
                ['numeric' => (float) ($model->target_t3 ?? 0), 'text' => $model->target_t3_teks],
                ['numeric' => (float) ($model->target_t4 ?? 0), 'text' => $model->target_t4_teks],
                ['numeric' => (float) ($model->target_t5 ?? 0), 'text' => $model->target_t5_teks],
            ]);

            $model->pagu_5_tahun = round(
                (float) ($model->pagu_t1 ?? 0)
                + (float) ($model->pagu_t2 ?? 0)
                + (float) ($model->pagu_t3 ?? 0)
                + (float) ($model->pagu_t4 ?? 0)
                + (float) ($model->pagu_t5 ?? 0),
                2
            );
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function sasaran()
    {
        return $this->belongsTo(RpjmdSasaran::class, 'rpjmd_sasaran_id');
    }

    public function opds()
    {
        return $this->belongsToMany(
            PerangkatDaerah::class,
            'opd_rpjmd_program',
            'rpjmd_program_id',
            'perangkat_daerah_id'
        )->withTimestamps();
    }
}
