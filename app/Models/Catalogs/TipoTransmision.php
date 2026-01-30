<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class TipoTransmision extends Model
{
    protected $table = 'cat004';
    
    protected $primaryKey = 'id';
    
    protected $keyType = 'string';
}
