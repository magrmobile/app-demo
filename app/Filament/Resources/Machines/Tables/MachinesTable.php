<?php

namespace App\Filament\Resources\Machines\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MachinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('machine_name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('device.device_name')
                    ->label('Dispositivo')
                    ->sortable(),
                TextColumn::make('process.description')
                    ->label('Proceso')
                    ->sortable(),
                TextColumn::make('warehouse')
                    ->label('Nave')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'AL' => 'gray',
                        'CU' => 'warning'
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location')
                    ->label('Ubicacion')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
