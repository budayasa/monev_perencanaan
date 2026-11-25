<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MasterProgram extends Model
{
    use LogsActivity;

    protected $fillable = [
        'kode_urusan',
        'nama_urusan',
        'kode_bidang_urusan',
        'nama_bidang_urusan',
        'kode_program',
        'nama_program',
        'indikator_program',
        'satuan',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function kegiatans()
    {
        return $this->hasMany(MasterKegiatan::class)->orderBy('kode_kegiatan');
    }
}
