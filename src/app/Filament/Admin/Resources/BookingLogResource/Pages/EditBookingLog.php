<?php

namespace App\Filament\Admin\Resources\BookingLogResource\Pages;

use App\Filament\Admin\Resources\BookingLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookingLog extends EditRecord
{
    protected static string $resource = BookingLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
