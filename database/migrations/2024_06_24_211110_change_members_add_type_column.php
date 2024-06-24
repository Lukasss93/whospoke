<?php

use App\Enums\MemberType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('type')->default(MemberType::DEFAULT->value)->after('user_id');
        });

        DB::table('members')
            ->where('offline', true)
            ->update(['type' => MemberType::OFFLINE->value]);

        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('offline');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('offline')->default(false)->after('user_id');
        });

        DB::table('members')
            ->where('type', MemberType::OFFLINE->value)
            ->update(['offline' => true]);

        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
