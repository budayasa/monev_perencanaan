<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JadwalMonev extends Model
{
    use LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'is_active' => 'boolean',
        'triwulan' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Cek apakah pengisian Monev untuk Tahun & Triwulan tertentu sedang dibuka.
     * Jika parameter tidak diisi, cek jadwal yang diset is_active = true.
     */
    public static function isBuka(?string $tahun = null, ?int $triwulan = null): bool
    {
        $query = self::where('is_active', true);

        if ($tahun !== null) {
            $query->where('tahun', $tahun);
        }

        if ($triwulan !== null) {
            $query->where('triwulan', $triwulan);
        }

        $jadwal = $query->orderByDesc('triwulan')->first();

        if (! $jadwal) {
            return false;
        }

        $sekarang = now();

        return $sekarang->between($jadwal->waktu_mulai, $jadwal->waktu_selesai);
    }

    public static function getJadwalAktif(?string $tahun = null, ?int $triwulan = null): ?self
    {
        $query = self::query();

        if ($tahun !== null) {
            $query->where('tahun', $tahun);
        }

        if ($triwulan !== null) {
            $query->where('triwulan', $triwulan);
        } else {
            $query->where('is_active', true);
        }

        return $query->orderByDesc('is_active')->orderByDesc('triwulan')->first();
    }
}
