<?php

namespace App\Http\Controllers;

use App\Enums\MemberType;
use App\Events\RoomChangedEvent;
use App\Http\Requests\LinkMemberToUserRequest;
use App\Http\Requests\UpdateMemberTypeRequest;
use App\Http\Requests\UpdateMemberStatusRequest;
use App\Models\Member;

class MemberController extends Controller
{
    public function setMemberStatus(UpdateMemberStatusRequest $request, Member $member)
    {
        $this->authorize('update', $member->room);

        $status = $request->boolean('status');

        $member->status = $status;
        $member->save();

        $member->room->refresh();

        RoomChangedEvent::dispatch($member->room);
    }

    public function setMemberType(UpdateMemberTypeRequest $request, Member $member)
    {
        $this->authorize('update', $member->room);

        $member->type = $request->enum('type', MemberType::class);
        $member->save();

        $member->room->refresh();

        RoomChangedEvent::dispatch($member->room);
    }

    public function resetTime(Member $member)
    {
        $this->authorize('update', $member->room);

        $member->started_at = null;
        $member->ended_at = null;
        $member->save();

        $member->room->refresh();

        RoomChangedEvent::dispatch($member->room);
    }

    public function startTime(Member $member)
    {
        $this->authorize('update', $member->room);

        $member->started_at = now();
        $member->save();

        $member->room->refresh();

        RoomChangedEvent::dispatch($member->room);
    }

    public function endTime(Member $member)
    {
        $this->authorize('update', $member->room);

        $member->ended_at = now();
        $member->status = true;
        $member->save();

        $member->room->refresh();

        RoomChangedEvent::dispatch($member->room);
    }

    public function resetCount(Member $member)
    {
        $this->authorize('update', $member->room);

        $member->count = 0;
        $member->save();

        $member->room->refresh();

        RoomChangedEvent::dispatch($member->room);
    }

    public function incrementCount(Member $member)
    {
        $this->authorize('update', $member->room);

        $member->increment('count');

        $member->room->refresh();

        RoomChangedEvent::dispatch($member->room);
    }

    public function decrementCount(Member $member)
    {
        $this->authorize('update', $member->room);

        $member->decrement('count');

        $member->room->refresh();

        RoomChangedEvent::dispatch($member->room);
    }

    public function linkUser(LinkMemberToUserRequest $request, Member $member)
    {
        $this->authorize('update', $member->room);

        $member->user_id = $request->input('user_id');
        $member->save();

        $member->room->refresh();

        RoomChangedEvent::dispatch($member->room);
    }
}
