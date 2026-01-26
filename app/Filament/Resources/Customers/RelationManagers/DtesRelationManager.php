<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use Carbon\Carbon;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class DtesRelationManager extends RelationManager
{
    protected static string $relationship = 'dtes';

    protected static ?string $title = 'Documentos Electrónicos (DTEs)';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigoGeneracion')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('codigoGeneracion')
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
            ->filtersFormColumns(1)
            ->headerActions([
                ExportAction::make('xlsx')
                    ->label('Exportar XLSX')
                    ->color('success')
                    ->exports([ExcelExport::make()->fromTable()->withWriterType(Excel::XLSX)]),
                ExportAction::make('csv')
                    ->label('Exportar CSV')
                    ->color('warning')
                    ->exports([ExcelExport::make()->fromTable()->withWriterType(Excel::CSV)]),
            ])
            ->recordActions([
                // EditAction::make(),
                // DissociateAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
