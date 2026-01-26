<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'cat020';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
}
