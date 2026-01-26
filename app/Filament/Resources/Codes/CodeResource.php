<?php

namespace App\Filament\Resources\Codes;

use App\Filament\Resources\Codes\Pages\CreateCode;
use App\Filament\Resources\Codes\Pages\EditCode;
use App\Filament\Resources\Codes\Pages\ListCodes;
use App\Filament\Resources\Codes\Schemas\CodeForm;
use App\Filament\Resources\Codes\Tables\CodesTable;
use App\Models\Code;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CodeResource extends Resource
{
    protected static ?string $model = Code::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Squares2x2;

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $modelLabel = 'Codigo';

    protected static ?string $navigationLabel = 'Codigos de Paro';

    protected static string|UnitEnum|null $navigationGroup = 'Configuraciones';

    public static function form(Schema $schema): Schema
    {
        return CodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CodesTable::configure($table);
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
            'index' => ListCodes::route('/'),
            'create' => CreateCode::route('/create'),
            'edit' => EditCode::route('/{record}/edit'),
        ];
    }
}
