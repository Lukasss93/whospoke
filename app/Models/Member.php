<?php

namespace App\Models;

use App\Enums\MemberType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    protected static $unguarded = true;
    protected $with = ['user'];

    protected function casts()
    {
        return [
            'type' => MemberType::class,
            'status' => 'boolean',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'offline' => 'boolean',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
