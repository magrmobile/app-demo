<?php

namespace App\Filament\Resources\Devices\Pages;

use App\Filament\Resources\Devices\DeviceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListDevices extends ListRecords
{
    protected static string $resource = DeviceResource::class;

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
