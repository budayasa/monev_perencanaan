<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl p-5 text-white shadow-lg" style="background: linear-gradient(135deg, #005000 0%, #166534 100%); border-bottom: 4px solid #FF6600;">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-black uppercase">Log Aktivitas & Audit Troubleshooting Sistem E-Monev</h2>
                    <p class="text-xs text-emerald-100">Rekam jejak otomatis perubahan data RPJMD, RKPD, Renstra, Renja, Pengisian Realisasi Triwulan & Verifikasi Bappeda ({{ $totalLogs }} aktivitas tercatat)</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <input type="text" wire:model.live.debounce.250ms="search" placeholder="Cari aktivitas / modul..."
                        class="rounded-xl text-xs text-slate-900 px-3.5 py-2 border-0">
                    <select wire:model.live="filterEvent" class="rounded-xl text-xs font-bold text-slate-900 px-3 py-2 border-0">
                        <option value="semua">Semua Event</option>
                        <option value="created">Created</option>
                        <option value="updated">Updated</option>
                        <option value="submitted_monev">Submitted Monev</option>
                        <option value="verified_monev">Verified Bappeda</option>
                        <option value="generated_ai">AI Executive Summary</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="text-white uppercase text-[11px]" style="background: #005000;">
                            <th class="py-3 px-4">Waktu (WITA)</th>
                            <th class="py-3 px-4">Pelaku (User / Role)</th>
                            <th class="py-3 px-4">Modul / Objek</th>
                            <th class="py-3 px-4">Event</th>
                            <th class="py-3 px-4">Deskripsi Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50">
                                <td class="py-2.5 px-4 font-mono text-[11px] text-slate-600 whitespace-nowrap">
                                    {{ $log->created_at?->format('d M Y, H:i:s') }}
                                </td>
                                <td class="py-2.5 px-4 font-bold text-slate-800">
                                    {{ $log->causer?->name ?? 'Sistem / Seeder' }}
                                    @if($log->causer?->role)
                                        <span class="block text-[10px] font-normal text-slate-500 uppercase">{{ $log->causer->role }}</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 font-mono text-slate-700">
                                    {{ class_basename((string) $log->subject_type) ?: $log->log_name }}
                                </td>
                                <td class="py-2.5 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase">
                                        {{ $log->event ?? 'log' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-slate-700">
                                    {{ $log->description }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-500">Belum ada log aktivitas yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
