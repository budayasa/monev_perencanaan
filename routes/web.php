<?php

use App\Http\Controllers\SSOBrokerController;
use App\Models\BeritaAcaraMonev;
use App\Models\MonevRealisasi;
use App\Models\PerangkatDaerah;
use App\Models\Renja;
use App\Services\MonevService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

// 1. Halaman Utama (Landing Page Pemkab Badung)
Route::get('/', function () {
    return view('landing');
});

// 2. Cetakan Resmi PDF Laporan Evaluasi Hasil Renja / RKPD (Format Lampiran Permendagri 86/2017)
Route::get('/cetak-evaluasi-pdf/{perangkat_daerah_id}/{tahun?}/{triwulan?}', function (
    int $perangkat_daerah_id,
    string $tahun = '2026',
    int $triwulan = 1
) {
    $pd = PerangkatDaerah::findOrFail($perangkat_daerah_id);
    $renjas = Renja::where('perangkat_daerah_id', $pd->id)
        ->where('tahun', $tahun)
        ->orderBy('kode_sub_kegiatan')
        ->get();

    $realisasis = MonevRealisasi::where('perangkat_daerah_id', $pd->id)
        ->where('tahun', $tahun)
        ->where('triwulan', $triwulan)
        ->get()
        ->keyBy('renja_id');

    $rekap = MonevService::hitungRekapOpd($pd, $tahun, $triwulan, $renjas, $realisasis->values());
    $ba = BeritaAcaraMonev::where('perangkat_daerah_id', $pd->id)
        ->where('tahun', $tahun)
        ->where('triwulan', $triwulan)
        ->first();

    $pdf = Pdf::loadView('pdf.evaluasi-permendagri86', [
        'pd' => $pd,
        'tahun' => $tahun,
        'triwulan' => $triwulan,
        'renjas' => $renjas,
        'realisasis' => $realisasis,
        'rekap' => $rekap,
        'ba' => $ba,
    ])->setPaper('a4', 'landscape');

    return $pdf->stream('Laporan_Evaluasi_Permendagri86_'.($pd->singkatan ?: $pd->id)."_{$tahun}_TW{$triwulan}.pdf");
})->name('cetak.evaluasi.pdf');

// 3. SSO auth.badungkab.go.id & Mock Login Switcher
Route::redirect('/admin/login', '/authenticateToSSO');
Route::get('authenticateToSSO/{authData?}', [SSOBrokerController::class, 'authenticateToSSO'])->name('sso.auth');
Route::get('authData/{authData?}', [SSOBrokerController::class, 'authenticateToSSO']);

Route::withoutMiddleware([VerifyCsrfToken::class])
    ->group(function () {
        Route::match(['get', 'post'], 'logout', [SSOBrokerController::class, 'logout'])->name('sso.logout');
        Route::match(['get', 'post'], 'logout/{sessionId}', [SSOBrokerController::class, 'logout']);
        Route::match(['get', 'post'], 'logoutBroker', [SSOBrokerController::class, 'logout']);
        Route::any('sso_callback_logout', [SSOBrokerController::class, 'ssoCallbackLogout']);
        Route::any('sso_callback_logout/{sessionId}', [SSOBrokerController::class, 'ssoCallbackLogout']);
    });
