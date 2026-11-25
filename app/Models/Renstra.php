<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Renstra extends Model
{
    use LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'kondisi_awal' => 'float',
        'target_t1' => 'float',
        'target_t2' => 'float',
        'target_t3' => 'float',
        'target_t4' => 'float',
        'target_t5' => 'float',
        'pagu_5_tahun' => 'float',
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

    public function rpjmd()
    {
        return $this->belongsTo(Rpjmd::class);
    }

    public function renjas()
    {
        return $this->hasMany(Renja::class);
    }
}
