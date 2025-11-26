<?php

namespace App\Filament\Resources\Skors\Pages;

use App\Filament\Resources\Skors\SkorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSkors extends ListRecords
{
    protected static string $resource = SkorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
