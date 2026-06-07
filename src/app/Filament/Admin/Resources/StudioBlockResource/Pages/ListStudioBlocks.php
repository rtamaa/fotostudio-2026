<?php

namespace App\Filament\Admin\Resources\StudioBlockResource\Pages;

use App\Filament\Admin\Resources\StudioBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudioBlocks extends ListRecords
{
    protected static string $resource = StudioBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
