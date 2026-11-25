<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PerangkatDaerah extends Model
{
    use LogsActivity;

    protected $fillable = [
        'kode_opd',
        'nama_instansi',
        'singkatan',
        'kepala_opd',
        'nip_kepala',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function rpjmds()
    {
        return $this->belongsToMany(
            Rpjmd::class,
            'opd_rpjmd',
            'perangkat_daerah_id',
            'rpjmd_id'
        )->withTimestamps();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function renstras()
    {
        return $this->hasMany(Renstra::class);
    }

    public function renjas()
    {
        return $this->hasMany(Renja::class);
    }

    public function monevRealisasis()
    {
        return $this->hasMany(MonevRealisasi::class);
    }

    public function beritaAcaras()
    {
        return $this->hasMany(BeritaAcaraMonev::class);
    }
}
