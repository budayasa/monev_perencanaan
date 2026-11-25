<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Satuan extends Model
{
    use LogsActivity;

    protected $fillable = [
        'nama_satuan',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            $model->nama_satuan = trim((string) $model->nama_satuan);
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    /**
     * Ambil daftar satuan aktif dalam bentuk [nama_satuan => nama_satuan] untuk Select Filament.
     */
    public static function getOptions(): array
    {
        return self::where('is_active', true)
            ->orderBy('nama_satuan')
            ->pluck('nama_satuan', 'nama_satuan')
            ->all();
    }
}
