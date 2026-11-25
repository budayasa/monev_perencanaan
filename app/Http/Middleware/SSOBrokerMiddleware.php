<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SSOBrokerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Pengecekan sesi bawaan SSO dan autentikasi Filament (Laravel Auth)
        if (! session('UserIsAuthenticated') || ! Auth::check()) {
            // Menyimpan tujuan URL user sebelum dilarikan ke SSO
            session(['urlToRedirect' => $request->url()]);

            // JIKA server SSO ternyata melempar balik token ke halaman yang sedang dilindungi ini,
            // (misal via ?token= atau ?authData=), kita selamatkan tokennya dan bawa ke sso.auth!
            $ssoParams = [];
            if ($request->has('token')) {
                $ssoParams['token'] = $request->query('token');
            }
            if ($request->has('authData')) {
                $ssoParams['authData'] = $request->query('authData');
            }
            if ($request->has('jwt')) {
                $ssoParams['jwt'] = $request->query('jwt');
            }
            if ($request->has('code')) {
                $ssoParams['code'] = $request->query('code');
            }

            // Lempar ke SSO (bersama token jika ada)
            return redirect()->route('sso.auth', $ssoParams);
        }

        return $next($request);
    }
}
