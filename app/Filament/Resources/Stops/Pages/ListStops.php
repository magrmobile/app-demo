<?php

namespace App\Filament\Resources\Stops\Pages;

use App\Filament\Resources\Stops\StopResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListStops extends ListRecords
{
    protected static string $resource = StopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make('xlsx')
                ->label('Exportar XLSX')
                ->color('success')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->ignoreFormatting()
                        ->queue()
                        ->withChunkSize(2000)
                        ->except('created_at', 'updated_at')
                        ->withWriterType(Excel::XLSX),
                ]),
            ExportAction::make('csv')
                ->label('Exportar CSV')
                ->color('warning')
                ->exports([ExcelExport::make()->fromTable()->withWriterType(Excel::CSV)]),
        ];
    }
}
