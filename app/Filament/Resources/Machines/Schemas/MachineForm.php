<?php

namespace App\Filament\Resources\Machines\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MachineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('process_id')
                            ->label('Proceso')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->native(false)
                            ->relationship(name: 'process', titleAttribute: 'description'),
                        Select::make('device_id')
                            ->label('Dispositivo')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->native(false)
                            ->relationship(name: 'device', titleAttribute: 'device_name'),
                        TextInput::make('machine_name')
                            ->label('Nombre')
                            ->required(),
                        Radio::make('warehouse')
                            ->label('Nave')
                            ->required()
                            ->options([
                                'AL' => 'Aluminio',
                                'CU' => 'Cobre'
                            ])
                            ->required(),
                        Radio::make('location')
                            ->label('Ubicacion')
                            ->required()
                            ->options([
                                'Santo Tomas' => 'Santo Tomas', 
                                'Santa Elena' => 'Santa Elena'
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
