<?php

namespace App\Models;

use App\Services\MonevService;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MonevRealisasi extends Model
{
    use LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'triwulan' => 'integer',
        'realisasi_kinerja' => 'float',
        'persen_capaian_kinerja' => 'float',
        'realisasi_anggaran' => 'float',
        'persen_serapan_anggaran' => 'float',
        'verified_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if ($model->renja) {
                $targetKinerja = (float) ($model->renja->target_kinerja_tahunan ?: 100);
                $paguAnggaran = (float) ($model->renja->pagu_anggaran ?: 0);

                $model->persen_capaian_kinerja = MonevService::hitungPersenKinerja(
                    (float) $model->realisasi_kinerja,
                    $targetKinerja
                );

                $model->persen_serapan_anggaran = MonevService::hitungPersenAnggaran(
                    (float) $model->realisasi_anggaran,
                    $paguAnggaran
                );

                if (! $model->perangkat_daerah_id) {
                    $model->perangkat_daerah_id = $model->renja->perangkat_daerah_id;
                }
                if (! $model->tahun) {
                    $model->tahun = $model->renja->tahun;
                }
            }
        });

        static::saved(function (self $model) {
            MonevService::clearCache($model->tahun);
        });

        static::deleted(function (self $model) {
            MonevService::clearCache($model->tahun);
        });
    }

    public function renja()
    {
        return $this->belongsTo(Renja::class);
    }

    public function perangkatDaerah()
    {
        return $this->belongsTo(PerangkatDaerah::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
