<?php

namespace App\Filament\Resources\Perusahaans\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PerusahaanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('NamaClient')
                    ->placeholder('-'),
                IconEntry::make('tampil')
                    ->boolean(),
                TextEntry::make('NamaApp')
                    ->placeholder('-'),
                TextEntry::make('VersiApp')
                    ->placeholder('-'),
                TextEntry::make('DescApp')
                    ->placeholder('-'),
                TextEntry::make('AlamatClient')
                    ->placeholder('-'),
                TextEntry::make('Signature')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('Logo')
                    ->placeholder('-'),
                TextEntry::make('jam')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('mcad')
                    ->placeholder('-'),
                TextEntry::make('init')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
