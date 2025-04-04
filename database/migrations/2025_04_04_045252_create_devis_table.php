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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rdv_id');
            $table->unsignedBigInteger('contact_id');
            $table->decimal('montant', 8, 2);
            $table->enum('statut', ['en attente', 'validé', 'refusé'])->default('en attente');
            $table->timestamps();

            $table->foreign('rdv_id')->references('id')->on('rdvs')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('devis');
    }
};
