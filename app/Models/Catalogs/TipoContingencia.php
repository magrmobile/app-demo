<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class TipoContingencia extends Model
{
    protected $table = 'cat005';
    
    protected $primaryKey = 'id';
    
    protected $keyType = 'string';
}
