<?php

use App\Enums\RoomType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('type')->default(RoomType::STATUS->value)->after('user_id');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->bigInteger('count')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('count');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
