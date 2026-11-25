<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

class KinerjaHarianService
{
    /**
     * Ambil rekap aktivitas harian seorang pegawai (User) pada tanggal tertentu.
     */
    public static function getRekapHarian(int $userId, string $tanggal): array
    {
        $user = User::with('perangkatDaerah')->findOrFail($userId);
        $dateObj = Carbon::parse($tanggal);

        $activities = Activity::where('causer_type', User::class)
            ->where('causer_id', $userId)
            ->whereDate('created_at', $dateObj->toDateString())
            ->orderBy('created_at', 'asc')
            ->get();

        $poinKinerja = self::susunPoinKinerja($user, $activities);

        $jamMulai = $activities->first()?->created_at?->format('H:i') ?? '08:00';
        $jamSelesai = $activities->last()?->created_at?->format('H:i') ?? '16:00';

        $nomorDokumen = sprintf(
            'BKH/MONEV/%s/%d/%s',
            $user->perangkatDaerah_id ? 'OPD-'.$user->perangkat_daerah_id : strtoupper($user->role),
            $user->id,
            $dateObj->format('Ymd')
        );

        return [
            'user' => $user,
            'tanggal' => $dateObj->toDateString(),
            'tanggal_indo' => $dateObj->locale('id')->translatedFormat('l, d F Y'),
            'activities' => $activities,
            'total_aktivitas' => $activities->count(),
            'poin_kinerja' => $poinKinerja,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'nomor_dokumen' => $nomorDokumen,
        ];
    }

    /**
     * Konversi log aktivitas teknis menjadi kalimat narasi Kinerja Harian ASN Pemkab Badung.
     */
    public static function susunPoinKinerja(User $user, $activities): array
    {
        if ($activities->isEmpty()) {
            return [
                'Melaksanakan pemantauan dan pengecekan dokumen perencanaan serta laporan Monev Triwulanan pada Sistem E-Monev Perencanaan Kabupaten Badung.',
            ];
        }

        $poin = [];
        $monevCount = 0;
        $renjaCount = 0;
        $renstraCount = 0;
        $rkpdCount = 0;
        $rpjmdCount = 0;
        $customLogs = [];

        foreach ($activities as $act) {
            $subjectType = class_basename((string) $act->subject_type);

            if ($act->log_name === 'monev_kinerja' && ! empty($act->description)) {
                $customLogs[] = $act->description;

                continue;
            }

            switch ($subjectType) {
                case 'MonevRealisasi':
                    $monevCount++;
                    break;
                case 'Renja':
                    $renjaCount++;
                    break;
                case 'Renstra':
                    $renstraCount++;
                    break;
                case 'Rkpd':
                    $rkpdCount++;
                    break;
                case 'Rpjmd':
                    $rpjmdCount++;
                    break;
                default:
                    if (! empty($act->description)) {
                        $customLogs[] = $act->description;
                    }
                    break;
            }
        }

        $instansi = $user->perangkatDaerah?->nama_instansi ?? 'Bappeda Kabupaten Badung';

        if ($monevCount > 0) {
            if (in_array($user->role, ['admin', 'evaluator'])) {
                $poin[] = "Melaksanakan verifikasi dan evaluasi capaian kinerja fisik, realisasi serapan anggaran, serta faktor penghambat/pendorong Monev Triwulanan ({$monevCount} Sub-Kegiatan).";
            } else {
                $poin[] = "Menginput dan memutakhirkan laporan realisasi fisik, serapan anggaran, faktor pendorong/penghambat, dan bukti dukung Monev Triwulanan pada {$instansi} ({$monevCount} Sub-Kegiatan).";
            }
        }

        if ($renjaCount > 0) {
            $poin[] = "Menyusun dan menyelaraskan data Rencana Kerja (Renja) Program, Kegiatan, dan Sub-Kegiatan pada {$instansi} ({$renjaCount} item).";
        }

        if ($renstraCount > 0) {
            $poin[] = "Memutakhirkan dokumen Rencana Strategis (Renstra) 5 Tahunan dan keselarasan IKU RPJMD pada {$instansi} ({$renstraCount} item).";
        }

        if ($rkpdCount > 0 || $rpjmdCount > 0) {
            $poin[] = 'Mengelola master dokumen perencanaan makro Kabupaten Badung (RPJMD & RKPD) pada Bappeda ('.($rkpdCount + $rpjmdCount).' indikator).';
        }

        foreach (array_unique($customLogs) as $desc) {
            $poin[] = $desc;
        }

        return $poin;
    }
}
