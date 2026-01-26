<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class Incoterms extends Model
{
    protected $table = 'cat031';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
}
