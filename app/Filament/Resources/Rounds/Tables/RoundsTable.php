<?php

namespace App\Filament\Resources\Rounds\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RoundsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('machine.machine_name')
                    ->label('Maquina')
                    ->sortable(),
                TextColumn::make('user.username')
                    ->label('Supervisor')
                    ->sortable(),
                IconColumn::make('shift')
                    ->label('Turno')
                    ->icon(fn (string $state): Heroicon => match ($state) {
                        'D' => Heroicon::Sun,
                        'N' => Heroicon::Moon
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'D' => 'warning',
                        'N' => 'gray'
                    }),
                TextColumn::make('warehouse')
                    ->label('Nave')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'CU' => 'warning',
                        'AL' => 'gray'
                    }),
                TextColumn::make('produced_meters')
                    ->label('Metros')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('production_speed')
                    ->label('Velocidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('product.product_name')
                    ->label('Producto')
                    ->sortable(),
                TextColumn::make('hour')
                    ->label('Hora')
                    ->time()
                    ->sortable(),
                TextColumn::make('round_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('code.description')
                    ->label('Codigo')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('round_date')
                    ->schema([
                        DatePicker::make('round_date_from')
                            ->hiddenLabel()
                            ->native(false)
                            ->prefix('Desde')
                            ->prefixIcon(Heroicon::CalendarDays)
                            ->closeOnDateSelection()
                            ->default(now())
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d')
                            ->maxDate(now()),
                        DatePicker::make('round_date_to')
                            ->hiddenLabel()
                            ->native(false)
                            ->prefix('Hasta')
                            ->prefixIcon(Heroicon::CalendarDays)
                            ->closeOnDateSelection()
                            ->default(now())
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d')
                            ->maxDate(now()),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        $from = isset($data['round_date_from']) ? Carbon::parse($data['round_date_from'])->startOfDay() : null;
                        $to = isset($data['round_date_to']) ? Carbon::parse($data['round_date_to'])->endOfDay() : null;

                        return $query
                            ->when($from && $to, fn ($q) => $q->whereBetween('round_date', [$from, $to]))
                            ->when($from && ! $to, fn ($q) => $q->where('round_date', '>=', $from))
                            ->when(! $from && $to, fn ($q) => $q->where('round_date', '<=', $to));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['round_date_from'] ?? null) {
                            $indicators[] = Indicator::make('Desde '.Carbon::parse($data['round_date_from'])->toFormattedDateString())
                                ->removeField('round_date_from');
                        }

                        if ($data['round_date_to'] ?? null) {
                            $indicators[] = Indicator::make('Hasta '.Carbon::parse($data['round_date_to'])->toFormattedDateString())
                                ->removeField('round_date_to');
                        }

                        return $indicators;
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(1)
            ->persistFiltersInSession()
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
