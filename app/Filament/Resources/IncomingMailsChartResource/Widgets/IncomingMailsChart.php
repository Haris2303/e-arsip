<?php

namespace App\Filament\Resources\IncomingMailsChartResource\Widgets;

use App\Models\IncomingMail;
use App\Models\OutgoingMail;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class IncomingMailsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart Surat';
    public ?string $filter = 'today';

    protected function getData(): array
    {
        switch ($this->filter) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                $internal = 'perHour';
                break;

            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $internal = 'perDay';
                break;

            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $internal = 'perMonth';
                break;

            default:
                $start = now()->subYears(5)->startOfYear();
                $end = now()->endOfYear();
                $internal = 'perYear';
        }

        // Get data Incoming Mails
        $incoming = Trend::model(IncomingMail::class)
            ->between(start: $start, end: $end)
            ->{$internal}()
            ->count();

        // Get data Outgoing Mails
        $outgoing = Trend::model(OutgoingMail::class)
            ->between(start: $start, end: $end)
            ->{$internal}()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Surat Masuk',
                    'data' => $incoming->map(fn(TrendValue $value) => $value->aggregate)->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)', // blue
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Surat Keluar',
                    'data' => $outgoing->map(fn(TrendValue $value) => $value->aggregate)->toArray(),
                    'backgroundColor' => 'rgba(255, 205, 86, 0.5)', // yellow
                    'borderColor' => 'rgba(255, 205, 86, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $incoming->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'year' => 'Tahun Ini',
            'years' => '5 Tahun Terakhir'
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
    {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    callback: (value) => Number.isInteger(value) ? value : null
                }
            }
        }
    }
    JS);
    }
}
