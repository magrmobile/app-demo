<?php

namespace App\Filament\Resources\Colors\Pages;

use App\Filament\Resources\Colors\ColorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListColors extends ListRecords
{
    protected static string $resource = ColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make('xlsx')
                ->label('Exportar XLSX')
                ->color('success')
                ->exports([ExcelExport::make()->fromTable()->withWriterType(Excel::XLSX)]),
            ExportAction::make('csv')
                ->label('Exportar CSV')
                ->color('warning')
                ->exports([ExcelExport::make()->fromTable()->withWriterType(Excel::CSV)]),
        ];
    }
}
