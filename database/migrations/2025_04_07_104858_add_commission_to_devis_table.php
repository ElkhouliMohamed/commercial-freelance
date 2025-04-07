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
        Schema::table('devis', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->integer('commission')->nullable()->after('montant');
        });
    }

    public function down()
    {
        Schema::table('devis', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('commission');
        });
    }
};
