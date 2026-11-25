<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Login — E-Monev Perencanaan Kabupaten Badung</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
        }
    </style>
</head>

<body class="min-h-screen text-slate-800 pb-16">
    <!-- Top Header -->
    <header class="bg-[#005000] text-white shadow-md sticky top-0 z-30 border-b-4 border-[#FF6600]">
        <div class="max-w-6xl mx-auto px-6 py-4 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-badung.png') }}" alt="Logo Badung" class="h-11 w-auto bg-white p-1 rounded-lg">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="font-extrabold text-lg tracking-wide uppercase">Portal Mock Login E-Monev Perencanaan</h1>
                        <span class="bg-[#FF6600] text-white text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Permendagri 86/2017</span>
                    </div>
                    <p class="text-xs text-emerald-100">Pilih Role (Admin Bappeda, Verifikator Bappeda, 37 OPD, atau Pimpinan) tanpa SSO auth.badungkab.go.id</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold px-3.5 py-2 rounded-lg border border-emerald-500 transition">
                        <i class="bi bi-speedometer2"></i>
                        Kembali ke Dashboard ({{ auth()->user()->name }})
                    </a>
                @endauth
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-3.5 py-2 rounded-lg transition">
                    <i class="bi bi-house-door"></i>
                    Beranda
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 mt-8 space-y-8">
        <!-- Quick Role Login (4 Role Utama) -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 uppercase tracking-tight">1. Login Cepat Berdasarkan 4 Role Utama</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Langsung masuk menggunakan akun representatif pada masing-masing role Monev Perencanaan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Admin Bappeda -->
                <a href="{{ route('sso.auth', ['role' => 'admin']) }}"
                    class="group flex items-center gap-3.5 p-4 rounded-xl border-2 border-emerald-100 bg-emerald-50/40 hover:bg-[#005000] hover:border-[#005000] transition-all">
                    <div class="w-11 h-11 rounded-xl bg-[#005000] text-white group-hover:bg-white group-hover:text-[#005000] flex items-center justify-center text-xl font-bold shadow-sm transition">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 group-hover:text-emerald-100">Admin (Bappeda)</span>
                        <h3 class="font-extrabold text-slate-900 group-hover:text-white text-sm truncate">Masuk Admin Bappeda</h3>
                        <p class="text-[11px] text-slate-500 group-hover:text-emerald-100 truncate">Master RPJMD, RKPD, Jadwal TW & User</p>
                    </div>
                </a>

                <!-- Evaluator / Verifikator Bappeda -->
                <a href="{{ route('sso.auth', ['role' => 'evaluator']) }}"
                    class="group flex items-center gap-3.5 p-4 rounded-xl border-2 border-orange-100 bg-orange-50/40 hover:bg-[#FF6600] hover:border-[#FF6600] transition-all">
                    <div class="w-11 h-11 rounded-xl bg-[#FF6600] text-white group-hover:bg-white group-hover:text-[#FF6600] flex items-center justify-center text-xl font-bold shadow-sm transition">
                        <i class="bi bi-clipboard2-check-fill"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-orange-700 group-hover:text-orange-100">Bidang Perencanaan / Monev</span>
                        <h3 class="font-extrabold text-slate-900 group-hover:text-white text-sm truncate">Verifikator Bappeda</h3>
                        <p class="text-[11px] text-slate-500 group-hover:text-orange-100 truncate">Cek keselarasan & verifikasi 37 OPD</p>
                    </div>
                </a>

                <!-- Perangkat Daerah Default -->
                <a href="{{ route('sso.auth', ['role' => 'perangkat_daerah']) }}"
                    class="group flex items-center gap-3.5 p-4 rounded-xl border-2 border-blue-100 bg-blue-50/40 hover:bg-blue-600 hover:border-blue-600 transition-all">
                    <div class="w-11 h-11 rounded-xl bg-blue-600 text-white group-hover:bg-white group-hover:text-blue-700 flex items-center justify-center text-xl font-bold shadow-sm transition">
                        <i class="bi bi-building-fill"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 group-hover:text-blue-100">Perangkat Daerah (37 OPD)</span>
                        <h3 class="font-extrabold text-slate-900 group-hover:text-white text-sm truncate">Operator OPD (Default)</h3>
                        <p class="text-[11px] text-slate-500 group-hover:text-blue-100 truncate">Input Renstra, Renja & Realisasi TW</p>
                    </div>
                </a>

                <!-- Pimpinan / Viewer -->
                <a href="{{ route('sso.auth', ['role' => 'viewer']) }}"
                    class="group flex items-center gap-3.5 p-4 rounded-xl border-2 border-purple-100 bg-purple-50/40 hover:bg-purple-700 hover:border-purple-700 transition-all">
                    <div class="w-11 h-11 rounded-xl bg-purple-700 text-white group-hover:bg-white group-hover:text-purple-700 flex items-center justify-center text-xl font-bold shadow-sm transition">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-purple-700 group-hover:text-purple-100">Pimpinan / Viewer</span>
                        <h3 class="font-extrabold text-slate-900 group-hover:text-white text-sm truncate">Eksekutif (Read-Only)</h3>
                        <p class="text-[11px] text-slate-500 group-hover:text-purple-100 truncate">Dashboard Eksekutif & Ranking OPD</p>
                    </div>
                </a>
            </div>
        </section>

        <!-- Pilih Perangkat Daerah Spesifik -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 uppercase tracking-tight">
                        2. Pilih Perangkat Daerah Spesifik ({{ $perangkatDaerahs->count() }} OPD Kabupaten Badung)
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Klik salah satu Perangkat Daerah untuk masuk sebagai Pejabat Perencana / Operator Renja & Monev OPD tersebut
                    </p>
                </div>
                <div class="relative w-full sm:w-80">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="search-pd" placeholder="Cari nama dinas / badan / kecamatan..."
                        class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-slate-300 focus:border-[#005000] focus:ring-2 focus:ring-emerald-500/20 outline-none">
                </div>
            </div>

            <div id="pd-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 max-h-[420px] overflow-y-auto pr-1">
                @foreach($perangkatDaerahs as $pd)
                    @php
                        $pdUser = $pd->users->where('role', 'perangkat_daerah')->first() ?? $pd->users->first();
                    @endphp
                    <a href="{{ route('sso.auth', ['pd_id' => $pd->id]) }}"
                        data-name="{{ strtolower($pd->nama_instansi . ' ' . ($pd->singkatan ?? '') . ' ' . ($pdUser->username ?? '')) }}"
                        class="pd-item group flex items-center justify-between gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-[#005000] hover:bg-emerald-50/50 transition">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600 group-hover:bg-[#005000] group-hover:text-white transition">
                                    #{{ $pd->id }} • {{ $pd->kode_opd ?? 'OPD' }}
                                </span>
                                @if($pdUser)
                                    <span class="text-[11px] text-emerald-700 font-medium truncate">
                                        <i class="bi bi-person-check-fill"></i> {{ $pdUser->username }}
                                    </span>
                                @endif
                            </div>
                            <p class="font-bold text-xs text-slate-800 group-hover:text-[#005000] mt-1.5 leading-snug">
                                {{ $pd->nama_instansi }}
                            </p>
                        </div>
                        <i class="bi bi-box-arrow-in-right text-slate-400 group-hover:text-[#005000] text-lg"></i>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Daftar Semua Akun User Terdaftar -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 uppercase tracking-tight">
                        3. Daftar Akun User di Database ({{ $users->count() }} User)
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Masuk menggunakan akun user spesifik (Admin Bappeda, Verifikator Bappeda, Operator OPD, atau Pimpinan)
                    </p>
                </div>
                <div class="relative w-full sm:w-80">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="search-user" placeholder="Cari nama, username, NIP, atau role..."
                        class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-slate-300 focus:border-[#005000] focus:ring-2 focus:ring-emerald-500/20 outline-none">
                </div>
            </div>

            <div class="overflow-x-auto max-h-96 overflow-y-auto border border-slate-200 rounded-xl">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-50 sticky top-0 border-b border-slate-200 text-slate-600 uppercase">
                        <tr>
                            <th class="py-3 px-4 font-bold">Nama / Jabatan</th>
                            <th class="py-3 px-4 font-bold">Username / NIP</th>
                            <th class="py-3 px-4 font-bold">Role</th>
                            <th class="py-3 px-4 font-bold">Perangkat Daerah</th>
                            <th class="py-3 px-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body" class="divide-y divide-slate-100">
                        @foreach($users as $u)
                            @php
                                $badgeClass = match($u->role) {
                                    'admin' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'evaluator' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    'perangkat_daerah' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    default => 'bg-purple-100 text-purple-800 border-purple-200',
                                };
                            @endphp
                            <tr class="user-row hover:bg-slate-50 transition"
                                data-search="{{ strtolower($u->name . ' ' . $u->username . ' ' . $u->nip . ' ' . $u->role . ' ' . ($u->perangkatDaerah->nama_instansi ?? '')) }}">
                                <td class="py-2.5 px-4 font-bold text-slate-800">
                                    {{ $u->name }}
                                    @if($u->nama_pejabat)
                                        <div class="text-[11px] font-normal text-slate-500">{{ $u->nama_pejabat }}</div>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 text-slate-600">
                                    <span class="font-mono font-semibold">{{ $u->username ?? $u->email }}</span>
                                    @if($u->nip)
                                        <div class="text-[10px] text-slate-400">NIP: {{ $u->nip }}</div>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $badgeClass }}">
                                        {{ str_replace('_', ' ', $u->role) }}
                                    </span>
                                    @if($u->jenis_evaluator)
                                        <div class="text-[10px] text-slate-500 mt-0.5">{{ $u->jenis_evaluator }}</div>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 text-slate-600">
                                    {{ $u->perangkatDaerah->nama_instansi ?? '—' }}
                                </td>
                                <td class="py-2.5 px-4 text-right">
                                    <a href="{{ route('sso.auth', ['user_id' => $u->id]) }}"
                                        class="inline-flex items-center gap-1.5 bg-[#005000] hover:bg-emerald-800 text-white font-bold px-3 py-1.5 rounded-lg transition">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                        Login
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        document.getElementById('search-pd')?.addEventListener('input', function (e) {
            const q = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.pd-item').forEach(el => {
                el.style.display = el.dataset.name.includes(q) ? '' : 'none';
            });
        });

        document.getElementById('search-user')?.addEventListener('input', function (e) {
            const q = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.user-row').forEach(el => {
                el.style.display = el.dataset.search.includes(q) ? '' : 'none';
            });
        });
    </script>
</body>

</html>
