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
        Schema::table('abonnements', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_id')->nullable()->after('freelancer_id');
            $table->integer('contracts_count')->default(0)->after('statut');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->dropForeign(['contact_id']);
            $table->dropColumn(['contact_id', 'contracts_count']);
        });
    }
};
