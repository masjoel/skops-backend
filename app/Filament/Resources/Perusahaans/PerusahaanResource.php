<?php

namespace App\Filament\Resources\Perusahaans;

use UnitEnum;
use BackedEnum;
use App\Models\Perusahaan;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Perusahaans\Pages\EditPerusahaan;
use App\Filament\Resources\Perusahaans\Pages\ViewPerusahaan;
use App\Filament\Resources\Perusahaans\Pages\ListPerusahaans;
use App\Filament\Resources\Perusahaans\Pages\CreatePerusahaan;
use App\Filament\Resources\Perusahaans\Schemas\PerusahaanForm;
use App\Filament\Resources\Perusahaans\Tables\PerusahaansTable;
use App\Filament\Resources\Perusahaans\Schemas\PerusahaanInfolist;

class PerusahaanResource extends Resource
{
    protected static ?string $model = Perusahaan::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'NamaClient';
    protected static string|UnitEnum|null $navigationGroup = 'Setting';
    protected static ?string $navigationLabel = 'Sekolah';
    protected static ?string $pluralModelLabel = 'Sekolah';
    protected static ?string $modelLabel = 'Sekolah';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user'])
            ->whereHas('user', function ($query) {
                $query->where('level', 'administrator');
            });
    }

    public static function form(Schema $schema): Schema
    {
        return PerusahaanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PerusahaanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerusahaansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPerusahaans::route('/'),
            'create' => CreatePerusahaan::route('/create'),
            'view' => ViewPerusahaan::route('/{record}'),
            'edit' => EditPerusahaan::route('/{record}/edit'),
        ];
    }
}
