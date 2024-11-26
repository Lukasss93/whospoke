<?php

namespace App\Models;

use App\Enums\MemberType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use LasseRafn\Initials\Initials;

class Member extends Model
{
    protected static $unguarded = true;
    protected $with = ['user'];
    protected $appends = ['initials', 'color'];

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

    public function initials(): Attribute
    {
        return Attribute::make(
            get: fn() => (new Initials)->name($this->name)->generate(),
        );
    }

    public function color(): Attribute
    {
        $colors = [
            '#B91C1C', //red
            '#C2410C', //orange
            '#B45309', //amber
            '#A16207', //yellow
            '#4D7C0F', //lime
            '#15803D', //green
            '#047857', //emerald
            '#0F766E', //teal
            '#0E7490', //cyan
            '#0369A1', //sky
            '#1D4ED8', //blue
            '#4338CA', //indigo
            '#6D28D9', //violet
            '#7E22CE', //purple
            '#A21CAF', //fuchsia
            '#BE185D', //pink
            '#BE123C', //rose
            '#44403C', //stone
            '#334155', //slate
        ];

        return Attribute::make(
            get: fn() => Arr::random($colors),
        );
    }
}
