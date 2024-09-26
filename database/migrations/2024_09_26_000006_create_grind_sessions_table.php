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
        Schema::create('grind_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('grind_spot_id')->constrained('grind_spots');
            $table->string('loot_image')->nullable();
            $table->string('video_link')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_video_verified')->default(false);
            $table->boolean('is_image_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grind_sessions');
    }
};
