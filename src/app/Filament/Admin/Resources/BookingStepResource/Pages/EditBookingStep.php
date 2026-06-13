<?php

namespace App\Filament\Admin\Resources\BookingStepResource\Pages;

use App\Filament\Admin\Resources\BookingStepResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookingStep extends EditRecord
{
    protected static string $resource = BookingStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
