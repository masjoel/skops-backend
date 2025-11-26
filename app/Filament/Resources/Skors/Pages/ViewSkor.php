<?php

namespace App\Filament\Resources\Skors\Pages;

use App\Filament\Resources\Skors\SkorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSkor extends ViewRecord
{
    protected static string $resource = SkorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
