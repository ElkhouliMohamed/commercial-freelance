<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseStatutLengthInRdvsTable extends Migration
{
    public function up()
    {
        Schema::table('rdvs', function (Blueprint $table) {
            $table->string('statut', 191)->change(); // Increase to 191 (Laravel default) or more
        });
    }

    public function down()
    {
        Schema::table('rdvs', function (Blueprint $table) {
            $table->string('statut', 50)->change(); // Revert back if needed
        });
    }
}