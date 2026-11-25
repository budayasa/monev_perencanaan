<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RpjmdVisi extends Model
{
    use LogsActivity;

    protected $fillable = [
        'tahun_awal',
        'tahun_akhir',
        'visi',
    ];

    protected $casts = [
        'tahun_awal' => 'integer',
        'tahun_akhir' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function misis()
    {
        return $this->hasMany(RpjmdMisi::class)->orderBy('urutan')->orderBy('id');
    }
}
