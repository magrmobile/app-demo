<?php

namespace App\Filament\Resources\Dtes;

use App\Filament\Resources\Dtes\Pages\CreateDte;
use App\Filament\Resources\Dtes\Pages\EditDte;
use App\Filament\Resources\Dtes\Pages\ListDtes;
use App\Filament\Resources\Dtes\Schemas\DteForm;
use App\Filament\Resources\Dtes\Tables\DtesTable;
use App\Models\Dte;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DteResource extends Resource
{
    protected static ?string $model = Dte::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static ?string $recordTitleAttribute = 'codigoGeneracion';

    protected static string|UnitEnum|null $navigationGroup = 'Facturacion';

    protected static ?string $navigationLabel = 'Documentos Electrónicos';

    protected static ?string $modelLabel = 'Documento Electrónico';

    protected static ?string $pluralModelLabel = 'Documentos Electrónicos (DTEs)';

    public static function form(Schema $schema): Schema
    {
        return DteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DtesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDtes::route('/'),
            'create' => CreateDte::route('/create'),
            'edit' => EditDte::route('/{record}/edit'),
        ];
    }
}
