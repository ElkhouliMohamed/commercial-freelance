<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComptePourCommissionToDevisTable extends Migration
{
    public function up()
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->boolean('compte_pour_commission')->default(false)->after('notes');
        });
    }

    public function down()
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropColumn('compte_pour_commission');
        });
    }
}
