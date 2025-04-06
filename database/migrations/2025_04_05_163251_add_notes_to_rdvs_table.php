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
        Schema::table('rdvs', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('type'); // Add the 'notes' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rdvs', function (Blueprint $table) {
            $table->dropColumn('notes'); // Remove the 'notes' column if rolled back
        });
    }
};
