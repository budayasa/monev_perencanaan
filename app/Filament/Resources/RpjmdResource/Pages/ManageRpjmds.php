<?php

namespace App\Filament\Resources\RpjmdResource\Pages;

use App\Filament\Resources\RpjmdResource;
use App\Models\MasterProgram;
use App\Models\PerangkatDaerah;
use App\Models\Rpjmd;
use App\Models\RpjmdIndikatorSasaran;
use App\Models\RpjmdIndikatorTujuan;
use App\Models\RpjmdMisi;
use App\Models\RpjmdProgram;
use App\Models\RpjmdSasaran;
use App\Models\RpjmdTujuan;
use App\Models\RpjmdVisi;
use App\Models\Satuan;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class ManageRpjmds extends Page
{
    protected static string $resource = RpjmdResource::class;

    protected static ?string $title = 'Master RPJMD (5 Tahunan Kabupaten)';

    protected static string $view = 'filament.resources.rpjmd-resource.pages.manage-rpjmds';

    // Modal control: null | visi | misi | tujuan | indikator_tujuan | sasaran | indikator_sasaran | program | indikator_program
    public ?string $activeModal = null;

    public ?int $editingId = null;

    public ?int $parentId = null;

    public string $parentLabel = '';

    // Form states
    public array $form = [];

    // Filter pencarian cepat
    public string $search = '';

    public bool $showProgramSuggestions = false;

    public function canManage(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator']);
    }

    public function canDeleteData(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public function closeModal(): void
    {
        $this->activeModal = null;
        $this->editingId = null;
        $this->parentId = null;
        $this->parentLabel = '';
        $this->form = [];
        $this->showProgramSuggestions = false;
    }

    // ==================== 1. VISI (Pasti 1) ====================
    public function openVisiModal(?int $id = null): void
    {
        if (! $this->canManage()) {
            return;
        }

        $visi = $id ? RpjmdVisi::find($id) : RpjmdVisi::first();
        $this->activeModal = 'visi';
        $this->editingId = $visi?->id;
        $this->form = [
            'tahun_awal' => $visi?->tahun_awal ?? 2026,
            'tahun_akhir' => $visi?->tahun_akhir ?? 2030,
            'visi' => $visi?->visi ?? '',
        ];
    }

    public function saveVisi(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.tahun_awal' => 'required|integer|min:2020|max:2045',
            'form.tahun_akhir' => 'required|integer|gte:form.tahun_awal',
            'form.visi' => 'required|string|min:5',
        ]);

        // Pastikan jumlah Visi hanya 1
        $existing = $this->editingId ? RpjmdVisi::find($this->editingId) : RpjmdVisi::first();

        if ($existing) {
            $existing->update([
                'tahun_awal' => (int) $this->form['tahun_awal'],
                'tahun_akhir' => (int) $this->form['tahun_akhir'],
                'visi' => trim((string) $this->form['visi']),
            ]);
        } else {
            RpjmdVisi::create([
                'tahun_awal' => (int) $this->form['tahun_awal'],
                'tahun_akhir' => (int) $this->form['tahun_akhir'],
                'visi' => trim((string) $this->form['visi']),
            ]);
        }

        Notification::make()->title('Visi RPJMD berhasil disimpan!')->success()->send();
        $this->closeModal();
    }

    // ==================== 2. MISI (1 Visi -> Banyak Misi) ====================
    public function openMisiModal(int $visiId, ?int $misiId = null): void
    {
        if (! $this->canManage()) {
            return;
        }

        $misi = $misiId ? RpjmdMisi::find($misiId) : null;
        $nextUrutan = RpjmdMisi::where('rpjmd_visi_id', $visiId)->max('urutan') + 1;

        $this->activeModal = 'misi';
        $this->parentId = $visiId;
        $this->editingId = $misi?->id;
        $this->form = [
            'urutan' => $misi?->urutan ?? $nextUrutan,
            'misi' => $misi?->misi ?? '',
        ];
    }

    public function saveMisi(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.urutan' => 'required|integer|min:1',
            'form.misi' => 'required|string|min:3',
        ]);

        $misiText = trim((string) $this->form['misi']);
        $duplikatMisi = RpjmdMisi::where('rpjmd_visi_id', $this->parentId)
            ->whereRaw('LOWER(TRIM(misi)) = ?', [mb_strtolower($misiText)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatMisi) {
            $this->addError('form.misi', 'Misi tersebut sudah ada pada Visi ini. Tidak boleh menginput Misi yang sama.');
            Notification::make()->title('Gagal: Misi sudah ada!')->danger()->send();

            return;
        }

        RpjmdMisi::updateOrCreate(
            ['id' => $this->editingId],
            [
                'rpjmd_visi_id' => $this->parentId,
                'urutan' => (int) $this->form['urutan'],
                'misi' => $misiText,
            ]
        );

        Notification::make()->title('Misi RPJMD berhasil disimpan!')->success()->send();
        $this->closeModal();
    }

    public function deleteMisi(int $id): void
    {
        if (! $this->canDeleteData()) {
            return;
        }
        RpjmdMisi::whereKey($id)->delete();
        Notification::make()->title('Misi beserta turunannya berhasil dihapus.')->success()->send();
    }

    // ==================== 3. TUJUAN (1 Misi -> Banyak Tujuan) ====================
    public function openTujuanModal(int $misiId, ?int $tujuanId = null): void
    {
        if (! $this->canManage()) {
            return;
        }

        $misi = RpjmdMisi::find($misiId);
        $tujuan = $tujuanId ? RpjmdTujuan::find($tujuanId) : null;
        $nextUrutan = RpjmdTujuan::where('rpjmd_misi_id', $misiId)->max('urutan') + 1;

        $this->activeModal = 'tujuan';
        $this->parentId = $misiId;
        $this->parentLabel = 'Misi '.$misi?->urutan.': '.$misi?->misi;
        $this->editingId = $tujuan?->id;
        $this->form = [
            'urutan' => $tujuan?->urutan ?? $nextUrutan,
            'tujuan' => $tujuan?->tujuan ?? '',
        ];
    }

    public function saveTujuan(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.urutan' => 'required|integer|min:1',
            'form.tujuan' => 'required|string|min:3',
        ]);

        $tujuanText = trim((string) $this->form['tujuan']);
        $duplikatTujuan = RpjmdTujuan::where('rpjmd_misi_id', $this->parentId)
            ->whereRaw('LOWER(TRIM(tujuan)) = ?', [mb_strtolower($tujuanText)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatTujuan) {
            $this->addError('form.tujuan', 'Tujuan tersebut sudah ada pada Misi ini. Tidak boleh menginput Tujuan yang sama.');
            Notification::make()->title('Gagal: Tujuan sudah ada!')->danger()->send();

            return;
        }

        RpjmdTujuan::updateOrCreate(
            ['id' => $this->editingId],
            [
                'rpjmd_misi_id' => $this->parentId,
                'urutan' => (int) $this->form['urutan'],
                'tujuan' => $tujuanText,
            ]
        );

        Notification::make()->title('Tujuan RPJMD berhasil disimpan!')->success()->send();
        $this->closeModal();
    }

    public function deleteTujuan(int $id): void
    {
        if (! $this->canDeleteData()) {
            return;
        }
        RpjmdTujuan::whereKey($id)->delete();
        Notification::make()->title('Tujuan beserta turunannya berhasil dihapus.')->success()->send();
    }

    // ==================== 4. INDIKATOR TUJUAN (1 Tujuan -> 2 atau lebih Indikator) ====================
    public function openIndikatorTujuanModal(int $tujuanId, ?int $indikatorId = null): void
    {
        if (! $this->canManage()) {
            return;
        }

        $tujuan = RpjmdTujuan::find($tujuanId);
        $ind = $indikatorId ? RpjmdIndikatorTujuan::find($indikatorId) : null;

        $this->activeModal = 'indikator_tujuan';
        $this->parentId = $tujuanId;
        $this->parentLabel = 'Tujuan: '.$tujuan?->tujuan;
        $this->editingId = $ind?->id;
        $this->form = [
            'indikator' => $ind?->indikator ?? '',
            'satuan' => $ind?->satuan ?? '%',
            'sifat_target' => $ind?->sifat_target ?? 'non_akumulatif',
            'capaian_awal' => $ind?->capaian_awal_teks ?: ($ind?->capaian_awal ?? 0),
            'target_t1' => $ind?->target_t1_teks ?: ($ind?->target_t1 ?? 0),
            'target_t2' => $ind?->target_t2_teks ?: ($ind?->target_t2 ?? 0),
            'target_t3' => $ind?->target_t3_teks ?: ($ind?->target_t3 ?? 0),
            'target_t4' => $ind?->target_t4_teks ?: ($ind?->target_t4 ?? 0),
            'target_t5' => $ind?->target_t5_teks ?: ($ind?->target_t5 ?? 0),
        ];
    }

    public function saveIndikatorTujuan(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.indikator' => 'required|string|min:2',
            'form.satuan' => 'required|string|max:50',
            'form.sifat_target' => 'required|in:non_akumulatif,akumulatif',
        ]);

        $indText = trim((string) $this->form['indikator']);
        $duplikatInd = RpjmdIndikatorTujuan::where('rpjmd_tujuan_id', $this->parentId)
            ->whereRaw('LOWER(TRIM(indikator)) = ?', [mb_strtolower($indText)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatInd) {
            $this->addError('form.indikator', 'Indikator Tujuan tersebut sudah ada. Tidak boleh menginput indikator yang sama.');
            Notification::make()->title('Gagal: Indikator Tujuan sudah ada!')->danger()->send();

            return;
        }

        $base = Rpjmd::parseTargetInput($this->form['capaian_awal'] ?? 0);
        $t1 = Rpjmd::parseTargetInput($this->form['target_t1'] ?? 0);
        $t2 = Rpjmd::parseTargetInput($this->form['target_t2'] ?? 0);
        $t3 = Rpjmd::parseTargetInput($this->form['target_t3'] ?? 0);
        $t4 = Rpjmd::parseTargetInput($this->form['target_t4'] ?? 0);
        $t5 = Rpjmd::parseTargetInput($this->form['target_t5'] ?? 0);

        RpjmdIndikatorTujuan::updateOrCreate(
            ['id' => $this->editingId],
            [
                'rpjmd_tujuan_id' => $this->parentId,
                'indikator' => $indText,
                'satuan' => trim((string) $this->form['satuan']),
                'sifat_target' => (string) $this->form['sifat_target'],
                'capaian_awal' => $base['numeric'],
                'capaian_awal_teks' => $base['text'],
                'target_t1' => $t1['numeric'],
                'target_t1_teks' => $t1['text'],
                'target_t2' => $t2['numeric'],
                'target_t2_teks' => $t2['text'],
                'target_t3' => $t3['numeric'],
                'target_t3_teks' => $t3['text'],
                'target_t4' => $t4['numeric'],
                'target_t4_teks' => $t4['text'],
                'target_t5' => $t5['numeric'],
                'target_t5_teks' => $t5['text'],
            ]
        );

        Notification::make()->title('Indikator Tujuan berhasil disimpan!')->success()->send();
        $this->closeModal();
    }

    public function deleteIndikatorTujuan(int $id): void
    {
        if (! $this->canDeleteData()) {
            return;
        }
        RpjmdIndikatorTujuan::whereKey($id)->delete();
        Notification::make()->title('Indikator Tujuan berhasil dihapus.')->success()->send();
    }

    // ==================== 5. SASARAN (1 Tujuan -> Banyak Sasaran) ====================
    public function openSasaranModal(int $tujuanId, ?int $sasaranId = null): void
    {
        if (! $this->canManage()) {
            return;
        }

        $tujuan = RpjmdTujuan::find($tujuanId);
        $sasaran = $sasaranId ? RpjmdSasaran::find($sasaranId) : null;
        $nextUrutan = RpjmdSasaran::where('rpjmd_tujuan_id', $tujuanId)->max('urutan') + 1;

        $this->activeModal = 'sasaran';
        $this->parentId = $tujuanId;
        $this->parentLabel = 'Tujuan: '.$tujuan?->tujuan;
        $this->editingId = $sasaran?->id;
        $this->form = [
            'urutan' => $sasaran?->urutan ?? $nextUrutan,
            'sasaran' => $sasaran?->sasaran ?? '',
        ];
    }

    public function saveSasaran(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.urutan' => 'required|integer|min:1',
            'form.sasaran' => 'required|string|min:3',
        ]);

        $sasaranText = trim((string) $this->form['sasaran']);
        $duplikatSasaran = RpjmdSasaran::where('rpjmd_tujuan_id', $this->parentId)
            ->whereRaw('LOWER(TRIM(sasaran)) = ?', [mb_strtolower($sasaranText)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatSasaran) {
            $this->addError('form.sasaran', 'Sasaran tersebut sudah ada pada Tujuan ini. Tidak boleh menginput Sasaran yang sama.');
            Notification::make()->title('Gagal: Sasaran sudah ada!')->danger()->send();

            return;
        }

        RpjmdSasaran::updateOrCreate(
            ['id' => $this->editingId],
            [
                'rpjmd_tujuan_id' => $this->parentId,
                'urutan' => (int) $this->form['urutan'],
                'sasaran' => $sasaranText,
            ]
        );

        Notification::make()->title('Sasaran RPJMD berhasil disimpan!')->success()->send();
        $this->closeModal();
    }

    public function deleteSasaran(int $id): void
    {
        if (! $this->canDeleteData()) {
            return;
        }
        RpjmdSasaran::whereKey($id)->delete();
        Notification::make()->title('Sasaran beserta turunannya berhasil dihapus.')->success()->send();
    }

    // ==================== 6. INDIKATOR SASARAN (1 Sasaran -> 2 atau lebih Indikator) ====================
    public function openIndikatorSasaranModal(int $sasaranId, ?int $indikatorId = null): void
    {
        if (! $this->canManage()) {
            return;
        }

        $sasaran = RpjmdSasaran::find($sasaranId);
        $ind = $indikatorId ? RpjmdIndikatorSasaran::find($indikatorId) : null;

        $this->activeModal = 'indikator_sasaran';
        $this->parentId = $sasaranId;
        $this->parentLabel = 'Sasaran: '.$sasaran?->sasaran;
        $this->editingId = $ind?->id;
        $this->form = [
            'indikator' => $ind?->indikator ?? '',
            'satuan' => $ind?->satuan ?? '%',
            'sifat_target' => $ind?->sifat_target ?? 'non_akumulatif',
            'capaian_awal' => $ind?->capaian_awal_teks ?: ($ind?->capaian_awal ?? 0),
            'target_t1' => $ind?->target_t1_teks ?: ($ind?->target_t1 ?? 0),
            'target_t2' => $ind?->target_t2_teks ?: ($ind?->target_t2 ?? 0),
            'target_t3' => $ind?->target_t3_teks ?: ($ind?->target_t3 ?? 0),
            'target_t4' => $ind?->target_t4_teks ?: ($ind?->target_t4 ?? 0),
            'target_t5' => $ind?->target_t5_teks ?: ($ind?->target_t5 ?? 0),
        ];
    }

    public function saveIndikatorSasaran(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.indikator' => 'required|string|min:2',
            'form.satuan' => 'required|string|max:50',
            'form.sifat_target' => 'required|in:non_akumulatif,akumulatif',
        ]);

        $indText = trim((string) $this->form['indikator']);
        $duplikatInd = RpjmdIndikatorSasaran::where('rpjmd_sasaran_id', $this->parentId)
            ->whereRaw('LOWER(TRIM(indikator)) = ?', [mb_strtolower($indText)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatInd) {
            $this->addError('form.indikator', 'Indikator Sasaran tersebut sudah ada. Tidak boleh menginput indikator yang sama.');
            Notification::make()->title('Gagal: Indikator Sasaran sudah ada!')->danger()->send();

            return;
        }

        $base = Rpjmd::parseTargetInput($this->form['capaian_awal'] ?? 0);
        $t1 = Rpjmd::parseTargetInput($this->form['target_t1'] ?? 0);
        $t2 = Rpjmd::parseTargetInput($this->form['target_t2'] ?? 0);
        $t3 = Rpjmd::parseTargetInput($this->form['target_t3'] ?? 0);
        $t4 = Rpjmd::parseTargetInput($this->form['target_t4'] ?? 0);
        $t5 = Rpjmd::parseTargetInput($this->form['target_t5'] ?? 0);

        RpjmdIndikatorSasaran::updateOrCreate(
            ['id' => $this->editingId],
            [
                'rpjmd_sasaran_id' => $this->parentId,
                'indikator' => $indText,
                'satuan' => trim((string) $this->form['satuan']),
                'sifat_target' => (string) $this->form['sifat_target'],
                'capaian_awal' => $base['numeric'],
                'capaian_awal_teks' => $base['text'],
                'target_t1' => $t1['numeric'],
                'target_t1_teks' => $t1['text'],
                'target_t2' => $t2['numeric'],
                'target_t2_teks' => $t2['text'],
                'target_t3' => $t3['numeric'],
                'target_t3_teks' => $t3['text'],
                'target_t4' => $t4['numeric'],
                'target_t4_teks' => $t4['text'],
                'target_t5' => $t5['numeric'],
                'target_t5_teks' => $t5['text'],
            ]
        );

        Notification::make()->title('Indikator Sasaran berhasil disimpan!')->success()->send();
        $this->closeModal();
    }

    public function deleteIndikatorSasaran(int $id): void
    {
        if (! $this->canDeleteData()) {
            return;
        }
        RpjmdIndikatorSasaran::whereKey($id)->delete();
        Notification::make()->title('Indikator Sasaran berhasil dihapus.')->success()->send();
    }

    // ==================== 7. PROGRAM (1 Sasaran -> Banyak Program) ====================
    public function openProgramModal(int $sasaranId, ?int $programId = null): void
    {
        if (! $this->canManage()) {
            return;
        }

        $sasaran = RpjmdSasaran::find($sasaranId);
        $prog = $programId ? RpjmdProgram::with('opds')->find($programId) : null;

        $this->activeModal = 'program';
        $this->parentId = $sasaranId;
        $this->parentLabel = 'Sasaran: '.$sasaran?->sasaran;
        $this->editingId = $prog?->id;
        $this->showProgramSuggestions = false;
        $this->form = [
            'kode_program' => $prog?->kode_program ?? '',
            'nama_program' => $prog?->nama_program ?? '',
            'selected_opd_id' => '',
            'opd_ids' => $prog ? $prog->opds->pluck('id')->map(fn ($v) => (string) $v)->all() : [],
        ];
    }

    public function updatedFormNamaProgram($value): void
    {
        $this->showProgramSuggestions = trim((string) $value) !== '';
    }

    public function selectMasterProgram(int $id): void
    {
        $master = MasterProgram::find($id);
        if ($master) {
            $this->form['kode_program'] = $master->kode_program;
            $this->form['nama_program'] = $master->nama_program;
        }
        $this->showProgramSuggestions = false;
    }

    public function updatedFormSelectedOpdId($value): void
    {
        $id = trim((string) $value);
        if ($id === '') {
            return;
        }

        $current = array_map('strval', (array) ($this->form['opd_ids'] ?? []));
        if (! in_array($id, $current, true)) {
            $current[] = $id;
            $this->form['opd_ids'] = $current;
        }
        $this->form['selected_opd_id'] = '';
    }

    public function removeOpdFromForm(string $opdId): void
    {
        $current = array_map('strval', (array) ($this->form['opd_ids'] ?? []));
        $this->form['opd_ids'] = array_values(array_filter($current, fn ($v) => $v !== $opdId));
    }

    public function saveProgram(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.nama_program' => 'required|string|min:3',
        ]);

        $kodeProgram = trim((string) ($this->form['kode_program'] ?? '')) ?: null;
        $namaProgram = trim((string) $this->form['nama_program']);

        if ($kodeProgram !== null) {
            $duplikatKode = RpjmdProgram::where('rpjmd_sasaran_id', $this->parentId)
                ->whereRaw('LOWER(TRIM(kode_program)) = ?', [mb_strtolower($kodeProgram)])
                ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
                ->exists();

            if ($duplikatKode) {
                $this->addError('form.kode_program', "Kode Program \"{$kodeProgram}\" sudah ada pada Sasaran ini.");
                Notification::make()->title('Gagal: Kode Program sudah ada!')->danger()->send();

                return;
            }
        }

        $duplikatNama = RpjmdProgram::where('rpjmd_sasaran_id', $this->parentId)
            ->whereRaw('LOWER(TRIM(nama_program)) = ?', [mb_strtolower($namaProgram)])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($duplikatNama) {
            $this->addError('form.nama_program', 'Nama Program tersebut sudah ada pada Sasaran ini. Tidak boleh menginput Program yang sama.');
            Notification::make()->title('Gagal: Nama Program sudah ada!')->danger()->send();

            return;
        }

        $prog = RpjmdProgram::updateOrCreate(
            ['id' => $this->editingId],
            [
                'rpjmd_sasaran_id' => $this->parentId,
                'kode_program' => $kodeProgram,
                'nama_program' => $namaProgram,
            ]
        );

        $opdIds = array_map('intval', array_filter((array) ($this->form['opd_ids'] ?? [])));
        $prog->opds()->sync($opdIds);

        Notification::make()->title('Program RPJMD berhasil disimpan! Selanjutnya silakan lengkapi Indikator Program & Pagu 5 Tahun.')->success()->send();
        $this->closeModal();
    }

    public function deleteProgram(int $id): void
    {
        if (! $this->canDeleteData()) {
            return;
        }
        RpjmdProgram::whereKey($id)->delete();
        Notification::make()->title('Program RPJMD berhasil dihapus.')->success()->send();
    }

    // ==================== 8. INDIKATOR PROGRAM (PASTI 1) & PAGU 5 TAHUN ====================
    public function openIndikatorProgramModal(int $programId): void
    {
        if (! $this->canManage()) {
            return;
        }

        $prog = RpjmdProgram::with('opds')->findOrFail($programId);

        $this->activeModal = 'indikator_program';
        $this->editingId = $prog->id;
        $this->parentLabel = ($prog->kode_program ? "[{$prog->kode_program}] " : '').$prog->nama_program;
        $this->form = [
            'indikator_program' => $prog->indikator_program ?? '',
            'satuan' => $prog->satuan ?? '%',
            'sifat_target' => $prog->sifat_target ?? 'non_akumulatif',
            'capaian_awal' => $prog->capaian_awal_teks ?: ($prog->capaian_awal ?? 0),
            'target_t1' => $prog->target_t1_teks ?: ($prog->target_t1 ?? 0),
            'target_t2' => $prog->target_t2_teks ?: ($prog->target_t2 ?? 0),
            'target_t3' => $prog->target_t3_teks ?: ($prog->target_t3 ?? 0),
            'target_t4' => $prog->target_t4_teks ?: ($prog->target_t4 ?? 0),
            'target_t5' => $prog->target_t5_teks ?: ($prog->target_t5 ?? 0),
            'pagu_t1' => $prog->pagu_t1 ?? 0,
            'pagu_t2' => $prog->pagu_t2 ?? 0,
            'pagu_t3' => $prog->pagu_t3 ?? 0,
            'pagu_t4' => $prog->pagu_t4 ?? 0,
            'pagu_t5' => $prog->pagu_t5 ?? 0,
            'opd_ids' => $prog->opds->pluck('id')->map(fn ($v) => (string) $v)->all(),
        ];
    }

    public function saveIndikatorProgram(): void
    {
        if (! $this->canManage()) {
            return;
        }

        $this->validate([
            'form.indikator_program' => 'required|string|min:2',
            'form.satuan' => 'required|string|max:50',
            'form.sifat_target' => 'required|in:non_akumulatif,akumulatif',
        ]);

        $base = Rpjmd::parseTargetInput($this->form['capaian_awal'] ?? 0);
        $t1 = Rpjmd::parseTargetInput($this->form['target_t1'] ?? 0);
        $t2 = Rpjmd::parseTargetInput($this->form['target_t2'] ?? 0);
        $t3 = Rpjmd::parseTargetInput($this->form['target_t3'] ?? 0);
        $t4 = Rpjmd::parseTargetInput($this->form['target_t4'] ?? 0);
        $t5 = Rpjmd::parseTargetInput($this->form['target_t5'] ?? 0);

        $prog = RpjmdProgram::findOrFail($this->editingId);
        $prog->update([
            'indikator_program' => trim((string) $this->form['indikator_program']),
            'satuan' => trim((string) $this->form['satuan']),
            'sifat_target' => (string) $this->form['sifat_target'],
            'capaian_awal' => $base['numeric'],
            'capaian_awal_teks' => $base['text'],
            'target_t1' => $t1['numeric'],
            'target_t1_teks' => $t1['text'],
            'target_t2' => $t2['numeric'],
            'target_t2_teks' => $t2['text'],
            'target_t3' => $t3['numeric'],
            'target_t3_teks' => $t3['text'],
            'target_t4' => $t4['numeric'],
            'target_t4_teks' => $t4['text'],
            'target_t5' => $t5['numeric'],
            'target_t5_teks' => $t5['text'],
            'pagu_t1' => (float) ($this->form['pagu_t1'] ?? 0),
            'pagu_t2' => (float) ($this->form['pagu_t2'] ?? 0),
            'pagu_t3' => (float) ($this->form['pagu_t3'] ?? 0),
            'pagu_t4' => (float) ($this->form['pagu_t4'] ?? 0),
            'pagu_t5' => (float) ($this->form['pagu_t5'] ?? 0),
        ]);

        if (array_key_exists('opd_ids', $this->form)) {
            $opdIds = array_map('intval', array_filter((array) $this->form['opd_ids']));
            $prog->opds()->sync($opdIds);
        }

        Notification::make()->title('Indikator Program (1 Indikator) & Pagu 5 Tahun berhasil disimpan!')->success()->send();
        $this->closeModal();
    }

    // ==================== EXPORT CSV HIERARKI RPJMD ====================
    public function exportCsv()
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'periode',
                'visi',
                'misi',
                'tujuan',
                'sasaran',
                'kode_program',
                'nama_program',
                'indikator_program',
                'satuan',
                'sifat_target',
                'capaian_awal',
                'target_t1',
                'target_t2',
                'target_t3',
                'target_t4',
                'target_t5',
                'target_akhir',
                'pagu_t1',
                'pagu_t2',
                'pagu_t3',
                'pagu_t4',
                'pagu_t5',
                'pagu_5_tahun',
                'opd_pengampu',
            ], ';');

            $visi = RpjmdVisi::with('misis.tujuans.sasarans.programs.opds')->first();
            if ($visi) {
                $periode = "{$visi->tahun_awal}-{$visi->tahun_akhir}";
                foreach ($visi->misis as $misi) {
                    foreach ($misi->tujuans as $tujuan) {
                        foreach ($tujuan->sasarans as $sasaran) {
                            foreach ($sasaran->programs as $prog) {
                                fputcsv($out, [
                                    $periode,
                                    $visi->visi,
                                    "Misi {$misi->urutan}: {$misi->misi}",
                                    $tujuan->tujuan,
                                    $sasaran->sasaran,
                                    $prog->kode_program,
                                    $prog->nama_program,
                                    $prog->indikator_program,
                                    $prog->satuan,
                                    $prog->sifat_target,
                                    $prog->capaian_awal,
                                    $prog->target_t1,
                                    $prog->target_t2,
                                    $prog->target_t3,
                                    $prog->target_t4,
                                    $prog->target_t5,
                                    $prog->target_akhir,
                                    $prog->pagu_t1,
                                    $prog->pagu_t2,
                                    $prog->pagu_t3,
                                    $prog->pagu_t4,
                                    $prog->pagu_t5,
                                    $prog->pagu_5_tahun,
                                    $prog->opds->pluck('singkatan')->implode(', '),
                                ], ';');
                            }
                        }
                    }
                }
            }
            fclose($out);
        }, 'Master-RPJMD-Berjenjang-Kab-Badung.csv');
    }

    public function getPreviewTargetAkhirProperty(): string
    {
        $sifat = (string) ($this->form['sifat_target'] ?? 'non_akumulatif');
        $items = [
            Rpjmd::parseTargetInput($this->form['target_t1'] ?? 0),
            Rpjmd::parseTargetInput($this->form['target_t2'] ?? 0),
            Rpjmd::parseTargetInput($this->form['target_t3'] ?? 0),
            Rpjmd::parseTargetInput($this->form['target_t4'] ?? 0),
            Rpjmd::parseTargetInput($this->form['target_t5'] ?? 0),
        ];

        $numeric = Rpjmd::hitungTargetAkhir(
            $sifat,
            $items[0]['numeric'],
            $items[1]['numeric'],
            $items[2]['numeric'],
            $items[3]['numeric'],
            $items[4]['numeric']
        );
        $teks = Rpjmd::hitungTargetAkhirTeks($sifat, $items);

        return Rpjmd::displayTarget($numeric, $teks);
    }

    public function getPreviewPagu5TahunProperty(): float
    {
        return (float) ($this->form['pagu_t1'] ?? 0)
            + (float) ($this->form['pagu_t2'] ?? 0)
            + (float) ($this->form['pagu_t3'] ?? 0)
            + (float) ($this->form['pagu_t4'] ?? 0)
            + (float) ($this->form['pagu_t5'] ?? 0);
    }

    protected function getViewData(): array
    {
        $visi = RpjmdVisi::with([
            'misis.tujuans.indikators',
            'misis.tujuans.sasarans.indikators',
            'misis.tujuans.sasarans.programs.opds',
        ])->first();

        // Dokumen RPJMD 2025-2029 memakai 2025 sebagai Baseline dan 2026-2030 sebagai Target T1-T5
        $rawTahunAwal = (int) ($visi?->tahun_awal ?? 2026);
        $tahunAwal = $rawTahunAwal === 2025 ? 2026 : $rawTahunAwal;
        $tahunAkhir = $rawTahunAwal === 2025 ? 2030 : (int) ($visi?->tahun_akhir ?? 2030);

        return [
            'visi' => $visi,
            'tahunAwal' => $tahunAwal,
            'tahunAkhir' => $tahunAkhir,
            'tahunBaseline' => $tahunAwal - 1,
            'totalMisi' => RpjmdMisi::count(),
            'totalTujuan' => RpjmdTujuan::count(),
            'totalIndikatorTujuan' => RpjmdIndikatorTujuan::count(),
            'totalSasaran' => RpjmdSasaran::count(),
            'totalIndikatorSasaran' => RpjmdIndikatorSasaran::count(),
            'totalProgram' => RpjmdProgram::count(),
            'totalPagu5Tahun' => (float) RpjmdProgram::sum('pagu_5_tahun'),
            'daftarOpd' => PerangkatDaerah::orderBy('id')->get(),
            'programSuggestions' => ($this->activeModal === 'program' && $this->showProgramSuggestions && trim((string) ($this->form['nama_program'] ?? '')) !== '')
                ? MasterProgram::query()
                    ->where(function ($sub): void {
                        $q = trim((string) ($this->form['nama_program'] ?? ''));
                        $sub->where('nama_program', 'ilike', "%{$q}%")
                            ->orWhere('kode_program', 'ilike', "%{$q}%")
                            ->orWhere('nama_bidang_urusan', 'ilike', "%{$q}%");
                    })
                    ->orderBy('kode_program')
                    ->limit(12)
                    ->get(['id', 'kode_program', 'nama_program', 'nama_bidang_urusan'])
                : collect(),
            'daftarSatuan' => Satuan::getOptions(),
            'canManage' => $this->canManage(),
            'canDelete' => $this->canDeleteData(),
        ];
    }
}
