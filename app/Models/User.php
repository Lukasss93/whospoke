<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use LasseRafn\Initials\Initials;
use Throwable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected static $unguarded = true;
    protected $appends = ['avatar', 'initials', 'color'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function managedRooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_editors', 'user_id', 'room_id')
            ->using(RoomEditor::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function avatar(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (Storage::disk('public')->exists("avatars/{$this->id}.jpg")) {
                    return Cache::rememberForever("avatar.{$this->id}", function () {
                        return asset("storage/avatars/{$this->id}.jpg") . '?v=' . time();
                    });
                }
                return null;
            },
        );
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

    public function saveAvatar(?string $avatarUrl): void
    {
        if (empty($avatarUrl)) {
            return;
        }

        try {
            Cache::forget("avatar.{$this->id}");

            $avatarPath = sprintf("avatars/%s.jpg", $this->id);
            $avatarContent = file_get_contents($avatarUrl);
            Storage::disk('public')->put($avatarPath, $avatarContent);
        } catch (Throwable $e) {
            // Do not save the avatar if an error occurs
        }
    }
}
