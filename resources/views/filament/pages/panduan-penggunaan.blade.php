<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #005000 0%, #166534 100%); border-bottom: 4px solid #FF6600;">
            <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider" style="background: #FF6600;">
                Pedoman Permendagri Nomor 86 Tahun 2017
            </span>
            <h2 class="text-xl font-black mt-2">
                Panduan Alur Berjenjang (Cascading) & Pembagian Tugas 4 Role Utama
            </h2>
            <p class="text-xs text-emerald-100 mt-1">
                Sistem Pengendalian dan Evaluasi Perencanaan Pembangunan Daerah Kabupaten Badung (RPJMD → RKPD → Renstra → Renja → Monev Triwulan I–IV)
            </p>
        </div>

        {{-- Skala Predikat Permendagri 86/2017 --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h3 class="text-sm font-black uppercase text-slate-900 mb-3">
                1. Standar Perhitungan & Kategori Predikat Capaian Kinerja (Permendagri 86/2017)
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200">
                    <span class="font-black text-emerald-800 text-sm">Sangat Tinggi (ST)</span>
                    <p class="font-bold text-slate-700 mt-1">Capaian > 90%</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Kinerja & serapan sangat memuaskan sesuai target</p>
                </div>
                <div class="p-3.5 rounded-xl bg-sky-50 border border-sky-200">
                    <span class="font-black text-sky-800 text-sm">Tinggi (T)</span>
                    <p class="font-bold text-slate-700 mt-1">75,01% s/d 90%</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Kinerja tinggi dan berada pada jalur on-track</p>
                </div>
                <div class="p-3.5 rounded-xl bg-yellow-50 border border-yellow-200">
                    <span class="font-black text-yellow-800 text-sm">Sedang (S)</span>
                    <p class="font-bold text-slate-700 mt-1">65,01% s/d 75%</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Perlu akselerasi pada triwulan berikutnya</p>
                </div>
                <div class="p-3.5 rounded-xl bg-orange-50 border border-orange-200">
                    <span class="font-black text-orange-800 text-sm">Rendah (R)</span>
                    <p class="font-bold text-slate-700 mt-1">50,01% s/d 65%</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Butuh perhatian khusus & evaluasi faktor penghambat</p>
                </div>
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200">
                    <span class="font-black text-rose-800 text-sm">Sangat Rendah (SR)</span>
                    <p class="font-bold text-slate-700 mt-1">Capaian ≤ 50%</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Wajib desk evaluasi & intervensi tindak lanjut segera</p>
                </div>
            </div>
        </div>

        {{-- Panduan Per Role --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-[#005000] text-white text-xs font-extrabold">ROLE 1</span>
                    <h4 class="font-black text-slate-900 text-sm">Admin (Bappeda Kabupaten Badung)</h4>
                </div>
                <ul class="list-disc pl-5 text-xs text-slate-700 space-y-1.5">
                    <li>Mengelola Master Data Level Kabupaten: <strong>1. RPJMD (5 Tahunan)</strong> dan <strong>2. RKPD (Tahunan)</strong> beserta fitur Import/Export CSV.</li>
                    <li>Mengatur <strong>Jadwal Buka-Tutup Pengisian per Triwulan (TW I, II, III, IV)</strong> yang otomatis mengunci pengisian OPD di backend bila melewati batas waktu.</li>
                    <li>Mengelola Master <strong>37 Perangkat Daerah (OPD)</strong>, Manajemen User SSO, dan memantau <strong>Log Aktivitas & Audit Troubleshooting</strong>.</li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-[#FF6600] text-white text-xs font-extrabold">ROLE 2</span>
                    <h4 class="font-black text-slate-900 text-sm">Evaluator / Verifikator Bappeda (Bidang Perencanaan / Monev)</h4>
                </div>
                <ul class="list-disc pl-5 text-xs text-slate-700 space-y-1.5">
                    <li>Memeriksa keselarasan dokumen berjenjang: <strong>Renstra ↔ RPJMD</strong> dan <strong>Renja ↔ RKPD</strong> pada seluruh 37 OPD.</li>
                    <li>Memverifikasi laporan realisasi fisik & keuangan per Triwulan pada menu <strong>Monev Triwulan I–IV</strong>, memberikan catatan revisi, atau menetapkan status <em>Verified / Final</em>.</li>
                    <li>Menerbitkan <strong>Berita Acara & Cetakan Resmi A4 (PDF / Word .docx)</strong> sesuai format Lampiran Permendagri 86/2017.</li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-blue-600 text-white text-xs font-extrabold">ROLE 3</span>
                    <h4 class="font-black text-slate-900 text-sm">Perangkat Daerah (37 OPD Kabupaten Badung)</h4>
                </div>
                <ul class="list-disc pl-5 text-xs text-slate-700 space-y-1.5">
                    <li>Mengisi dan memutakhirkan dokumen <strong>3. Renstra PD (5 Tahunan)</strong> dan <strong>4. Renja PD (Program → Kegiatan → Sub-Kegiatan SIPD)</strong>.</li>
                    <li>Menginput realisasi kinerja fisik, serapan anggaran (Rp), faktor pendorong, faktor penghambat, tindak lanjut, serta link bukti dukung setiap Triwulan.</li>
                    <li>Mengirim laporan Triwulan ke Bappeda sebelum batas jadwal berakhir dan mengunduh <strong>Jurnal Kinerja Harian</strong>.</li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-purple-700 text-white text-xs font-extrabold">ROLE 4</span>
                    <h4 class="font-black text-slate-900 text-sm">Pimpinan / Viewer (Eksekutif Read-Only)</h4>
                </div>
                <ul class="list-disc pl-5 text-xs text-slate-700 space-y-1.5">
                    <li>Memantau <strong>Dashboard Eksekutif Kabupaten Badung</strong> dan tabel peringkat capaian kinerja fisik & serapan anggaran seluruh 37 OPD secara real-time.</li>
                    <li>Menggunakan fitur <strong>✨ AI Executive Summary</strong> dan <strong>✨ AI Analisis Faktor Penghambat</strong> untuk bahan pengambilan keputusan pimpinan.</li>
                </ul>
            </div>
        </div>
    </div>
</x-filament-panels::page>
