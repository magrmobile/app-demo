<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = "cat002";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
}