<?php

namespace App\Filament\Resources\Dtes\Pages;

use App\Filament\Resources\Dtes\DteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListDtes extends ListRecords
{
    protected static string $resource = DteResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
