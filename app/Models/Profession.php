<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profession extends Model
{
    protected static $unguarded = true;

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
