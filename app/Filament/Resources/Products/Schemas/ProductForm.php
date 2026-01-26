<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('product_name')
                            ->label('Nombre')
                            ->required(),
                        Select::make('family_id')
                            ->label('Familia')
                            ->relationship(name: 'family', titleAttribute: 'family_name')
                            ->native(false)
                            ->preload(),
                        Select::make('process_id')
                            ->label('Proceso')
                            ->relationship(name: 'process', titleAttribute: 'description')
                            ->native(false)
                            ->preload(),
                        Select::make('metal_type')
                            ->label('Tipo de Metal')
                            ->required()
                            ->native(false)
                            ->options([
                                'AL' => 'AL',
                                'AL S8000' => 'AL S8000',
                                'CCA' => 'CCA',
                                'CU' => 'CU',
                                'CCS' => 'CCS'
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
