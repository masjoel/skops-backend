<?php

namespace App\Filament\Resources\Jurusans\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;

class JurusanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('jurusan'),
                TextInput::make('kode'),
            ]);
    }
}
