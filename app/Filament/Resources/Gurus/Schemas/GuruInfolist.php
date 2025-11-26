<?php

namespace App\Filament\Resources\Gurus\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GuruInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('customer.nama')->label('Nama Guru'),
            TextEntry::make('customer.hp')->label('HP'),
            TextEntry::make('customer.email')->label('Email'),
            TextEntry::make('nip')->label('NIP'),
            TextEntry::make('mata_pelajaran')->label('Mata Pelajaran'),
        ]);
        // return $schema
        //     ->components([
        //         TextEntry::make('customer_id')
        //             ->numeric(),
        //         TextEntry::make('nip')
        //             ->placeholder('-'),
        //         TextEntry::make('mata_pelajaran')
        //             ->placeholder('-'),
        //         TextEntry::make('created_at')
        //             ->dateTime()
        //             ->placeholder('-'),
        //         TextEntry::make('updated_at')
        //             ->dateTime()
        //             ->placeholder('-'),
        //     ]);
    }
}
