<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Catalogs\Departamento;
use App\Models\Catalogs\Municipio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('nit')
                            ->label('NIT')
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('nrc')
                            ->label('NRC')
                            ->columnSpan(2),
                        TextInput::make('nombre')
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('nombreComercial')
                            ->label('Nombre Comercial')
                            ->required()
                            ->columnSpan(2),
                        Select::make('codActividad')
                            ->label('Actividad Economica')
                            ->required()
                            ->relationship(name: 'activity_code', titleAttribute: 'valor')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} - {$record->valor}")
                            ->searchable(['id', 'valor'])
                            ->native(false)
                            ->preload()
                            ->columnSpanFull(),
                        Select::make('tipoEstablecimiento')
                            ->label('Tipo Establecimiento')
                            ->relationship(name: 'tipo_establecimiento', titleAttribute: 'valor')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} - {$record->valor}")
                            ->searchable(['id', 'valor'])
                            ->native(false)
                            ->preload(),
                        Select::make('codPais')
                            ->label('Codigo Pais')
                            ->relationship(name: 'pais', titleAttribute: 'valor')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} - {$record->valor}")
                            ->searchable(['id', 'valor'])
                            ->native(false)
                            ->preload(),
                        Select::make('departamento_id')
                            ->label('Departamento')
                            ->visible(fn(Get $get) => $get('codPais') == 'SV')
                            ->required(fn(Get $get) => $get('codPais') == 'SV')
                            ->searchable()
                            ->native(false)
                            ->options(Departamento::query()->pluck('valor','id'))
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $departamento = Departamento::query()->where('id', $state)->first();
                                $set('state', $departamento->valor);
                            })
                            ->placeholder('Seleccionar Departamento'),
                        Select::make('municipio_id')
                            ->label('Municipio')
                            ->visible(fn(Get $get) => $get('codPais') == 'SV')
                            ->required(fn(Get $get) => $get('codPais') == 'SV')
                            ->searchable()
                            ->native(false)
                            ->options(fn (Get $get): Collection => Municipio::query()
                                ->where('departamento_id', $get('departamento_id'))
                                ->pluck('valor', 'id'))
                            ->placeholder('Seleccionar Municipio')
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $departamento_id = $get('departamento_id');
                                $municipio = Municipio::query()
                                                ->where('departamento_id', $departamento_id)
                                                ->where('id', $state)
                                                ->first();
                                $set('city', $municipio->valor);
                            }),
                        TextInput::make('complemento')
                            ->columnSpanFull(),
                        Select::make('codDomiciliado')
                            ->relationship(name: 'cod_domiciliado', titleAttribute: 'valor')
                            ->label('Codigo Domiciliado')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} - {$record->valor}")
                            ->searchable(['id', 'valor'])
                            ->native(false)
                            ->preload(),
                        TextInput::make('codigoMH')
                            ->label('Codigo Ministerio de Hacienda'),
                        TextInput::make('puntoVentaMH')
                            ->label('Punto de Venta Ministerio de Hacienda'),
                        Select::make('bienTitulo')
                            ->relationship(name: 'titulo_bien', titleAttribute: 'valor')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} - {$record->valor}")
                            ->label('Bien Titulo')
                            ->searchable(['id', 'valor'])
                            ->native(false)
                            ->preload(),
                        Select::make('tipoPersona')
                            ->relationship(name: 'tipo_persona', titleAttribute: 'valor')
                            ->label('Tipo Persona')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} - {$record->valor}")
                            ->searchable(['id', 'valor'])
                            ->native(false)
                            ->preload(),
                        TextInput::make('telefono')
                            ->tel(),
                        TextInput::make('correo')
                            ->label('Correo Electronico')
                            ->email(),
                        Select::make('category_id')
                            ->label('Categoria Cliente')
                            ->required()
                            ->relationship(name: 'customer_category', titleAttribute: 'category_name')
                            ->native(false)
                            ->preload(),
                        TextInput::make('nombre_contacto')
                            ->label('Nombre Contacto'),
                        Select::make('tipodoc_contacto')
                            ->relationship(name: 'tipo_documento', titleAttribute: 'valor')
                            ->label('Tipo Documento Contacto')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} - {$record->valor}")
                            ->searchable(['id', 'valor'])
                            ->native(false)
                            ->preload(),
                        TextInput::make('numdoc_contacto')
                            ->label('Numero Documento Contacto')
                            ->numeric(),
                        Hidden::make('created_by'),
                ])
                ->columns(4)
                ->columnSpanFull(),
            ]);
    }
}
