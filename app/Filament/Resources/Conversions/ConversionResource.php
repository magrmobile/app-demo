<?php

namespace App\Filament\Resources\Conversions;

use App\Filament\Resources\Conversions\Pages\CreateConversion;
use App\Filament\Resources\Conversions\Pages\EditConversion;
use App\Filament\Resources\Conversions\Pages\ListConversions;
use App\Filament\Resources\Conversions\Schemas\ConversionForm;
use App\Filament\Resources\Conversions\Tables\ConversionsTable;
use App\Models\Conversion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ConversionResource extends Resource
{
    protected static ?string $model = Conversion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Scale;

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $modelLabel = 'Conversion';

    protected static ?string $pluralModelLabel = 'Conversiones';

    protected static string|UnitEnum|null $navigationGroup = 'Configuraciones';

    public static function form(Schema $schema): Schema
    {
        return ConversionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConversionsTable::configure($table);
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
            'index' => ListConversions::route('/'),
            'create' => CreateConversion::route('/create'),
            'edit' => EditConversion::route('/{record}/edit'),
        ];
    }
}
