<?php

namespace App\Models;

use App\Enums\MemberRole;
use App\Enums\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected static $unguarded = true;
    protected $with = ['members'];

    protected function casts(): array
    {
        return [
            'type' => RoomType::class,
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'room_id');
    }

    public function editors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'room_editors', 'room_id', 'user_id');
    }

    public function canManage(?User $user): bool
    {
        return $this->isOwner($user) || $this->isEditor($user);
    }

    public function isOwner(?User $user): bool
    {
        return $this->user_id === $user?->id;
    }

    public function isEditor(?User $user): bool
    {
        return $this->editors()->where('user_id', $user?->id)->exists();
    }

    public function getUserRole(?User $user): MemberRole
    {
        return match (true) {
            $this->isOwner($user) => MemberRole::OWNER,
            $this->isEditor($user) => MemberRole::EDITOR,
            default => MemberRole::DEFAULT,
        };
    }
}
