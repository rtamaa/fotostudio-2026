<?php

namespace App\Filament\Admin\Resources\BookingStepResource\Pages;

use App\Filament\Admin\Resources\BookingStepResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingSteps extends ListRecords
{
    protected static string $resource = BookingStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
