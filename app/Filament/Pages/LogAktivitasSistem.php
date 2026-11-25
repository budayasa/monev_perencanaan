<?php

namespace App\Filament\Pages;

use App\Models\PerangkatDaerah;
use App\Models\User;
use Filament\Pages\Page;
use Spatie\Activitylog\Models\Activity;

class LogAktivitasSistem extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?string $navigationLabel = 'Log Aktivitas & Audit';

    protected static ?string $title = 'Log Aktivitas & Audit Sistem (Troubleshooting)';

    protected static ?string $navigationGroup = 'Pengaturan & Master';

    protected static ?int $navigationSort = 11;

    protected static string $view = 'filament.pages.log-aktivitas-sistem';

    public string $search = '';

    public string $filterEvent = 'semua';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator']);
    }

    protected function getViewData(): array
    {
        $query = Activity::with('causer')->orderByDesc('created_at');

        if ($this->filterEvent !== 'semua') {
            $query->where('event', $this->filterEvent);
        }

        if ($this->search !== '') {
            $q = $this->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('description', 'ilike', "%{$q}%")
                    ->orWhere('subject_type', 'ilike', "%{$q}%");
            });
        }

        $logs = $query->limit(100)->get();

        return [
            'logs' => $logs,
            'totalLogs' => Activity::count(),
            'totalUsers' => User::count(),
            'totalOpd' => PerangkatDaerah::count(),
        ];
    }
}
