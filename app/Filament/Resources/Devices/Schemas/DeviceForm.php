<?php

namespace App\Filament\Resources\Devices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('serial_number')
                            ->label('Numero de Serie')
                            ->unique()
                            ->required(),
                        TextInput::make('mac_address')
                            ->label('MAC Address')
                            ->macAddress()
                            ->unique(),
                        TextInput::make('device_name')
                            ->label('Nombre')
                            ->unique()
                            ->required(),
                        TextInput::make('description')
                            ->label('Descripcion'),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
