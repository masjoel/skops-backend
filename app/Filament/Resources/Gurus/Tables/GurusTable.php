<?php

namespace App\Filament\Resources\Gurus\Tables;

use Faker\Core\Color;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\ExportAction;

class GurusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.nama')
                    ->label('Nama Guru')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.hp')
                    ->label('HP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('info'), // warna hijau
                // DeleteAction::make()
                //     ->label('Hapus')
                //     ->icon('heroicon-m-trash')
                //     ->color('danger')
                //     ->requiresConfirmation()
                //     ->modalHeading('Hapus Data Guru')
                //     ->modalDescription('Apakah Anda yakin ingin menghapus guru ini?')
                //     ->modalSubmitActionLabel('Ya, Hapus')
                //     ->modalCancelActionLabel('Batal'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Guru')
                    ->icon('heroicon-o-plus')
                    ->color('primary'),
                ExportAction::make()
                    ->label('Excel')
                    ->color('success')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename('Data-Guru-' . now()->format('Y-m-d')),
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
