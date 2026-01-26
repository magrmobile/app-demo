<?php

namespace App\Filament\Resources\Stops\Tables;

use App\Models\Stop;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StopsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('machine.machine_name')
                    ->label('Maquina')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('operator.username')
                    ->label('Operador')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('product.product_name')
                    ->label('Producto')
                    ->sortable()
                    ->searchable(),
                ColorColumn::make('color.hex_code')
                    ->label('Color')
                    ->sortable(),
                TextColumn::make('code.description')
                    ->label('Codigo Paro')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('conversion.description')
                    ->label('Conversion')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('meters')
                    ->label('Metros')
                    ->numeric()
                    ->default(0)
                    ->sortable(),
                TextColumn::make('stop_datetime_start')
                    ->label('Inicio de Paro')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('stop_datetime_end')
                    ->label('Fin de Paro')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('duration')
                    ->label('Duracion')
                    ->state(function (Stop $record): string {
                        if (! $record->stop_datetime_start || ! $record->stop_datetime_end) {
                            return '-';
                        }

                        $seconds = Carbon::parse($record->stop_datetime_start)
                            ->diffInSeconds(Carbon::parse($record->stop_datetime_end));
                        $hours = intdiv($seconds, 3600);
                        $minutes = intdiv($seconds % 3600, 60);
                        $remainingSeconds = $seconds % 60;

                        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
                    }),
            ])
            ->filters([
                Filter::make('stop_datetime')
                    ->schema([
                        DateTimePicker::make('stop_datetime_start_from')
                            ->hiddenLabel()
                            ->native(false)
                            ->prefix('Inicio desde')
                            ->prefixIcon(Heroicon::CalendarDays)
                            ->closeOnDateSelection()
                            ->displayFormat('Y-m-d H:i')
                            ->format('Y-m-d H:i')
                            ->default(Carbon::today()->startOfDay()),
                        DateTimePicker::make('stop_datetime_end_to')
                            ->hiddenLabel()
                            ->native(false)
                            ->prefix('Fin hasta')
                            ->prefixIcon(Heroicon::CalendarDays)
                            ->closeOnDateSelection()
                            ->displayFormat('Y-m-d H:i')
                            ->format('Y-m-d H:i')
                            ->default(Carbon::today()->endOfDay()),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        $from = isset($data['stop_datetime_start_from'])
                            ? Carbon::parse($data['stop_datetime_start_from'])
                            : null;
                        $to = isset($data['stop_datetime_end_to'])
                            ? Carbon::parse($data['stop_datetime_end_to'])
                            : null;

                        return $query
                            ->when($from, fn (Builder $query): Builder => $query->where('stop_datetime_start', '>=', $from))
                            ->when($to, fn (Builder $query): Builder => $query->where('stop_datetime_end', '<=', $to));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['stop_datetime_start_from'] ?? null) {
                            $indicators[] = Indicator::make('Inicio desde '.Carbon::parse($data['stop_datetime_start_from'])->format('Y-m-d H:i'))
                                ->removeField('stop_datetime_start_from');
                        }

                        if ($data['stop_datetime_end_to'] ?? null) {
                            $indicators[] = Indicator::make('Fin hasta '.Carbon::parse($data['stop_datetime_end_to'])->format('Y-m-d H:i'))
                                ->removeField('stop_datetime_end_to');
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
