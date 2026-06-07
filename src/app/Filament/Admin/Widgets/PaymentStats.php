<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Payment', Payment::count()),
            Stat::make('Success Payment', Payment::where('status', 'success')->count()),
            Stat::make('Total Income', Payment::where('status', 'success')->sum('gross_amount')),
        ];
    }
}