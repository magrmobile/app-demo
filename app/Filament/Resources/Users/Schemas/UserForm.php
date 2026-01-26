<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('supervisor_id')
                    ->numeric(),
                TextInput::make('process_id')
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('username'),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('active_user')
                    ->options(['enabled' => 'Enabled', 'disabled' => 'Disabled'])
                    ->default('enabled'),
                TextInput::make('role'),
                Toggle::make('active')
                    ->required(),
                Select::make('shift')
                    ->options(['D' => 'D', 'N' => 'N']),
                Select::make('warehouse')
                    ->options(['AL' => 'A l', 'CU' => 'C u']),
                TextInput::make('numDocumento'),
            ]);
    }
}
