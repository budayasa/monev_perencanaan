@if(config('sso.mock') && auth()->check())
    @php
        $currentUser = auth()->user();
        $roleLabel = match($currentUser->role) {
            'admin' => 'ADMIN BAPPEDA',
            'evaluator' => 'VERIFIKATOR BAPPEDA',
            'perangkat_daerah' => 'OPD: ' . \Illuminate\Support\Str::limit($currentUser->perangkatDaerah->singkatan ?? $currentUser->perangkatDaerah->nama_instansi ?? $currentUser->name, 20),
            'viewer' => 'PIMPINAN / VIEWER',
            default => strtoupper($currentUser->role),
        };
    @endphp
    <div class="flex items-center gap-1.5 mr-2">
        <a href="{{ route('sso.auth') }}"
            title="Klik untuk ganti akun Mock (Admin Bappeda / Verifikator / 37 OPD / Pimpinan)"
            style="background: rgba(255, 102, 0, 0.95); color: #ffffff; border: 1px solid rgba(255,255,255,0.45);"
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold tracking-wide shadow-sm hover:brightness-110 transition">
            <span>⇄ Ganti Role/OPD ({{ $roleLabel }})</span>
        </a>
    </div>
@endif
