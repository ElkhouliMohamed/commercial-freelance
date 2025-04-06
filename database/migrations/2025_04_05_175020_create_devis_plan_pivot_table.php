<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('devis_plan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devis_id');
            $table->unsignedBigInteger('plan_id');
            $table->timestamps();

            $table->foreign('devis_id')->references('id')->on('devis')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');

            $table->unique(['devis_id', 'plan_id']); // Ensure no duplicate entries
        });
    }

    public function down()
    {
        Schema::dropIfExists('devis_plan');
    }
};