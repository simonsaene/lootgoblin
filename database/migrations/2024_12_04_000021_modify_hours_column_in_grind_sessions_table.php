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
        Schema::table('grind_sessions', function (Blueprint $table) {
            $table->decimal('hours', 4, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grind_sessions', function (Blueprint $table) {
            $table->decimal('hours', 4, 2)->nullable(false)->change();
        });
    }
};
