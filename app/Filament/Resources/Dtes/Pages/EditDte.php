<?php

namespace App\Filament\Resources\Dtes\Pages;

use App\Filament\Resources\Dtes\DteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDte extends EditRecord
{
    protected static string $resource = DteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
