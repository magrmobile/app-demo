<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class RetencionIVA extends Model
{
    protected $table = 'cat006';
    
    protected $primaryKey = 'id';
    
    protected $keyType = 'string';
}
