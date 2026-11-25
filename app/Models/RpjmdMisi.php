<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RpjmdMisi extends Model
{
    use LogsActivity;

    protected $fillable = [
        'rpjmd_visi_id',
        'urutan',
        'misi',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function visi()
    {
        return $this->belongsTo(RpjmdVisi::class, 'rpjmd_visi_id');
    }

    public function tujuans()
    {
        return $this->hasMany(RpjmdTujuan::class)->orderBy('urutan')->orderBy('id');
    }
}
