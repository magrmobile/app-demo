<?php

namespace App\Filament\Resources\Codes\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->unique()
                            ->numeric(),
                        TextInput::make('description')
                            ->required(),
                        Radio::make('type')
                            ->required()
                            ->options([
                                'Programado' => 'Programado',
                                'No Programado' => 'No Programado'
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
