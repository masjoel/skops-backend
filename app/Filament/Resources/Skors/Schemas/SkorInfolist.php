<?php

namespace App\Filament\Resources\Skors\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SkorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('urut')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('kode')
                    ->placeholder('-'),
                TextEntry::make('user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jenis')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('skor')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tindakan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('tipe')
                    ->badge(),
                TextEntry::make('jam')
                    ->dateTime()
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
