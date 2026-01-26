<?php

namespace App\Filament\Resources\Stops\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StopForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('machine_id')
                    ->required()
                    ->numeric(),
                TextInput::make('operator_id')
                    ->required()
                    ->numeric(),
                TextInput::make('product_id')
                    ->numeric(),
                TextInput::make('color_id')
                    ->numeric(),
                TextInput::make('code_id')
                    ->required()
                    ->numeric(),
                TextInput::make('conversion_id')
                    ->numeric(),
                TextInput::make('quantity')
                    ->numeric(),
                TextInput::make('meters')
                    ->numeric(),
                Textarea::make('comment')
                    ->columnSpanFull(),
                DateTimePicker::make('stop_datetime_start'),
                DateTimePicker::make('stop_datetime_end'),
            ]);
    }
}
