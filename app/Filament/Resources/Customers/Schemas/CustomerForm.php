<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('nama')
                    ->required(),
                Textarea::make('alamat')
                    ->columnSpanFull(),
                TextInput::make('kota'),
                TextInput::make('hp'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['Lancar' => 'Lancar', 'Macet' => 'Macet'])
                    ->default('Lancar')
                    ->required(),
            ]);
    }
}
