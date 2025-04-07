<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDevisIdToCommissionsTable extends Migration
{
    public function up()
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->unsignedBigInteger('devis_id')->nullable()->after('id');
            $table->foreign('devis_id')->references('id')->on('devis')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropForeign(['devis_id']);
            $table->dropColumn('devis_id');
        });
    }
}
