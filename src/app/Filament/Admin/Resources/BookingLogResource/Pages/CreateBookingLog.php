<?php

namespace App\Filament\Admin\Resources\BookingLogResource\Pages;

use App\Filament\Admin\Resources\BookingLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookingLog extends CreateRecord
{
    protected static string $resource = BookingLogResource::class;
}
