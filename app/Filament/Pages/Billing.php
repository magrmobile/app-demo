<?php

namespace App\Filament\Pages;

use App\Models\Catalogs\RecintoFiscal;
use App\Models\Catalogs\Regimen;
use App\Models\Catalogs\TipoDocumento;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class Billing extends Page implements HasSchemas
{
    Use InteractsWithSchemas;

    protected string $view = 'filament.pages.billing';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentArrowUp;

    protected static string|UnitEnum|null $navigationGroup = 'Facturacion';

    protected static ?string $title = 'Generar Facturacion (JSON)';

    protected static ?string $navigationLabel = 'Cargar CSV';

    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('type')
                            ->label('Tipo de Documento')
                            ->options(
                                TipoDocumento::query()
                                    ->where('active', true)
                                    ->get()
                                    ->mapWithKeys(fn ($item) => [
                                        $item->id => "{$item->abrv} - {$item->valor}",
                                    ])
                            )
                            ->native(false)
                            ->live()
                            ->preload()
                            ->required(),
                        Select::make('recintoFiscal')
                            ->label('Recinto Fiscal')
                            ->options(
                                RecintoFiscal::query()
                                    ->get()
                                    ->mapWithKeys(fn ($item) => [
                                        $item->id => "{$item->id} - {$item->valor}",
                                    ])
                            )
                            ->native(false)
                            ->preload()
                            ->visible(fn (Get $get) => in_array($get('type'), ['11'])),
                        Select::make('regimen')
                            ->label('Regimen de Exportacion')
                            ->options(
                                Regimen::query()
                                    ->get()
                                    ->mapWithKeys(fn ($item) => [
                                        $item->id => "{$item->id} - {$item->valor}",
                                    ])
                            )
                            ->default('EX-1.1000.000')
                            ->native(false)
                            ->preload()
                            ->visible(fn (Get $get) => in_array($get('type'), ['11'])),
                        Textarea::make('comments')
                            ->label('Observaciones'),
                        FileUpload::make('file')
                            ->label('Archivo Excel / CSV')
                            ->required()
                            ->disk('public')
                            ->directory('csv'),
                    ])
                    ->columnSpanFull()
            ])
            ->statePath('data');
    }
}
