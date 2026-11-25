<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RpjmdSasaran extends Model
{
    use LogsActivity;

    protected $fillable = [
        'rpjmd_tujuan_id',
        'urutan',
        'sasaran',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function tujuan()
    {
        return $this->belongsTo(RpjmdTujuan::class, 'rpjmd_tujuan_id');
    }

    public function indikators()
    {
        return $this->hasMany(RpjmdIndikatorSasaran::class)->orderBy('id');
    }

    public function programs()
    {
        return $this->hasMany(RpjmdProgram::class)->orderBy('kode_program')->orderBy('id');
    }
}
