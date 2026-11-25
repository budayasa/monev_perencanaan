<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Rpjmd extends Model
{
    use LogsActivity;

    protected $fillable = [
        'tahun_awal',
        'tahun_akhir',
        'periode_awal',
        'periode_akhir',
        'visi',
        'misi',
        'tujuan',
        'sasaran',
        'kode_iku',
        'nama_iku',
        'indikator_iku',
        'satuan',
        'sifat_target',
        'capaian_awal',
        'kondisi_awal',
        'target_t1',
        'target_t2',
        'target_t3',
        'target_t4',
        'target_t5',
        'target_akhir',
        'pagu_indikatif',
        'penanggung_jawab_pd_id',
    ];

    protected $casts = [
        'tahun_awal' => 'integer',
        'tahun_akhir' => 'integer',
        'capaian_awal' => 'float',
        'kondisi_awal' => 'float',
        'target_t1' => 'float',
        'target_t2' => 'float',
        'target_t3' => 'float',
        'target_t4' => 'float',
        'target_t5' => 'float',
        'target_akhir' => 'float',
        'pagu_indikatif' => 'float',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            // Sinkronkan kolom baru & kolom legacy agar selalu konsisten
            $model->tahun_awal = (int) ($model->tahun_awal ?: ($model->periode_awal ?: 2025));
            $model->tahun_akhir = (int) ($model->tahun_akhir ?: ($model->periode_akhir ?: 2029));
            $model->periode_awal = (string) $model->tahun_awal;
            $model->periode_akhir = (string) $model->tahun_akhir;

            if (empty($model->nama_iku) && ! empty($model->indikator_iku)) {
                $model->nama_iku = $model->indikator_iku;
            }
            if (! empty($model->nama_iku)) {
                $model->indikator_iku = $model->nama_iku;
            }

            if ($model->capaian_awal === null && $model->kondisi_awal !== null) {
                $model->capaian_awal = (float) $model->kondisi_awal;
            }
            $model->kondisi_awal = (float) ($model->capaian_awal ?? 0);

            if (empty($model->visi)) {
                $model->visi = 'Melanjutkan Kebahagiaan Masyarakat Badung Melalui Pembangunan yang Berlandaskan Tri Hita Karana';
            }

            $model->sifat_target = $model->sifat_target ?: 'non_akumulatif';

            // Hitung otomatis target_akhir berdasarkan sifat_target
            $model->target_akhir = self::hitungTargetAkhir(
                $model->sifat_target,
                (float) ($model->target_t1 ?? 0),
                (float) ($model->target_t2 ?? 0),
                (float) ($model->target_t3 ?? 0),
                (float) ($model->target_t4 ?? 0),
                (float) ($model->target_t5 ?? 0)
            );
        });
    }

    /**
     * Hitung Target Akhir Periode RPJMD berdasarkan sifat_target:
     * - akumulatif: penjumlahan T1 + T2 + T3 + T4 + T5
     * - non_akumulatif: mengambil nilai target_t5 (atau tahun terakhir yang terisi)
     */
    public static function hitungTargetAkhir(
        string $sifatTarget,
        float $t1 = 0,
        float $t2 = 0,
        float $t3 = 0,
        float $t4 = 0,
        float $t5 = 0
    ): float {
        if ($sifatTarget === 'akumulatif') {
            return round($t1 + $t2 + $t3 + $t4 + $t5, 4);
        }

        // Non-akumulatif: utamakan target_t5, fallback ke tahun terakhir yang > 0 jika t5 = 0
        if ($t5 != 0.0) {
            return round($t5, 4);
        }

        foreach ([$t4, $t3, $t2, $t1] as $val) {
            if ($val != 0.0) {
                return round($val, 4);
            }
        }

        return round($t5, 4);
    }

    /**
     * Format angka target secara fleksibel:
     * - Jika memiliki lebih dari 2 angka di belakang koma (misal 0,6701 atau 0,6830), tampilkan 4 desimal.
     * - Jika cukup 2 angka di belakang koma (misal 96,60 atau 6,95), tampilkan 2 desimal.
     */
    public static function formatTarget(float|int|string|null $value): string
    {
        $num = round((float) ($value ?? 0), 4);
        $decimals = abs($num - round($num, 2)) >= 0.00005 ? 4 : 2;

        return number_format($num, $decimals, ',', '.');
    }

    /**
     * Tampilkan target: jika memiliki teks rentang (misal "2,10 - 2,20"), gunakan teks tersebut;
     * jika tidak, format angka numerik secara fleksibel (2 atau 4 desimal).
     */
    public static function displayTarget(float|int|string|null $numeric, ?string $text = null): string
    {
        if (filled($text)) {
            return trim((string) $text);
        }

        return self::formatTarget($numeric);
    }

    /**
     * Parsing input target (bisa angka tunggal "0,6701" / "0.6701" atau rentang "2,10 - 2,20").
     * Mengembalikan ['numeric' => float, 'text' => ?string].
     */
    public static function parseTargetInput(mixed $input): array
    {
        $raw = trim((string) ($input ?? ''));
        if ($raw === '') {
            return ['numeric' => 0.0, 'text' => null];
        }

        // Cek pola rentang: angka - angka (mendukung koma maupun titik desimal)
        if (preg_match('/^([0-9]+(?:[.,][0-9]+)?)\s*[-–]\s*([0-9]+(?:[.,][0-9]+)?)$/', $raw, $matches)) {
            $min = self::toFloat($matches[1]);
            $max = self::toFloat($matches[2]);
            $numeric = round(($min + $max) / 2, 4);
            $text = self::formatTarget($min).' - '.self::formatTarget($max);

            return ['numeric' => $numeric, 'text' => $text];
        }

        // Cek pola angka tunggal (mendukung koma/titik desimal & pemisah ribuan)
        if (preg_match('/^-?[0-9]+(?:[.,][0-9]+)*$/', $raw)) {
            return [
                'numeric' => round(self::toFloat($raw), 4),
                'text' => null,
            ];
        }

        // Teks / kata kualitatif (misal: "WTP", "Predikat A", "BB")
        return [
            'numeric' => 0.0,
            'text' => $raw,
        ];
    }

    /**
     * Hitung teks Target Akhir untuk target bertipe rentang atau teks kualitatif.
     */
    public static function hitungTargetAkhirTeks(
        string $sifatTarget,
        array $items // [['numeric' => float, 'text' => ?string], ...] untuk t1..t5
    ): ?string {
        $hasText = false;
        $hasRange = false;
        foreach ($items as $item) {
            $txt = trim((string) ($item['text'] ?? ''));
            if ($txt !== '') {
                $hasText = true;
                if (preg_match('/^([0-9.,]+)\s*[-–]\s*([0-9.,]+)$/', $txt)) {
                    $hasRange = true;
                }
            }
        }

        if (! $hasText) {
            return null;
        }

        if ($sifatTarget === 'akumulatif' && $hasRange) {
            $sumMin = 0.0;
            $sumMax = 0.0;
            foreach ($items as $item) {
                $txt = trim((string) ($item['text'] ?? ''));
                if ($txt !== '' && preg_match('/^([0-9.,]+)\s*[-–]\s*([0-9.,]+)$/', $txt, $m)) {
                    $sumMin += self::toFloat($m[1]);
                    $sumMax += self::toFloat($m[2]);
                } else {
                    $val = (float) ($item['numeric'] ?? 0);
                    $sumMin += $val;
                    $sumMax += $val;
                }
            }

            return self::formatTarget($sumMin).' - '.self::formatTarget($sumMax);
        }

        // Non-akumulatif (atau teks kualitatif seperti WTP): ambil T5 terlebih dahulu, atau tahun terakhir yang terisi
        $t5 = $items[4] ?? ['numeric' => 0, 'text' => null];
        if (! empty($t5['text'])) {
            return $t5['text'];
        }
        if ((float) ($t5['numeric'] ?? 0) != 0.0) {
            return null;
        }

        for ($i = 3; $i >= 0; $i--) {
            $item = $items[$i] ?? ['numeric' => 0, 'text' => null];
            if (! empty($item['text'])) {
                return $item['text'];
            }
            if ((float) ($item['numeric'] ?? 0) != 0.0) {
                return null;
            }
        }

        return null;
    }

    private static function toFloat(string $val): float
    {
        $val = trim($val);
        if ($val === '') {
            return 0.0;
        }
        // Format Indonesia dengan pemisah ribuan titik dan koma desimal (misal 1.250,75)
        if (str_contains($val, '.') && str_contains($val, ',')) {
            $val = str_replace('.', '', $val);
            $val = str_replace(',', '.', $val);
        } elseif (str_contains($val, ',')) {
            $val = str_replace(',', '.', $val);
        }

        return (float) $val;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Relasi Multi-OPD Pengampu IKU/Sasaran RPJMD melalui tabel pivot opd_rpjmd.
     */
    public function opds()
    {
        return $this->belongsToMany(
            PerangkatDaerah::class,
            'opd_rpjmd',
            'rpjmd_id',
            'perangkat_daerah_id'
        )->withTimestamps();
    }

    public function penanggungJawabPd()
    {
        return $this->belongsTo(PerangkatDaerah::class, 'penanggung_jawab_pd_id');
    }

    public function rkpds()
    {
        return $this->hasMany(Rkpd::class);
    }

    public function renstras()
    {
        return $this->hasMany(Renstra::class);
    }
}
