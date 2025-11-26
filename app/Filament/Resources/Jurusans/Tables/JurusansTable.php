<?php

namespace App\Filament\Resources\Jurusans\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class JurusansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // show if role==admin
                TextColumn::make('perusahaan.NamaClient')
                    ->label('Nama Sekolah')
                    ->sortable()
                    ->searchable()
                    ->visible(fn() => Auth::user()?->level === 'administrator'),
                TextColumn::make('jurusan')
                    ->searchable(),
                TextColumn::make('kode')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->color('info'), // warna hijau
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
