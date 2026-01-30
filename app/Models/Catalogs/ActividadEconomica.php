<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActividadEconomica extends Model
{
    protected $table = 'cat019';

    protected $primaryKey = 'id';
    
    protected $keyType = 'string';
}
