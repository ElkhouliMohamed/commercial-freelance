<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->nullable()->after('montant');
            $table->decimal('commission', 10, 2)->nullable()->after('commission_rate');
        });
    }

    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropColumn(['commission_rate', 'commission']);
        });
    }
};
