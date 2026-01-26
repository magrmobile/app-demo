<?php

namespace App\Filament\Resources\Rounds\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class RoundForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        DatePicker::make('round_date')
                            ->label('Fecha'),
                        Select::make('machine_id')
                            ->label('Maquina')
                            ->relationship(name: 'machine', titleAttribute: 'machine_name')
                            ->required()
                            ->native(false)
                            ->preload(),
                        Select::make('product_id')
                            ->label('Producto')
                            ->relationship(name: 'product', titleAttribute: 'product_name')
                            ->required()
                            ->native(false)
                            ->preload(),
                        TextInput::make('production_speed')
                            ->label('Velocidad de produccion')
                            ->numeric(),
                        TextInput::make('produced_meters')
                            ->label('Metros producidos')
                            ->numeric()
                            ->inputMode('decimal'),
                        Select::make('code_id')
                            ->label('Codigo')
                            ->relationship(name: 'code', titleAttribute: 'description')
                            ->native(false)
                            ->live()
                            ->preload(),
                        Textarea::make('no_production_reason')
                            ->label('Motivo no Produccion')
                            ->visible(fn (Get $get) => $get('code_id') === 12)
                            ->columnSpanFull(),
                        Hidden::make('user_id'),
                        Hidden::make('shift'),
                        Hidden::make('warehouse'),
                        TimePicker::make('hour'),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
