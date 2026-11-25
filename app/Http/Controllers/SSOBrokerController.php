<?php

namespace App\Http\Controllers;

use App\JWT\JWT;
use App\Models\PerangkatDaerah;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SSOBrokerController extends Controller
{
    private $ssoDomain;

    private $brokerCode;

    private $jwtSecret;

    private $logoutLinkSelf;

    public function __construct()
    {
        // Pastikan ssoDomain selalu diakhiri dengan slash agar URL tidak broken
        $this->ssoDomain = rtrim(config('sso.domain'), '/').'/';
        $this->brokerCode = config('sso.broker_code');
        $this->jwtSecret = config('sso.jwt_secret');

        // URL logout aplikasi INI — dikirim ke SSO server agar bisa memanggil balik
        // dan menghancurkan session local di browser user
        $protocol = ((new Request)->secure()) ? 'https' : 'http';
        $this->logoutLinkSelf = $protocol.'://'.($_SERVER['HTTP_HOST'] ?? 'localhost').'/logout';
    }

    public function authenticateToSSO(Request $request, $authData = null)
    {
        // --- MOCK LOGIN SEMENTARA (Tanpa menghapus fitur SSO auth.badungkab.go.id) ---
        if (config('sso.mock')) {
            // Jika belum ada parameter pilihan akun/role/instansi, tampilkan halaman pemilihan akun Mock
            if (! $request->hasAny(['user_id', 'username', 'role', 'pd_id'])) {
                $users = User::with('perangkatDaerah')
                    ->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'evaluator' THEN 2 WHEN 'perangkat_daerah' THEN 3 ELSE 4 END")
                    ->orderBy('name')
                    ->get();
                $perangkatDaerahs = PerangkatDaerah::with('users')->orderBy('nama_instansi')->get();

                return view('auth.mock-login', compact('users', 'perangkatDaerahs'));
            }

            $role = $request->query('role', 'admin');
            $user = null;

            if ($request->filled('user_id')) {
                $user = User::find($request->query('user_id'));
            } elseif ($request->filled('username')) {
                $user = User::where('username', $request->query('username'))->first();
            } elseif ($request->filled('pd_id')) {
                $pd = PerangkatDaerah::find($request->query('pd_id'));
                if ($pd) {
                    $user = User::where('perangkat_daerah_id', $pd->id)->first();
                    if (! $user) {
                        $user = User::create([
                            'name' => 'Admin '.$pd->nama_instansi,
                            'username' => 'pd_'.$pd->id,
                            'email' => 'pd_'.$pd->id.'@badungkab.go.id',
                            'password' => 'password',
                            'role' => 'perangkat_daerah',
                            'perangkat_daerah_id' => $pd->id,
                        ]);
                    }
                }
            } else {
                $user = User::where('role', $role)->first() ?? User::first();
            }

            if (! $user) {
                $defaultPdId = $role === 'perangkat_daerah' ? PerangkatDaerah::value('id') : null;
                $user = User::create([
                    'name' => 'Mock '.ucfirst(str_replace('_', ' ', $role)),
                    'username' => 'mock_'.$role,
                    'email' => 'mock_'.$role.'@badungkab.go.id',
                    'password' => 'password',
                    'role' => in_array($role, ['admin', 'evaluator', 'perangkat_daerah', 'viewer']) ? $role : 'admin',
                    'perangkat_daerah_id' => $defaultPdId,
                ]);
            }

            Auth::login($user);
            session([
                'UserIsAuthenticated' => 1,
                'authUserData' => (object) [
                    'user' => (object) [
                        'nip' => $user->nip,
                        'username' => $user->username ?? $user->email,
                        'name' => $user->name,
                    ],
                ],
            ]);

            $redirectUrl = session('urlToRedirect') ?? url('/admin');
            session()->forget('urlToRedirect');

            return redirect($redirectUrl);
        }

        Log::info('--- SSO START ---');
        Log::info('URL Dipanggil: '.$request->fullUrl());
        Log::info('Semua Data Parameter Request: ', $request->all());

        // Coba tangkap dari berbagai kemungkinan nama parameter
        if (empty($authData)) {
            $authData = $request->query('token') ?? $request->query('authData') ?? $request->query('jwt') ?? $request->query('code');
        }

        Log::info('Token JWT (authData): '.($authData ?? 'KOSONG (Meminta Token Baru)'));

        // Fase 1: Request dialihkan ke Server SSO form login
        if (empty($authData)) {
            $jwt = new JWT;

            // HARDCODE MUTLAK domain production, jangan andalkan .env karena sering nyangkut http://
            $appDomain = 'https://evaluasi-sakip.badungkab.go.id';

            // Format Payload WAJIB mengikuti standar SSO Badung (Siprobis):
            $payload = [
                'redirect' => $appDomain.'/authenticateToSSO',
                'urlToRedirect' => session('urlToRedirect') ?? $appDomain.'/admin',
                // pisahkan callback SSO server ke endpoint khusus yang HANYA membalas 200 OK
                // tanpa melakukan redirect, sehingga tidak ada infinite loop!
                'logoutLink' => $appDomain.'/sso_callback_logout',
                'kode_broker' => $this->brokerCode,
                'sessionRequest' => session()->getId(),
            ];

            $jwt->setPayloadJWT($payload);
            $token = $jwt->encodeJWT();

            return redirect($this->ssoDomain.'authBroker/'.$token);
        }

        // Fase 2: Menangkap dan dan Memverifikasi Balikan (Callback) Token
        try {
            $client = new Client;
            $response = $client->request('POST', $this->ssoDomain.'api/v1/auth/jwt/verify', [
                'form_params' => [
                    'token' => $authData,
                ],
            ]);

            $result = json_decode($response->getBody()->getContents());
            Log::info('Hasil API Verify SSO: '.json_encode($result));

            // Server SSO Badung mengembalikan format: { status: true, data: "jwt_string", message: "..." }
            if (isset($result->status) && $result->status == true && ! empty($result->data)) {
                $jwt = new JWT;
                // Siprobis menggunakan $result->data untuk token balasan
                $jwt->setJWTString($result->data);
                $decoded = $jwt->decodeJWT();

                Log::info('Token Decoded dari SSO: ', (array) $decoded);
                Log::info('Session Peminta Awal (dari token): '.($decoded->sessionRequest ?? 'TIDAK ADA'));
                Log::info('Session Saat Ini (di browser): '.session()->getId());

                // Re-validasi keamanan: pastikan session ID sesuai dengan si peminta awal
                if ($decoded && isset($decoded->sessionRequest) && $decoded->sessionRequest === session()->getId()) {

                    // Pada standar Badung SSO, seluruh isi payload adalah data user itu sendiri, namun detail spesifik ada di dalam property ->user
                    $userData = $decoded;

                    if (isset($userData->user)) {
                        $user = null;

                        // 1. Prioritas NIP
                        if (! empty($userData->user->nip)) {
                            $user = User::where('nip', $userData->user->nip)->first();
                        }

                        // 2. Fallback Username
                        if (! $user && ! empty($userData->user->username)) {
                            $user = User::where('username', $userData->user->username)->first();
                        }

                        if ($user) {
                            $oldSessionId = $decoded->sessionRequest;

                            // Bangun Sesi Lokal Laravel & Filament (Ini meregenerate Session ID!)
                            Auth::login($user);

                            $newSessionId = session()->getId();

                            // JEMBATAN SESI: Simpan pemetaan ID Sesi Lama (yg dipegang SSO Server)
                            // ke ID Sesi Baru (yg digunakan local Laravel User) ke Cache.
                            if ($oldSessionId !== $newSessionId) {
                                Cache::put('sso_mapped_session_'.$oldSessionId, $newSessionId, now()->addDays(3));
                            }

                            session(['UserIsAuthenticated' => 1]);
                            session(['authUserData' => $userData]);

                            $redirectUrl = session('urlToRedirect') ?? url('/admin');
                            session()->forget('urlToRedirect');

                            return redirect($redirectUrl);
                        } else {
                            $identifier = $userData->user->nip ?? ($userData->user->username ?? 'Unknown');

                            return response("Akses Ditolak. Akun Anda ({$identifier}) valid di SSO namun tidak terdaftar pada Sistem Evaluasi SAKIP.", 403);
                        }
                    } else {
                        return response('Akses Ditolak. Struktur profil user (SSO) tidak dikenali.', 403);
                    }
                } else {
                    Log::error('VALIDASI GAGAL: Session ID Mismatch! (Session awal vs sekarang berbeda) atau token rusak.');

                    return response('SSO Verification Failed. Invalid Session Mismatch.', 401);
                }
            } else {
                Log::error('API Verify Merespons bahwa Token Tidak Valid / Success: False');
            }

            Log::error('SSO Verification Failed. Returning 401.');

            return response('SSO Verification Failed. API Verify response is empty or status is false.', 401);

        } catch (ClientException $e) {
            return response('Error validasi (4xx) saat menghubungi server SSO: '.$e->getMessage(), 400);
        } catch (ServerException $e) {
            return response('Error server (5xx) saat menghubungi server SSO: '.$e->getMessage(), 500);
        } catch (\Exception $e) {
            return response('Terjadi kesalahan fatal saat validasi token SSO: '.$e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        Log::info('===== LOGOUT ROUTE DIPANGGIL =====');
        Log::info('Referer: '.$request->headers->get('referer'));
        Log::info('User-Agent: '.$request->headers->get('user-agent'));
        Log::info('Parameter sessionId: '.$request->sessionId);

        // ================================================================
        // SKENARIO A: Callback backward compatibility (untuk session yang lama)
        // Dipanggil dari auth.badungkab.go.id saat proses global logout.
        // ================================================================
        if ($request->sessionId) {
            $oldSessionId = $request->sessionId;
            Log::info('Skenario A terpilih. Menghancurkan sesi (Old ID): '.$oldSessionId);
            Session::getHandler()->destroy($oldSessionId);

            // JEMBATAN SESI
            $mappedNewSessionId = Cache::get('sso_mapped_session_'.$oldSessionId);
            if ($mappedNewSessionId) {
                Log::info('Jembatan Sesi Ditemukan! Menghancurkan Session Baru: '.$mappedNewSessionId);
                Session::getHandler()->destroy($mappedNewSessionId);
                Cache::forget('sso_mapped_session_'.$oldSessionId);
            }
            Log::info('Berhasil destroy session handler.');
        }

        // Hancurkan session lokal
        Log::info('Persiapan memanggil Session::flush() & Auth::logout() ...');
        Session::flush();
        Auth::logout();
        Log::info('Session & Auth flush KELAR.');

        // KARENA AppServiceProvider mengurus redirect Filament User, SEMUA
        // request yang masuk ke endpoint ini adalah murni PING DARI SSO SERVER.
        // Cukup balas dengan 200 OK agar SSO server tidak macet (timeout).
        Log::info('Return response 200 OK.');

        return response('OK', 200);
    }

    public function ssoCallbackLogout(Request $request)
    {
        Log::info('===== SSO_CALLBACK_LOGOUT DIPANGGIL =====');
        Log::info('Parameter sessionId: '.$request->sessionId);

        // ================================================================
        // SKENARIO C: Callback murni dari SSO Server
        // Endpoint HANYA dipanggil oleh auth.badungkab.go.id (iframe/curl)
        // ================================================================
        if ($request->sessionId) {
            $oldSessionId = $request->sessionId;
            Log::info('Menghancurkan Handler Session SSO (Old ID): '.$oldSessionId);
            Session::getHandler()->destroy($oldSessionId);

            // JEMBATAN SESI: Cari tahu apakah Sesi ID ini sudah di-regenerate oleh Laravel Auth
            $mappedNewSessionId = Cache::get('sso_mapped_session_'.$oldSessionId);
            if ($mappedNewSessionId) {
                Log::info('Jembatan Sesi Ditemukan! Mengancurkan Session ID Baru: '.$mappedNewSessionId);
                Session::getHandler()->destroy($mappedNewSessionId);

                // Bersihkan cache mapping
                Cache::forget('sso_mapped_session_'.$oldSessionId);
            } else {
                Log::info('Tidak ada pemetaan sesi baru ditemukan di Cache.');
            }
        }

        Log::info('Mengeksekusi Session::flush()...');
        Session::flush();
        Auth::logout();
        Log::info('Kelar flushing. Mengembalikan teks 200 OK.');

        // JANGAN prnah redirect ke manapun dari function ini!
        // Hanya respons OK saja!
        return response('OK', 200);
    }
}
