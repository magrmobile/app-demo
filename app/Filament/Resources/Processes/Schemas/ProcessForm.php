<?php

namespace App\Filament\Resources\Processes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProcessForm
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
                    ])
                    ->columnSpanFull()
            ]);
    }
}
