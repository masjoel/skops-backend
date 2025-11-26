<?php

namespace App\Filament\Resources\Skors\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SkorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('urut')
                    ->numeric(),
                TextInput::make('kode'),
                TextInput::make('user_id')
                    ->numeric(),
                Textarea::make('jenis')
                    ->columnSpanFull(),
                TextInput::make('skor')
                    ->numeric(),
                Textarea::make('tindakan')
                    ->columnSpanFull(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Select::make('tipe')
                    ->options(['pelanggaran' => 'Pelanggaran', 'reward' => 'Reward'])
                    ->default('pelanggaran')
                    ->required(),
                DateTimePicker::make('jam'),
            ]);
    }
}
