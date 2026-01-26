<?php

namespace App\Models;

use App\Models\Catalogs\ActividadEconomica;
use App\Models\Catalogs\CustomerCategory;
use App\Models\Catalogs\Departamento;
use App\Models\Catalogs\DomicilioFiscal;
use App\Models\Catalogs\Municipio;
use App\Models\Catalogs\Pais;
use App\Models\Catalogs\TipoDocumentoReceptor;
use App\Models\Catalogs\TipoEstablecimiento;
use App\Models\Catalogs\TipoPersona;
use App\Models\Catalogs\TituloBienes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    public function activity_code(): BelongsTo
    {
        return $this->belongsTo(ActividadEconomica::class, 'codActividad', 'id');
    }

    public function customer_category(): BelongsTo
    {
        return $this->belongsTo(CustomerCategory::class, 'category_id', 'id');
    }

    public function tipo_establecimiento(): BelongsTo
    {
        return $this->belongsTo(TipoEstablecimiento::class, 'tipoEstablecimiento', 'id');
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id');
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'codPais', 'id');
    }

    public function cod_domiciliado(): BelongsTo
    {
        return $this->belongsTo(DomicilioFiscal::class, 'codDomiciliado', 'id');
    }

    public function titulo_bien(): BelongsTo
    {
        return $this->belongsTo(TituloBienes::class, 'bienTitulo', 'id');
    }

    public function tipo_persona(): BelongsTo
    {
        return $this->belongsTo(TipoPersona::class, 'tipoPersona', 'id');
    }

    public function tipo_documento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoReceptor::class, 'tipodoc_contacto', 'id');
    }

    public function dtes(): HasMany
    {
        return $this->hasMany(Dte::class, 'customer_id', 'id');
    }
}
