<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->timestamp('ended_at')->nullable()->after('members');
            $table->timestamp('started_at')->nullable()->after('members');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('started_at');
            $table->dropColumn('ended_at');
        });
    }
};
