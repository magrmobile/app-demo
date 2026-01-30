<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $table = 'cat012';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class);
    }
}
