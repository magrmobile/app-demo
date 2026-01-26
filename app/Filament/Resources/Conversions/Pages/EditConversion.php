<?php

namespace App\Filament\Resources\Conversions\Pages;

use App\Filament\Resources\Conversions\ConversionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConversion extends EditRecord
{
    protected static string $resource = ConversionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
