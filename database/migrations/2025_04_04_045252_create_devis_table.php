<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rdv_id');
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('freelancer_id')->nullable();
            $table->unsignedBigInteger('service_id'); // Keep the column, but no foreign key yet
            $table->float('montant');
            $table->string('statut')->default('Brouillon');
            $table->date('date_validite');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rdv_id')->references('id')->on('rdvs')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('set null');
            // Remove or comment out: $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('devis');
    }
};
