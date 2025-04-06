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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('freelancer_id');
            $table->decimal('montant', 8, 2);
            $table->string('description')->nullable();
            $table->enum('statut', ['en attente', 'validé', 'payé', 'rejeté'])->default('en attente');
            $table->boolean('demande_paiement')->default(false);
            $table->enum('niveau', ['Bronze', 'Silver', 'Gold', 'Platinum'])->nullable();
            $table->integer('nombre_contrats')->default(0);
            $table->string('payment_proof_path')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('freelancer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
