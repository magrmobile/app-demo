<?php

namespace App\Filament\Resources\Dtes\Tables;

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

class DtesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha Creacion')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('codigoGeneracion')
                    ->label('Codigo Generacion')
                    ->searchable(),
                IconColumn::make('signed')
                    ->label('Firmado')
                    ->boolean(),
                IconColumn::make('received')
                    ->label('Recibido')
                    ->boolean(),
                IconColumn::make('invalidate')
                    ->label('Anulado')
                    ->boolean(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_at_from')
                            ->hiddenLabel()
                            ->native(false)
                            ->prefix('Desde')
                            ->prefixIcon(Heroicon::CalendarDays)
                            ->closeOnDateSelection()
                            ->default(now())
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d')
                            ->maxDate(now()),
                        DatePicker::make('created_at_to')
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
                        $from = isset($data['created_at_from']) ? Carbon::parse($data['created_at_from'])->startOfDay() : null;
                        $to = isset($data['created_at_to']) ? Carbon::parse($data['created_at_to'])->endOfDay() : null;

                        return $query
                            ->when($from && $to, fn ($q) => $q->whereBetween('created_at', [$from, $to]))
                            ->when($from && ! $to, fn ($q) => $q->where('created_at', '>=', $from))
                            ->when(! $from && $to, fn ($q) => $q->where('created_at', '<=', $to));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_at_from'] ?? null) {
                            $indicators[] = Indicator::make('Desde '.Carbon::parse($data['created_at_from'])->toFormattedDateString())
                                ->removeField('created_at_from');
                        }

                        if ($data['created_at_to'] ?? null) {
                            $indicators[] = Indicator::make('Hasta '.Carbon::parse($data['created_at_to'])->toFormattedDateString())
                                ->removeField('created_at_to');
                        }

                        return $indicators;
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->filtersFormColumns(1)
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
