<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_locations', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Numéro de commande unique
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Utilisateur
            $table->foreignId('cart_location_id')->nullable()->constrained()->onDelete('set null'); // Panier d'origine
            
            // Statuts de la commande de location
            $table->enum('status', [
                'pending',      // En attente de confirmation
                'confirmed',    // Confirmée par l'admin
                'active',       // Location en cours
                'completed',    // Terminée (retour effectué)
                'cancelled',    // Annulée
                'overdue'       // En retard
            ])->default('pending');
            
            // Montants
            $table->decimal('total_amount', 10, 2); // Total de la location
            $table->decimal('deposit_amount', 10, 2)->default(0); // Total des cautions
            $table->decimal('paid_amount', 10, 2)->default(0); // Montant payé
            
            // Dates importantes
            $table->datetime('rental_start_date'); // Date de début de location
            $table->datetime('rental_end_date');   // Date de fin prévue
            $table->datetime('actual_return_date')->nullable(); // Date de retour réelle
            $table->datetime('confirmed_at')->nullable(); // Date de confirmation
            $table->datetime('picked_up_at')->nullable(); // Date de récupération
            $table->datetime('returned_at')->nullable(); // Date de retour
            
            // Informations de contact pour la récupération
            $table->text('pickup_notes')->nullable(); // Notes pour la récupération
            $table->text('return_notes')->nullable(); // Notes pour le retour
            $table->text('admin_notes')->nullable(); // Notes admin
            
            // Pénalités en cas de retard
            $table->decimal('late_fee', 8, 2)->default(0);
            $table->decimal('damage_fee', 8, 2)->default(0);
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['user_id', 'status']);
            $table->index(['rental_start_date', 'rental_end_date']);
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
        Schema::dropIfExists('order_locations');
    }
}
