<?php

namespace App\Filament\Resources\Gurus\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Gurus\GuruResource;

class EditGuru extends EditRecord
{
    protected static string $resource = GuruResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterFill(): void
    {
        $customer = $this->record->customer;
        $guru = $this->record;

        $this->form->fill([
            'nama_customer' => $customer->nama,
            'hp_customer' => $customer->hp,
            'email_customer' => $customer->email,
            'nip' => $guru->nip,
            'mata_pelajaran' => $guru->mata_pelajaran
        ]);
    }


    /**
     * 🔹 Simpan data ke customer + guru
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $customer = $this->record->customer;
        $customer->update([
            'nama'  => $data['nama_customer'],
            'email' => $data['email_customer'] ?? null,
            'hp'    => $data['hp_customer'] ?? null,
        ]);

        unset($data['nama_customer'], $data['email_customer'], $data['hp_customer']);
        return $data;
    }
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Success')
            ->body('Data Guru berhasil diupdate.')
            ->success();
    }
}
