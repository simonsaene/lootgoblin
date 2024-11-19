<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['grind_session_id']);
            $table->dropColumn('grind_session_id');
            $table->foreignId('poster_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('grind_spot_id')->constrained('grind_spots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('grind_session_id')->constrained('grind_sessions')->onDelete('cascade');
            $table->dropColumn('poster_id');
            $table->dropColumn('grind_spot_id');
        });
    }
};
