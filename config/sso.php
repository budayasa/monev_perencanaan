<?php

return [
    // Default WAJIB false agar di Server Production/Live tetap memakai SSO auth.badungkab.go.id.
    // Di komputer lokal, aktifkan lewat SSO_MOCK=true di dalam file .env (file .env tidak ikut ter-push ke GitHub).
    'mock' => (bool) env('SSO_MOCK', false) && in_array(env('APP_ENV', 'production'), ['local', 'testing']),
    'domain' => env('SSO_DOMAIN', 'https://auth.badungkab.go.id'),
    'broker_code' => env('BROKER_CODE', '779522'),
    'jwt_secret' => env('JWT_SECRET', 'secret_sso_sakip'),
];
