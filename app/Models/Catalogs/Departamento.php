<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Departamento extends Model
{
    protected $table = 'cat012';

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

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class);
    }
}
