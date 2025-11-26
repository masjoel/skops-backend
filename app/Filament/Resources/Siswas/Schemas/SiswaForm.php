<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('nis'),
                TextInput::make('nisn'),
                Select::make('kelas')
                    ->options([
            'PAUD' => 'P a u d',
            'TK' => 'T k',
            'I' => 'I',
            'II' => 'I i',
            'III' => 'I i i',
            'IV' => 'I v',
            'V' => 'V',
            'VI' => 'V i',
            'VII' => 'V i i',
            'VIII' => 'V i i i',
            'IX' => 'I x',
            'X' => 'X',
            'XI' => 'X i',
            'XII' => 'X i i',
            'XIII' => 'X i i i',
        ])
                    ->default('I')
                    ->required(),
                Select::make('ext')
                    ->options([
            '-' => ' ',
            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
            'D' => 'D',
            'E' => 'E',
            'F' => 'F',
            'G' => 'G',
            'H' => 'H',
            'I' => 'I',
            'J' => 'J',
            'K' => 'K',
            'L' => 'L',
            'M' => 'M',
            'N' => 'N',
            'O' => 'O',
            'P' => 'P',
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
            11 => '11',
            12 => '12',
        ])
                    ->default('-')
                    ->required(),
                TextInput::make('jurusan'),
            ]);
    }
}
