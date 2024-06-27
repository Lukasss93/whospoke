<?php

namespace App\Models;

use App\Events\EditorChangedEvent;
use App\Events\RoomChangedEvent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RoomEditor extends Pivot
{
    protected $table = 'room_editors';

    protected static function booted(): void
    {
        static::created(function (RoomEditor $roomEditor) {
            RoomChangedEvent::dispatch($roomEditor->room);
            EditorChangedEvent::dispatch($roomEditor);
        });

        static::deleted(function (RoomEditor $roomEditor) {
            RoomChangedEvent::dispatch($roomEditor->room);
            EditorChangedEvent::dispatch($roomEditor);
        });
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
