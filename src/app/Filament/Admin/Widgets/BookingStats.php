<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Booking', Booking::count()),
            Stat::make('Pending Booking', Booking::where('booking_status', 'pending')->count()),
            Stat::make('Confirmed Booking', Booking::where('booking_status', 'confirmed')->count()),
        ];
    }
}