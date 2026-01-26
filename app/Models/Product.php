<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function machines(): BelongsToMany
    {
        return $this->belongsToMany(Machine::class)
            ->withPivot('speed')
            ->withTimestamps();
    }
}
