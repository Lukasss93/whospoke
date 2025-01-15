<?php

namespace App\Http\Resources;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Member */
class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = new UserResource($this->whenLoaded('user'));
        $user->additional([
            'canEdit' => $this->user?->can('update', $this->room) ?? false,
        ]);

        return [
            'id' => $this->id,
            'room_id' => $this->room_id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'name' => $this->name,
            'initials' => $this->initials,
            'color' => $this->color,
            'status' => $this->status,
            'count' => $this->count,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'profession' => new ProfessionResource($this->whenLoaded('profession')),
            'user' => $user,
        ];
    }
}
