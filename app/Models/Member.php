<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    protected static $unguarded = true;

    protected function casts()
    {
        return [
            'status' => 'boolean',
            'started_at' => 'timestamp',
            'ended_at' => 'timestamp',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
