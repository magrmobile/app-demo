<?php

namespace App\Filament\Resources\Rounds\Pages;

use App\Filament\Resources\Rounds\RoundResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListRounds extends ListRecords
{
    protected static string $resource = RoundResource::class;

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
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->ignoreFormatting()
                        ->queue()
                        ->withChunkSize(2000)
                        ->except('created_at', 'updated_at')
                        ->withWriterType(Excel::CSV),
                ]),
        ];
    }
}
