<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class QuickBooksUnits extends Model
{
    protected $table = 'qb_um';
    protected $primaryKey = 'um';
    protected $keyType = 'string';
    public $timestamps = false;
}
