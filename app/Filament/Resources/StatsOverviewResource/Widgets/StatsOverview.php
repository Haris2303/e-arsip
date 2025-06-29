<?php

namespace App\Filament\Resources\StatsOverviewResource\Widgets;

use App\Models\IncomingMail;
use App\Models\OutgoingMail;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            $this->buildStat(
                label: 'Surat Masuk Hari Ini',
                model: IncomingMail::class
            ),
            $this->buildStat(
                label: 'Surat Keluar Hari ini',
                model: OutgoingMail::class
            )
        ];
    }

    private function buildStat(string $label, string $model): Stat
    {
        $todayCount = $model::whereDate('created_at', today())->count();
        $yesterdayCount = $model::whereDate('created_at', today()->subDay())->count();
        $diff = $todayCount - $yesterdayCount;

        $description = $this->formatDifference($diff);
        $icon = $diff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $color = $diff >= 0 ? 'success' : 'danger';

        return Stat::make($label, number_format($todayCount))
            ->description($description)
            ->descriptionIcon($icon)
            ->color($color);
    }

    private function formatDifference(int $diff): string
    {
        $abs = abs($diff);

        if ($diff > 0) {
            return '+' . number_format($abs) . ' meningkat';
        } elseif ($diff < 0) {
            return '-' . number_format($abs) . ' menurun';
        }

        return 'No change';
    }
}
