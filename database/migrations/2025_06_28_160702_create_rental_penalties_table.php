<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental_penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->foreignId('rental_item_id')->nullable()->constrained()->onDelete('cascade');
            
            // Type d'amende
            $table->enum('type', [
                'late_return',      // Retard de retour
                'damage',           // Dommage
                'loss',             // Perte
                'cleaning',         // Nettoyage
                'other'             // Autre
            ]);
            
            // Détails de l'amende
            $table->string('reason');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            
            // Calcul automatique pour retard
            $table->integer('days_late')->nullable();
            $table->decimal('daily_penalty_rate', 8, 2)->nullable();
            
            // Statut de paiement
            $table->enum('payment_status', [
                'pending',          // En attente
                'paid',             // Payée
                'waived',           // Annulée/remise
                'disputed'          // Contestée
            ])->default('pending');
            
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index(['rental_id', 'type']);
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rental_penalties');
    }
}
