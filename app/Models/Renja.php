<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Renja extends Model
{
    use LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'target_kinerja_tahunan' => 'float',
        'pagu_anggaran' => 'float',
        'target_tw1_fisik' => 'float',
        'target_tw2_fisik' => 'float',
        'target_tw3_fisik' => 'float',
        'target_tw4_fisik' => 'float',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function perangkatDaerah()
    {
        return $this->belongsTo(PerangkatDaerah::class);
    }

    public function renstra()
    {
        return $this->belongsTo(Renstra::class);
    }

    public function rkpd()
    {
        return $this->belongsTo(Rkpd::class);
    }

    public function monevRealisasis()
    {
        return $this->hasMany(MonevRealisasi::class);
    }
}
