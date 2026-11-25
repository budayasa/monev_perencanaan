<?php

namespace App\Filament\Pages;

use App\Models\MasterKegiatan;
use App\Models\MasterProgram;
use App\Models\MasterSubKegiatan;
use App\Models\Satuan;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class MasterNomenklatur extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Master Program, Kegiatan & Sub';

    protected static ?string $title = 'Master Program, Kegiatan & Sub Kegiatan (Format Permendagri)';

    protected static ?string $navigationGroup = 'Pengaturan & Master';

    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.pages.master-nomenklatur';

    public string $search = '';

    public string $filterUrusan = '';

    public string $filterBidang = '';

    public string $filterSpm = '';

    public int $page = 1;

    public int $perPage = 10;

    // Modal: null | program | kegiatan | sub_kegiatan | import_csv
    public ?string $activeModal = null;

    public ?int $editingId = null;

    public ?int $parentId = null;

    public string $parentLabel = '';

    public array $form = [];

    public $csvFile = null;

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator', 'perangkat_daerah', 'viewer']);
    }

    public function canManage(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator']);
    }

    public function canDeleteData(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public function updatedSearch(): void
    {
        $this->page = 1;
    }

    public function updatedFilterUrusan(): void
    {
        $this->filterBidang = '';
        $this->page = 1;
    }

    public function updatedFilterBidang(): void
    {
        $this->page = 1;
    }

    public function updatedFilterSpm(): void
    {
        $this->page = 1;
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterUrusan = '';
        $this->filterBidang = '';
        $this->filterSpm = '';
        $this->page = 1;
    }

    public function prevPage(): void
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function nextPage(int $lastPage): void
    {
        if ($this->page < $lastPage) {
            $this->page++;
        }
    }

    public function closeModal(): void
    {
        $this->activeModal = null;
        $this->editingId = null;
        $this->parentId = null;
        $this->parentLabel = '';
        $this->form = [];
        $this->csvFile = null;
        $this->resetValidation();
    }

    // ==================== 1. PROGRAM ====================
    public function openProgramModal(?int $id = null): void
    {
        if (! $this->canManage()) {
            return;
        }

        $prog = $id ? MasterProgram::find($id) : null;
        $this->activeModal = 'program';
        $this->editingId = $prog?->id;
        $this->form = [
            'kode_urusan' => $prog?->kode_urusan ?? '',
            'nama_urusan' => $prog?->nama_urusan ?? '',
            'kode_bidang_urusan' => $prog?->kode_bidang_urusan ?? '',
            'nama_bidang_urusan' => $prog?->nama_bidang_urusan ?? '',
            'kode_program' => $prog?->kode_program ?? '',
            'nama_program' => $prog?->nama_program ?? '',
            'indikator_program' => $prog?->indikator_program ?? '',
            'satuan' => $prog?->satuan ?? '%',
        ];
    }

    public function saveProgram(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.kode_program' => 'required|string|max:50',
            'form.nama_program' => 'required|string|min:3',
            'form.satuan' => 'required|string|max:80',
        ]);

        $kode = trim((string) $this->form['kode_program']);
        $nama = trim((string) $this->form['nama_program']);

        $duplikatKode = MasterProgram::whereRaw('LOWER(TRIM(kode_program)) = ?', [mb_strtolower($kode)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatKode) {
            $this->addError('form.kode_program', "Kode Program \"{$kode}\" sudah terdaftar di Master Program.");
            Notification::make()->title('Gagal: Kode Program sudah ada!')->danger()->send();

            return;
        }

        $duplikatNama = MasterProgram::whereRaw('LOWER(TRIM(nama_program)) = ?', [mb_strtolower($nama)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatNama) {
            $this->addError('form.nama_program', "Nama Program \"{$nama}\" sudah terdaftar di Master Program.");
            Notification::make()->title('Gagal: Nama Program sudah ada!')->danger()->send();

            return;
        }

        MasterProgram::updateOrCreate(
            ['id' => $this->editingId],
            [
                'kode_urusan' => trim((string) ($this->form['kode_urusan'] ?? '')) ?: null,
                'nama_urusan' => trim((string) ($this->form['nama_urusan'] ?? '')) ?: null,
                'kode_bidang_urusan' => trim((string) ($this->form['kode_bidang_urusan'] ?? '')) ?: null,
                'nama_bidang_urusan' => trim((string) ($this->form['nama_bidang_urusan'] ?? '')) ?: null,
                'kode_program' => $kode,
                'nama_program' => $nama,
                'indikator_program' => trim((string) ($this->form['indikator_program'] ?? '')) ?: null,
                'satuan' => trim((string) $this->form['satuan']),
            ]
        );

        Notification::make()->title('Master Program berhasil disimpan!')->success()->send();
        $this->closeModal();
    }

    public function deleteProgram(int $id): void
    {
        if (! $this->canDeleteData()) {
            return;
        }
        MasterProgram::whereKey($id)->delete();
        Notification::make()->title('Program beserta Kegiatan & Sub Kegiatannya dihapus.')->success()->send();
    }

    // ==================== 2. KEGIATAN ====================
    public function openKegiatanModal(int $programId, ?int $kegiatanId = null): void
    {
        if (! $this->canManage()) {
            return;
        }

        $prog = MasterProgram::find($programId);
        $keg = $kegiatanId ? MasterKegiatan::find($kegiatanId) : null;

        $this->activeModal = 'kegiatan';
        $this->parentId = $programId;
        $this->parentLabel = "[{$prog?->kode_program}] {$prog?->nama_program}";
        $this->editingId = $keg?->id;
        $this->form = [
            'kode_kegiatan' => $keg?->kode_kegiatan ?? ($prog?->kode_program ? $prog->kode_program.'.2.01' : ''),
            'nama_kegiatan' => $keg?->nama_kegiatan ?? '',
            'indikator_kegiatan' => $keg?->indikator_kegiatan ?? '',
            'satuan' => $keg?->satuan ?? 'Dokumen',
        ];
    }

    public function saveKegiatan(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.kode_kegiatan' => 'required|string|max:60',
            'form.nama_kegiatan' => 'required|string|min:3',
            'form.satuan' => 'required|string|max:80',
        ]);

        $kode = trim((string) $this->form['kode_kegiatan']);
        $nama = trim((string) $this->form['nama_kegiatan']);

        $duplikatKode = MasterKegiatan::whereRaw('LOWER(TRIM(kode_kegiatan)) = ?', [mb_strtolower($kode)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatKode) {
            $this->addError('form.kode_kegiatan', "Kode Kegiatan \"{$kode}\" sudah terdaftar.");
            Notification::make()->title('Gagal: Kode Kegiatan sudah ada!')->danger()->send();

            return;
        }

        $duplikatNama = MasterKegiatan::where('master_program_id', $this->parentId)
            ->whereRaw('LOWER(TRIM(nama_kegiatan)) = ?', [mb_strtolower($nama)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatNama) {
            $this->addError('form.nama_kegiatan', "Nama Kegiatan \"{$nama}\" sudah ada pada Program ini.");
            Notification::make()->title('Gagal: Nama Kegiatan sudah ada!')->danger()->send();

            return;
        }

        MasterKegiatan::updateOrCreate(
            ['id' => $this->editingId],
            [
                'master_program_id' => $this->parentId,
                'kode_kegiatan' => $kode,
                'nama_kegiatan' => $nama,
                'indikator_kegiatan' => trim((string) ($this->form['indikator_kegiatan'] ?? '')) ?: null,
                'satuan' => trim((string) $this->form['satuan']),
            ]
        );

        Notification::make()->title('Master Kegiatan berhasil disimpan!')->success()->send();
        $this->closeModal();
    }

    public function deleteKegiatan(int $id): void
    {
        if (! $this->canDeleteData()) {
            return;
        }
        MasterKegiatan::whereKey($id)->delete();
        Notification::make()->title('Kegiatan beserta Sub Kegiatannya dihapus.')->success()->send();
    }

    // ==================== 3. SUB KEGIATAN ====================
    public function openSubKegiatanModal(int $kegiatanId, ?int $subId = null): void
    {
        if (! $this->canManage()) {
            return;
        }

        $keg = MasterKegiatan::find($kegiatanId);
        $sub = $subId ? MasterSubKegiatan::find($subId) : null;

        $this->activeModal = 'sub_kegiatan';
        $this->parentId = $kegiatanId;
        $this->parentLabel = "[{$keg?->kode_kegiatan}] {$keg?->nama_kegiatan}";
        $this->editingId = $sub?->id;
        $this->form = [
            'kode_sub_kegiatan' => $sub?->kode_sub_kegiatan ?? ($keg?->kode_kegiatan ? $keg->kode_kegiatan.'.0001' : ''),
            'nama_sub_kegiatan' => $sub?->nama_sub_kegiatan ?? '',
            'kinerja' => $sub?->kinerja ?? '',
            'indikator_sub_kegiatan' => $sub?->indikator_sub_kegiatan ?? '',
            'satuan' => $sub?->satuan ?? 'Dokumen',
            'klasifikasi_belanja' => $sub?->klasifikasi_belanja ?? 'Belanja Operasi & Modal',
            'spm' => $sub?->spm ?? 'Non-SPM',
            'aktivitas_layanan' => $sub?->aktivitas_layanan ?? 'Layanan',
        ];
    }

    public function saveSubKegiatan(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.kode_sub_kegiatan' => 'required|string|max:80',
            'form.nama_sub_kegiatan' => 'required|string|min:3',
            'form.satuan' => 'required|string|max:80',
        ]);

        $kode = trim((string) $this->form['kode_sub_kegiatan']);
        $nama = trim((string) $this->form['nama_sub_kegiatan']);

        $duplikatKode = MasterSubKegiatan::whereRaw('LOWER(TRIM(kode_sub_kegiatan)) = ?', [mb_strtolower($kode)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatKode) {
            $this->addError('form.kode_sub_kegiatan', "Kode Sub Kegiatan \"{$kode}\" sudah terdaftar.");
            Notification::make()->title('Gagal: Kode Sub Kegiatan sudah ada!')->danger()->send();

            return;
        }

        $duplikatNama = MasterSubKegiatan::where('master_kegiatan_id', $this->parentId)
            ->whereRaw('LOWER(TRIM(nama_sub_kegiatan)) = ?', [mb_strtolower($nama)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatNama) {
            $this->addError('form.nama_sub_kegiatan', "Nama Sub Kegiatan \"{$nama}\" sudah ada pada Kegiatan ini.");
            Notification::make()->title('Gagal: Nama Sub Kegiatan sudah ada!')->danger()->send();

            return;
        }

        MasterSubKegiatan::updateOrCreate(
            ['id' => $this->editingId],
            [
                'master_kegiatan_id' => $this->parentId,
                'kode_sub_kegiatan' => $kode,
                'nama_sub_kegiatan' => $nama,
                'kinerja' => trim((string) ($this->form['kinerja'] ?? '')) ?: null,
                'indikator_sub_kegiatan' => trim((string) ($this->form['indikator_sub_kegiatan'] ?? '')) ?: null,
                'satuan' => trim((string) $this->form['satuan']),
                'klasifikasi_belanja' => trim((string) ($this->form['klasifikasi_belanja'] ?? '')) ?: null,
                'spm' => trim((string) ($this->form['spm'] ?? 'Non-SPM')) ?: 'Non-SPM',
                'aktivitas_layanan' => trim((string) ($this->form['aktivitas_layanan'] ?? '')) ?: null,
            ]
        );

        Notification::make()->title('Master Sub Kegiatan berhasil disimpan!')->success()->send();
        $this->closeModal();
    }

    public function deleteSubKegiatan(int $id): void
    {
        if (! $this->canDeleteData()) {
            return;
        }
        MasterSubKegiatan::whereKey($id)->delete();
        Notification::make()->title('Sub Kegiatan berhasil dihapus.')->success()->send();
    }

    // ==================== 4. IMPORT / EXPORT CSV FORMAT PERMENDAGRI ====================
    public function openImportModal(): void
    {
        if (! $this->canManage()) {
            return;
        }
        $this->activeModal = 'import_csv';
    }

    public function importCsv(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $this->csvFile->getRealPath();
        $sample = file_get_contents($path, false, null, 0, 2048) ?: '';
        $delimiter = substr_count($sample, ';') > substr_count($sample, ',') ? ';' : ',';

        $handle = fopen($path, 'r');
        if (! $handle) {
            return;
        }

        $now = now();
        $programs = [];
        $kegiatans = [];
        $subKegiatans = [];
        $uniqueSatuans = [];
        $count = 0;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($delimiter === ',') {
                // Format Nomenklatur RKPD / Permendagri (kolom 2 = Kode 6 segmen, misal 1.01.02.2.01.0001)
                $kode = trim((string) ($row[2] ?? ''));
                $parts = explode('.', $kode);
                if (count($parts) < 6) {
                    continue;
                }

                $kodeUrusan = $parts[0];
                $kodeBidang = $parts[0].'.'.$parts[1];
                $kodeProgram = $parts[0].'.'.$parts[1].'.'.$parts[2];
                $kodeKegiatan = $parts[0].'.'.$parts[1].'.'.$parts[2].'.'.$parts[3].'.'.$parts[4];
                $satuan = trim((string) ($row[10] ?? '')) ?: 'Dokumen';
                $spmRaw = trim((string) ($row[11] ?? ''));
                $spm = ($spmRaw === '' || $spmRaw === '-') ? 'Non-SPM' : $spmRaw;

                $uniqueSatuans[mb_strtolower($satuan)] = $satuan;

                $programs[$kodeProgram] ??= [
                    'kode_urusan' => $kodeUrusan,
                    'nama_urusan' => trim((string) ($row[3] ?? '')) ?: null,
                    'kode_bidang_urusan' => $kodeBidang,
                    'nama_bidang_urusan' => trim((string) ($row[4] ?? '')) ?: null,
                    'kode_program' => $kodeProgram,
                    'nama_program' => trim((string) ($row[5] ?? '')),
                    'satuan' => '%',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $kegiatans[$kodeKegiatan] ??= [
                    'kode_program' => $kodeProgram,
                    'kode_kegiatan' => $kodeKegiatan,
                    'nama_kegiatan' => trim((string) ($row[6] ?? '')),
                    'satuan' => 'Dokumen',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $subKegiatans[$kode] = [
                    'kode_kegiatan' => $kodeKegiatan,
                    'kode_sub_kegiatan' => $kode,
                    'nama_sub_kegiatan' => trim((string) ($row[7] ?? '')),
                    'kinerja' => trim((string) ($row[8] ?? '')) ?: null,
                    'indikator_sub_kegiatan' => trim((string) ($row[9] ?? '')) ?: null,
                    'satuan' => $satuan,
                    'spm' => $spm,
                    'aktivitas_layanan' => trim((string) ($row[12] ?? '')) ?: null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $count++;
            } else {
                // Format ekspor sistem (pemisah ;)
                if (empty($row[2]) || empty($row[3]) || strtolower(trim((string) $row[2])) === 'kode_program') {
                    continue;
                }

                $kodeProg = trim((string) $row[2]);
                $programs[$kodeProg] ??= [
                    'kode_urusan' => trim((string) ($row[0] ?? '')) ?: null,
                    'nama_urusan' => null,
                    'kode_bidang_urusan' => trim((string) ($row[1] ?? '')) ?: null,
                    'nama_bidang_urusan' => null,
                    'kode_program' => $kodeProg,
                    'nama_program' => trim((string) $row[3]),
                    'satuan' => trim((string) ($row[5] ?? '%')) ?: '%',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (! empty($row[6]) && ! empty($row[7])) {
                    $kodeKeg = trim((string) $row[6]);
                    $kegiatans[$kodeKeg] ??= [
                        'kode_program' => $kodeProg,
                        'kode_kegiatan' => $kodeKeg,
                        'nama_kegiatan' => trim((string) $row[7]),
                        'satuan' => trim((string) ($row[9] ?? 'Dokumen')) ?: 'Dokumen',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    if (! empty($row[10]) && ! empty($row[11])) {
                        $kodeSub = trim((string) $row[10]);
                        $satuan = trim((string) ($row[14] ?? 'Dokumen')) ?: 'Dokumen';
                        $uniqueSatuans[mb_strtolower($satuan)] = $satuan;
                        $subKegiatans[$kodeSub] = [
                            'kode_kegiatan' => $kodeKeg,
                            'kode_sub_kegiatan' => $kodeSub,
                            'nama_sub_kegiatan' => trim((string) $row[11]),
                            'kinerja' => trim((string) ($row[12] ?? '')) ?: null,
                            'indikator_sub_kegiatan' => trim((string) ($row[13] ?? '')) ?: null,
                            'satuan' => $satuan,
                            'spm' => trim((string) ($row[15] ?? 'Non-SPM')) ?: 'Non-SPM',
                            'aktivitas_layanan' => trim((string) ($row[16] ?? '')) ?: null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
                $count++;
            }
        }
        fclose($handle);

        DB::transaction(function () use ($programs, $kegiatans, $subKegiatans, $uniqueSatuans, $now): void {
            $existingSatuans = Satuan::query()
                ->pluck('nama_satuan')
                ->map(fn (string $v) => mb_strtolower(trim($v)))
                ->flip()
                ->all();

            $newSatuans = [];
            foreach ($uniqueSatuans as $k => $v) {
                if (! isset($existingSatuans[$k])) {
                    $newSatuans[] = [
                        'nama_satuan' => $v,
                        'keterangan' => 'Import CSV Nomenklatur',
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            foreach (array_chunk($newSatuans, 100) as $chunk) {
                DB::table('satuans')->insertOrIgnore($chunk);
            }

            foreach (array_chunk(array_values($programs), 200) as $chunk) {
                MasterProgram::upsert($chunk, ['kode_program'], ['kode_urusan', 'nama_urusan', 'kode_bidang_urusan', 'nama_bidang_urusan', 'nama_program', 'updated_at']);
            }
            $progMap = MasterProgram::query()->pluck('id', 'kode_program')->all();

            $kegRows = [];
            foreach ($kegiatans as $keg) {
                if (! isset($progMap[$keg['kode_program']])) {
                    continue;
                }
                $kegRows[] = [
                    'master_program_id' => $progMap[$keg['kode_program']],
                    'kode_kegiatan' => $keg['kode_kegiatan'],
                    'nama_kegiatan' => $keg['nama_kegiatan'],
                    'satuan' => $keg['satuan'],
                    'created_at' => $keg['created_at'],
                    'updated_at' => $keg['updated_at'],
                ];
            }
            foreach (array_chunk($kegRows, 300) as $chunk) {
                MasterKegiatan::upsert($chunk, ['kode_kegiatan'], ['master_program_id', 'nama_kegiatan', 'updated_at']);
            }
            $kegMap = MasterKegiatan::query()->pluck('id', 'kode_kegiatan')->all();

            $subRows = [];
            foreach ($subKegiatans as $sub) {
                if (! isset($kegMap[$sub['kode_kegiatan']])) {
                    continue;
                }
                $subRows[] = [
                    'master_kegiatan_id' => $kegMap[$sub['kode_kegiatan']],
                    'kode_sub_kegiatan' => $sub['kode_sub_kegiatan'],
                    'nama_sub_kegiatan' => $sub['nama_sub_kegiatan'],
                    'kinerja' => $sub['kinerja'],
                    'indikator_sub_kegiatan' => $sub['indikator_sub_kegiatan'],
                    'satuan' => $sub['satuan'],
                    'spm' => $sub['spm'],
                    'aktivitas_layanan' => $sub['aktivitas_layanan'],
                    'created_at' => $sub['created_at'],
                    'updated_at' => $sub['updated_at'],
                ];
            }
            foreach (array_chunk($subRows, 500) as $chunk) {
                MasterSubKegiatan::upsert($chunk, ['kode_sub_kegiatan'], ['master_kegiatan_id', 'nama_sub_kegiatan', 'kinerja', 'indikator_sub_kegiatan', 'satuan', 'spm', 'aktivitas_layanan', 'updated_at']);
            }
        });

        Notification::make()->title("Berhasil mengimpor {$count} baris nomenklatur Permendagri!")->success()->send();
        $this->closeModal();
    }

    public function exportCsv()
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'kode_urusan',
                'kode_bidang_urusan',
                'kode_program',
                'nama_program',
                'indikator_program',
                'satuan_program',
                'kode_kegiatan',
                'nama_kegiatan',
                'indikator_kegiatan',
                'satuan_kegiatan',
                'kode_sub_kegiatan',
                'nama_sub_kegiatan',
                'kinerja',
                'indikator_sub_kegiatan',
                'satuan_sub_kegiatan',
                'spm',
                'aktivitas_layanan',
            ], ';');

            $programs = MasterProgram::with('kegiatans.subKegiatans')->orderBy('kode_program')->get();
            foreach ($programs as $p) {
                if ($p->kegiatans->isEmpty()) {
                    fputcsv($out, [$p->kode_urusan, $p->kode_bidang_urusan, $p->kode_program, $p->nama_program, $p->indikator_program, $p->satuan], ';');

                    continue;
                }
                foreach ($p->kegiatans as $k) {
                    if ($k->subKegiatans->isEmpty()) {
                        fputcsv($out, [$p->kode_urusan, $p->kode_bidang_urusan, $p->kode_program, $p->nama_program, $p->indikator_program, $p->satuan, $k->kode_kegiatan, $k->nama_kegiatan, $k->indikator_kegiatan, $k->satuan], ';');

                        continue;
                    }
                    foreach ($k->subKegiatans as $s) {
                        fputcsv($out, [
                            $p->kode_urusan,
                            $p->kode_bidang_urusan,
                            $p->kode_program,
                            $p->nama_program,
                            $p->indikator_program,
                            $p->satuan,
                            $k->kode_kegiatan,
                            $k->nama_kegiatan,
                            $k->indikator_kegiatan,
                            $k->satuan,
                            $s->kode_sub_kegiatan,
                            $s->nama_sub_kegiatan,
                            $s->kinerja,
                            $s->indikator_sub_kegiatan,
                            $s->satuan,
                            $s->spm,
                            $s->aktivitas_layanan,
                        ], ';');
                    }
                }
            }
            fclose($out);
        }, 'Master-Nomenklatur-Permendagri.csv');
    }

    protected function getViewData(): array
    {
        $q = trim($this->search);
        $urusan = trim($this->filterUrusan);
        $bidang = trim($this->filterBidang);
        $spm = trim($this->filterSpm);

        $baseQuery = MasterProgram::query()
            ->when($urusan !== '', fn ($query) => $query->where('kode_urusan', $urusan))
            ->when($bidang !== '', fn ($query) => $query->where('kode_bidang_urusan', $bidang))
            ->when($spm !== '', function ($query) use ($spm) {
                $query->whereHas('kegiatans.subKegiatans', fn ($sq) => $sq->where('spm', $spm));
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('kode_program', 'ilike', "%{$q}%")
                        ->orWhere('nama_program', 'ilike', "%{$q}%")
                        ->orWhere('nama_bidang_urusan', 'ilike', "%{$q}%")
                        ->orWhereHas('kegiatans', function ($kq) use ($q) {
                            $kq->where('kode_kegiatan', 'ilike', "%{$q}%")
                                ->orWhere('nama_kegiatan', 'ilike', "%{$q}%")
                                ->orWhereHas('subKegiatans', function ($sq) use ($q) {
                                    $sq->where('kode_sub_kegiatan', 'ilike', "%{$q}%")
                                        ->orWhere('nama_sub_kegiatan', 'ilike', "%{$q}%")
                                        ->orWhere('indikator_sub_kegiatan', 'ilike', "%{$q}%");
                                });
                        });
                });
            });

        $totalFiltered = (clone $baseQuery)->count();
        $lastPage = max(1, (int) ceil($totalFiltered / $this->perPage));
        $currentPage = min(max(1, $this->page), $lastPage);
        $this->page = $currentPage;

        $programs = $baseQuery
            ->with([
                'kegiatans' => function ($kq) use ($spm, $q) {
                    $kq->orderBy('kode_kegiatan')
                        ->when($spm !== '', fn ($query) => $query->whereHas('subKegiatans', fn ($sq) => $sq->where('spm', $spm)))
                        ->with([
                            'subKegiatans' => function ($sq) use ($spm, $q) {
                                $sq->orderBy('kode_sub_kegiatan')
                                    ->when($spm !== '', fn ($query) => $query->where('spm', $spm))
                                    ->when($q !== '', function ($query) use ($q) {
                                        // Jika pencarian cocok langsung di level sub kegiatan, tampilkan yang cocok saja bila ada
                                        $query->where(function ($inner) use ($q) {
                                            $inner->where('kode_sub_kegiatan', 'ilike', "%{$q}%")
                                                ->orWhere('nama_sub_kegiatan', 'ilike', "%{$q}%")
                                                ->orWhere('indikator_sub_kegiatan', 'ilike', "%{$q}%")
                                                ->orWhereHas('kegiatan', fn ($k) => $k->where('kode_kegiatan', 'ilike', "%{$q}%")
                                                    ->orWhere('nama_kegiatan', 'ilike', "%{$q}%")
                                                    ->orWhereHas('program', fn ($p) => $p->where('kode_program', 'ilike', "%{$q}%")
                                                        ->orWhere('nama_program', 'ilike', "%{$q}%")
                                                        ->orWhere('nama_bidang_urusan', 'ilike', "%{$q}%")));
                                        });
                                    });
                            },
                        ]);
                },
            ])
            ->orderBy('kode_program')
            ->offset(($currentPage - 1) * $this->perPage)
            ->limit($this->perPage)
            ->get();

        $daftarUrusan = MasterProgram::query()
            ->select('kode_urusan', 'nama_urusan')
            ->whereNotNull('kode_urusan')
            ->distinct()
            ->orderBy('kode_urusan')
            ->get();

        $daftarBidang = MasterProgram::query()
            ->select('kode_bidang_urusan', 'nama_bidang_urusan')
            ->whereNotNull('kode_bidang_urusan')
            ->when($urusan !== '', fn ($query) => $query->where('kode_urusan', $urusan))
            ->distinct()
            ->orderBy('kode_bidang_urusan')
            ->get();

        return [
            'programs' => $programs,
            'daftarUrusan' => $daftarUrusan,
            'daftarBidang' => $daftarBidang,
            'totalUrusan' => MasterProgram::distinct('kode_urusan')->count('kode_urusan'),
            'totalBidang' => MasterProgram::distinct('kode_bidang_urusan')->count('kode_bidang_urusan'),
            'totalProgram' => MasterProgram::count(),
            'totalKegiatan' => MasterKegiatan::count(),
            'totalSubKegiatan' => MasterSubKegiatan::count(),
            'totalSpm' => MasterSubKegiatan::where('spm', 'SPM')->count(),
            'totalFiltered' => $totalFiltered,
            'currentPage' => $currentPage,
            'lastPage' => $lastPage,
            'daftarSatuan' => Satuan::getOptions(),
            'canManage' => $this->canManage(),
            'canDelete' => $this->canDeleteData(),
        ];
    }
}
