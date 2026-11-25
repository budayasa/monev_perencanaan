<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Rkpd extends Model
{
    use LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'target_kinerja' => 'float',
        'pagu_indikatif' => 'float',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
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
