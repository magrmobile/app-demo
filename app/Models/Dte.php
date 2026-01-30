<?php

namespace App\Models;

use App\Models\Catalogs\TipoDocumento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dte extends Model
{
    protected $fillable = [
        'customer_id',
        'numeroControl',
        'codigoGeneracion',
        'file_csv',
        'json_dte',
        'created_by',
        'tipoDte',
    ];
    
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipoDte');
    }
}
