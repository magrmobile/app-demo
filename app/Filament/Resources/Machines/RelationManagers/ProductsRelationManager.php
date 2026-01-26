<?php

namespace App\Filament\Resources\Machines\RelationManagers;

use App\Filament\Resources\Machines\MachineResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Productos';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([
                TextColumn::make('product_name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pivot.speed')
                    ->label('Velocidad'),
                TextColumn::make('created_at')
                    ->label('Asignado')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->color('primary')
                    ->label('Asignar producto')
                    ->schema([
                        Select::make('recordId')
                            ->label('Producto')
                            ->options(
                                Product::query()->pluck('product_name', 'id')
                            )
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('pivot.speed')
                            ->label('Velocidad')
                            ->numeric()
                            ->required()
                            ->suffix('rpm'),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        TextInput::make('speed')
                            ->label('Velocidad')
                            ->numeric()
                            ->required()
                            ->suffix('rpm')
                            ->default(fn ($record) => $record?->pivot?->speed),
                    ])
                    ->action(function ($record, array $data) {
                        $this->getOwnerRecord()
                            ->products()
                            ->updateExistingPivot(
                                $record->id, 
                                ['speed' => $data['speed']]
                            );
                    }),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                   DetachBulkAction::make(), // Bulk actions can be added here
                ]),
            ]);
    }
}
