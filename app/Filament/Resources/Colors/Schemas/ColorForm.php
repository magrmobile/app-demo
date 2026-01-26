<?php

namespace App\Filament\Resources\Colors\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ColorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required(),
                        ColorPicker::make('hex_code')
                            ->required()
                            ->label('Color'),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
