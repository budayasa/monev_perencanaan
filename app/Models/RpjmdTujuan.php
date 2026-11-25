<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RpjmdTujuan extends Model
{
    use LogsActivity;

    protected $fillable = [
        'rpjmd_misi_id',
        'urutan',
        'tujuan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function misi()
    {
        return $this->belongsTo(RpjmdMisi::class, 'rpjmd_misi_id');
    }

    public function indikators()
    {
        return $this->hasMany(RpjmdIndikatorTujuan::class)->orderBy('id');
    }

    public function sasarans()
    {
        return $this->hasMany(RpjmdSasaran::class)->orderBy('urutan')->orderBy('id');
    }
}
