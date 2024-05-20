<?php

namespace App\Http\Controllers;

use App\Events\RoomChangedEvent;
use App\Http\Requests\UpdateMemberOfflineRequest;
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

    public function setMemberOffline(UpdateMemberOfflineRequest $request, Member $member)
    {
        $this->authorize('update', $member->room);

        $member->offline = $request->boolean('offline');
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
        $member->save();

        $member->room->refresh();

        RoomChangedEvent::dispatch($member->room);
    }
}
