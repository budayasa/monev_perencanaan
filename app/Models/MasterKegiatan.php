<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MasterKegiatan extends Model
{
    use LogsActivity;

    protected $fillable = [
        'master_program_id',
        'kode_kegiatan',
        'nama_kegiatan',
        'indikator_kegiatan',
        'satuan',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function program()
    {
        return $this->belongsTo(MasterProgram::class, 'master_program_id');
    }

    public function subKegiatans()
    {
        return $this->hasMany(MasterSubKegiatan::class)->orderBy('kode_sub_kegiatan');
    }
}
