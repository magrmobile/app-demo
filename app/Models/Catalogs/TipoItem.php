<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class TipoItem extends Model
{
    protected $table = 'cat011';
    
    protected $primaryKey = 'id';
    
    protected $keyType = 'string';
}
