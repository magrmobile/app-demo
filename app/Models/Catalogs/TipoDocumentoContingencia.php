<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentoContingencia extends Model
{
    protected $table = 'cat023';
    
    protected $primaryKey = 'id';
    
    protected $keyType = 'string';
}
