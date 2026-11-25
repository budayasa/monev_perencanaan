<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MasterSubKegiatan extends Model
{
    use LogsActivity;

    protected $fillable = [
        'master_kegiatan_id',
        'kode_sub_kegiatan',
        'nama_sub_kegiatan',
        'kinerja',
        'indikator_sub_kegiatan',
        'satuan',
        'klasifikasi_belanja',
        'spm',
        'aktivitas_layanan',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function kegiatan()
    {
        return $this->belongsTo(MasterKegiatan::class, 'master_kegiatan_id');
    }
}
