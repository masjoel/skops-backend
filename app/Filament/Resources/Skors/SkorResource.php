<?php

namespace App\Filament\Resources\Skors;

use App\Filament\Resources\Skors\Pages\CreateSkor;
use App\Filament\Resources\Skors\Pages\EditSkor;
use App\Filament\Resources\Skors\Pages\ListSkors;
use App\Filament\Resources\Skors\Pages\ViewSkor;
use App\Filament\Resources\Skors\Schemas\SkorForm;
use App\Filament\Resources\Skors\Schemas\SkorInfolist;
use App\Filament\Resources\Skors\Tables\SkorsTable;
use App\Models\Skor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SkorResource extends Resource
{
    protected static ?string $model = Skor::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'kode';
    protected static string|UnitEnum|null $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Skor';
    protected static ?string $pluralModelLabel = 'Skor';
    protected static ?string $modelLabel = 'Skor';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return SkorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SkorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SkorsTable::configure($table);
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
            'index' => ListSkors::route('/'),
            'create' => CreateSkor::route('/create'),
            'view' => ViewSkor::route('/{record}'),
            'edit' => EditSkor::route('/{record}/edit'),
        ];
    }
}
