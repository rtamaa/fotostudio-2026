<?php

namespace App\Filament\Admin\Resources\BookingLogResource\Pages;

use App\Filament\Admin\Resources\BookingLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingLogs extends ListRecords
{
    protected static string $resource = BookingLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
