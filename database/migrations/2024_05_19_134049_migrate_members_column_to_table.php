<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // migrate members column to members table
        DB::table('rooms')
            ->lazyById()
            ->each(function (object $room) {
                $members = json_decode($room->members, true, flags: JSON_THROW_ON_ERROR);

                foreach ($members as $member) {
                    DB::table('members')->insert([
                        'room_id' => $room->id,
                        'name' => $member['name'],
                        'status' => $member['status'],
                        'started_at' => null,
                        'ended_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });


        // drop column
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('members');
        });
    }

    public function down(): void
    {
        // add column
        Schema::table('rooms', function (Blueprint $table) {
            $table->json('members');
        });

        // migrate members table to members column
        DB::table('members')
            ->lazyById()
            ->each(function (object $member) {
                $members[] = [
                    'name' => $member->name,
                    'status' => $member->status,
                ];

                DB::table('rooms')
                    ->where('id', $member->room_id)
                    ->update([
                        'members' => json_encode($members, flags: JSON_THROW_ON_ERROR),
                    ]);
            });
    }
};
