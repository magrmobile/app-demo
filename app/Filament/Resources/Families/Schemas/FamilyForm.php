<?php

namespace App\Filament\Resources\Families\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FamilyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([  
                        TextInput::make('family_name')
                            ->label('Nombre')
                            ->required(),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
