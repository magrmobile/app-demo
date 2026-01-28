<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AmbienteDestino extends Model
{
    protected $table = 'cat001';

    protected $primaryKey = 'id';
    
    protected $keyType = 'string';

    public static function label(?string $id): ?string
    {
        if (!$id) {
            return null;
        }

        return DB::table(static::$table)
            ->where('id', $id)
            ->value('valor');
    }
}
