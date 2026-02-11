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
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use UnitEnum;

class Billing extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowUpCircle;

    protected static ?string $navigationLabel = 'Cargar CSV';
    
    protected static ?string $title = 'Generar DTE desde CSV';
    
    protected static string|UnitEnum|null $navigationGroup = 'Facturacion';

    protected string $view = 'filament.pages.billing';

    public ?string $pdfFileName = null;

    public bool $showPreview = false;

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
                            ->acceptedFileTypes([
                                'text/csv',
                                'text/plain',
                                'application/csv',
                                'application/vnd.ms-excel',
                                'application/octet-stream',
                            ])
                            ->multiple(false)
                            ->storeFiles(false),
                    ])
                    ->footer([
                        Actions::make([
                            Action::make('process')
                                ->label('Procesar Archivo')
                                ->action('process'),
                            Action::make('preview')
                                ->label('Ver PDF')
                                ->icon(Heroicon::Eye)
                                ->visible(fn () => $this->pdfFileName !== null)
                                ->modalHeading('Vista previa del PDF')
                                ->modalWidth('7x1')
                                ->modalSubmitAction(false)
                                ->modalCancelActionLabel('Cerrar')
                                ->modalContent(fn () => view(
                                    'filament.billing.pdf-preview',
                                    ['filename' => $this->pdfFileName]
                                )),
                        ])
                    ])
                ]),
                //Section::make('Vista preliminar', [
                    View::make('filament.billing.pdf-preview')
                        ->viewData(['pdfFilename' => $this->pdfFileName])
                        ->columnSpanFull()
                //])
                //->columnSpanFull()
            ]);
    }

    public function process(BillingService $service): void
    {
        try {
            /** @var TemporaryUploadedFile $tmp */
            $tmp = collect($this->data['file'])->first();

            if (!$tmp instanceof TemporaryUploadedFile) {
                throw new \RuntimeException('Archivo invalido');
            }

            $relativePath = $tmp->storeAs(
                'csv',
                $tmp->getClientOriginalName(),
                'public'
            );

            //dd($relativePath);

            $absolutePath = Storage::disk('public')->path($relativePath);

            $result = $service->handle([
                'type' => $this->data['type'],
                'file_path' => $absolutePath,
                'file_original_name' => $tmp->getClientOriginalName(),
                'comments' => $this->data['comments'] ?? null,
                'recintoFiscal' => $this->data['recintoFiscal'] ?? null,
                'regimen' => $this->data['regimen'] ?? null,
            ]);

            // Guardamos PDF para preview
            $this->pdfFileName = $result->pdfFileName;
            $this->showPreview = true;

            Notification::make()
                ->title('Documento generado')
                ->body('Puedes previsualizar el PDF antes de continuar')
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
