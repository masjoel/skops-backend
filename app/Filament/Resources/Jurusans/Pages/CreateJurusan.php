<?php

namespace App\Filament\Resources\Jurusans\Pages;

use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Jurusans\JurusanResource;

class CreateJurusan extends CreateRecord
{
    protected static string $resource = JurusanResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['perusahaan_id'] = Auth::user()?->perusahaan_id;
        return $data;
    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Success')
            ->body('Data Jurusan berhasil ditambahkan.')
            ->success();
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
