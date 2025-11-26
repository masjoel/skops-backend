<?php

namespace App\Filament\Resources\Gurus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GuruForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_customer')
                    ->label('Nama Guru')
                    ->required(),

                TextInput::make('hp_customer')
                    ->label('Nomor HP')
                    ->numeric()
                    ->nullable(),

                TextInput::make('email_customer')
                    ->label('Email')
                    ->email()
                    ->nullable(),

                TextInput::make('nip')->label('NIP')->nullable(),
                TextInput::make('mata_pelajaran')->label('Mata Pelajaran')->nullable(),
            ]);
    }
}
