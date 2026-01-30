<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

abstract class CatalogBase extends Model
{
    /**
     * Nombre de la tabla (cat0xx)
     */
    protected static string $table;

    /**
     * TTL del cache (segundos)
     * 86400 = 1 dia
     */
    protected static int $ttl = 86400;

    /**
     * Obtener el valor (label) del catalogo
     */
    public static function label(?string $id): ?string
    {
        if (!$id) {
            return null;
        }

        return Cache::remember(
            static::cacheKey($id),
            static::$ttl,
            fn () => DB::table(static::$table)
                ->where('id', $id)
                ->value('valor')
        );
    }

    /**
     * Clave unica del cache
     */
    protected static function cacheKey(string $id): string
    {
        return 'mh:' . static::$table . ':' . $id;
    }

    /**
     * Invalida un registro puntual
     */
    public static function forget(?string $id): void
    {
        if ($id) {
            Cache::forget(static::cacheKey($id));
        }
    }

    /**
     * Invalida TODO el catalogo
     */
    public static function flush(): void
    {
        Cache::flush(); // simple (ver mejora abajo)
    }
}