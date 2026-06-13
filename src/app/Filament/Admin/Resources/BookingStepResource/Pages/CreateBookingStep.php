<?php

namespace App\Filament\Admin\Resources\BookingStepResource\Pages;

use App\Filament\Admin\Resources\BookingStepResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookingStep extends CreateRecord
{
    protected static string $resource = BookingStepResource::class;
}
