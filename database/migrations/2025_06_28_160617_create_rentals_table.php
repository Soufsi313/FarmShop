<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('rental_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Dates de location
            $table->date('start_date');
            $table->date('end_date');
            $table->date('actual_return_date')->nullable();
            
            // Statuts
            $table->enum('status', [
                'pending',      // En attente de confirmation
                'confirmed',    // Confirmée
                'active',       // En cours
                'completed',    // Terminée (retour effectué)
                'overdue',      // En retard
                'cancelled'     // Annulée
            ])->default('pending');
            
            // Montants
            $table->decimal('total_rental_amount', 10, 2)->default(0);
            $table->decimal('total_deposit_amount', 10, 2)->default(0);
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->decimal('refund_amount', 10, 2)->default(0);
            
            // Informations de facturation
            $table->json('billing_address');
            $table->string('payment_status')->default('pending'); // pending, paid, refunded
            $table->string('deposit_status')->default('pending'); // pending, held, refunded
            
            // Notes et conditions
            $table->text('notes')->nullable();
            $table->text('return_notes')->nullable();
            $table->json('rental_conditions')->nullable();
            
            // Notifications
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['user_id', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rentals');
    }
}
