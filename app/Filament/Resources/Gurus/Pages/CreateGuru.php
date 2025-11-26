<?php

namespace App\Filament\Resources\Gurus\Pages;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Gurus\GuruResource;

class CreateGuru extends CreateRecord
{
    protected static string $resource = GuruResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $customer = Customer::create([
            'user_id' => Auth::id(),
            'nama'  => $data['nama_customer'],
            'email' => $data['email_customer'] ?? null,
            'hp'    => $data['hp_customer'] ?? null,
        ]);

        $data['customer_id'] = $customer->id;

        unset($data['nama_customer'], $data['email_customer'], $data['hp_customer']);
        return $data;
    }
    /**
     * Kustom notifikasi setelah berhasil disimpan
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Success')
            ->body('Data Guru berhasil ditambahkan.')
            ->success();
    }
    /**
     * Setelah berhasil create, langsung redirect ke halaman list.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
