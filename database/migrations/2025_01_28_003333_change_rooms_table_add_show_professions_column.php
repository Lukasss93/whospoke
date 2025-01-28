<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->boolean('show_professions')->default(false)->after('code');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('show_professions');
        });
    }
};
