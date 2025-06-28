<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Numéro de retour unique
            $table->string('return_number')->unique();
            
            // Détails du retour
            $table->integer('quantity_returned');
            $table->decimal('refund_amount', 10, 2);
            $table->text('return_reason');
            $table->text('return_notes')->nullable(); // Notes du client
            $table->text('admin_notes')->nullable(); // Notes admin
            
            // Statut du retour
            $table->enum('status', [
                'requested',    // Demandé
                'approved',     // Approuvé
                'received',     // Reçu
                'refunded',     // Remboursé
                'rejected'      // Rejeté
            ])->default('requested');
            
            // Dates importantes
            $table->timestamp('requested_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            // Méthode de remboursement
            $table->string('refund_method')->nullable(); // same_payment_method, store_credit, etc.
            $table->string('refund_transaction_id')->nullable();
            
            // Conditions du retour
            $table->boolean('is_within_return_period')->default(true);
            $table->date('return_deadline');
            
            $table->timestamps();
            
            // Index
            $table->index(['order_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('return_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_returns');
    }
}
