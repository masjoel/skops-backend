<?php

namespace App\Filament\Resources\Perusahaans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PerusahaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('NamaClient'),
                Toggle::make('tampil')
                    ->required(),
                TextInput::make('NamaApp'),
                TextInput::make('VersiApp'),
                TextInput::make('DescApp'),
                TextInput::make('AlamatClient'),
                TextInput::make('Signature'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('Logo'),
                DateTimePicker::make('jam'),
                TextInput::make('mcad'),
                TextInput::make('init'),
            ]);
    }
}
