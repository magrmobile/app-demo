<?php

namespace App\Filament\Pages;

use App\Models\Catalogs\RecintoFiscal;
use App\Models\Catalogs\Regimen;
use App\Models\Catalogs\TipoDocumento;
use App\Services\Billing\BillingService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class Billing extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowUpCircle;

    protected static ?string $navigationLabel = 'Cargar CSV';
    
    protected static ?string $title = 'Generar DTE desde CSV';
    
    protected static string|UnitEnum|null $navigationGroup = 'Facturacion';

    protected string $view = 'filament.pages.billing';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make([
                    Form::make([
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
                    ->footer([
                        Actions::make([
                            Action::make('process')
                                ->label('Procesar Archivo')
                                ->action('process')
                        ])
                    ])
                ])
            ]);
    }

    public function process(BillingService $service): void
    {
        try {
            $filePath = Storage::disk('public')->path($this->data['file']);

            $result = $service->handle([
                'type' => $this->data['type'],
                'file_path' => $filePath,
                'file_original_name' => basename($this->data['file']),
                'comments' => $this->data['comments'] ?? null,
                'recintoFiscal' => $this->data['recintoFiscal'] ?? null,
                'regimen' => $this->data['regimen'] ?? null,
            ]);

            Notification::make()
                ->title('Proceso completado')
                ->body($result->confirmMessage)
                ->success()
                ->send();

            $this->form->fill([]);
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Error al procesar')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
