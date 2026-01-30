<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Municipio extends Model
{
    protected $table = 'cat013';

    protected $primaryKey = 'id';
    
    protected $keyType = 'string';

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }
}