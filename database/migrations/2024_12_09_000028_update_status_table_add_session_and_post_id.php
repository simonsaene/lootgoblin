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
        Schema::table('status', function (Blueprint $table) {
            // Remove the is_active column
            $table->dropColumn('is_active');
            
            // Add the session_id and post_id columns (nullable)
            $table->unsignedBigInteger('session_id')->nullable();
            $table->unsignedBigInteger('post_id')->nullable();
            
            $table->foreign('session_id')->references('id')->on('grind_sessions')->onDelete('set null');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status', function (Blueprint $table) {
            // Rollback changes: restore the 'is_active' column
            $table->boolean('is_active')->default(true);
    
            // Drop the session_id and post_id columns
            $table->dropColumn('session_id');
            $table->dropColumn('post_id');
        });
    }
};
