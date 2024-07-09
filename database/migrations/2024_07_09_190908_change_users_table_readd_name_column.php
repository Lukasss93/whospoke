<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('username')->nullable();
        });

        DB::table('users')->update([
            'name' => DB::raw('TRIM(CONCAT(first_name, " ", last_name))'),
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('username')->change();
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
        });

        DB::table('users')->update([
            'first_name' => DB::raw('SUBSTRING_INDEX(name, " ", 1)'),
            'last_name' => DB::raw('SUBSTRING_INDEX(name, " ", -1)'),
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->change();
            $table->dropColumn('name');
        });
    }
};
