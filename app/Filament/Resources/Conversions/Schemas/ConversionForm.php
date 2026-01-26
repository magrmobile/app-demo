<?php

namespace App\Filament\Resources\Conversions\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ConversionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('description')
                            ->label('Descripcion')
                            ->required(),
                        TextInput::make('factor')
                            ->required()
                            ->numeric()
                            ->inputMode('decimal'),
                        Radio::make('type')
                            ->label('Tipo')
                            ->required()
                            ->options([
                                'R' => 'Rollo',
                                'C' => 'Carrete'
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
