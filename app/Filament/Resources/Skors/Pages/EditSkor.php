<?php

namespace App\Filament\Resources\Skors\Pages;

use App\Filament\Resources\Skors\SkorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSkor extends EditRecord
{
    protected static string $resource = SkorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
