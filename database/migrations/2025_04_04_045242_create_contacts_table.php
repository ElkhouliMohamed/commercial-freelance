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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Contact's last name
            $table->string('prenom'); // Contact's first name
            $table->string('email')->unique(); // Contact's email
            $table->string('telephone')->nullable(); // Contact's phone number
            $table->unsignedBigInteger('freelancer_id'); // Foreign key for the freelancer
            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade'); // Foreign key constraint
            $table->enum('statut', ['actif', 'inactif'])->default('actif'); // Status of the contact
            $table->softDeletes(); // For soft deletes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
