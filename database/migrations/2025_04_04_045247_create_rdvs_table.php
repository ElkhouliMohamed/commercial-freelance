<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rdvs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('freelancer_id');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->dateTime('date');
            $table->string('type');
            $table->enum('statut', ['planifié', 'terminé', 'annulé'])->default('planifié');
            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        // Drop the dependent table first
        Schema::dropIfExists('devis'); // Drop the `devis` table before `rdvs`
        Schema::dropIfExists('rdvs'); // Then drop the `rdvs` table
    }
};
