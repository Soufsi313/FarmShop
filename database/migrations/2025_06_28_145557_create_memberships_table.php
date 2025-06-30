<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informations de l'adhésion
            $table->string('membership_number')->unique(); // Numéro d'adhérent unique
            $table->enum('type', ['individual', 'family', 'professional', 'student', 'association'])->default('individual');
            $table->enum('status', ['pending', 'active', 'suspended', 'expired', 'cancelled'])->default('pending');
            
            // Dates importantes
            $table->date('start_date');
            $table->date('end_date');
            $table->date('renewal_date')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Tarification
            $table->decimal('annual_fee', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->date('last_payment_date')->nullable();
            
            // Avantages et privilèges
            $table->boolean('has_delivery_discount')->default(false);
            $table->decimal('delivery_discount_percent', 5, 2)->default(0);
            $table->boolean('has_product_discount')->default(false);
            $table->decimal('product_discount_percent', 5, 2)->default(0);
            $table->boolean('can_reserve_products')->default(true);
            $table->boolean('has_priority_access')->default(false);
            $table->boolean('can_participate_events')->default(true);
            
            // Informations supplémentaires
            $table->text('notes')->nullable(); // Notes admin
            $table->json('preferences')->nullable(); // Préférences (légumes, fruits, etc.)
            $table->string('referral_code', 10)->unique()->nullable(); // Code de parrainage
            $table->foreignId('referred_by')->nullable()->constrained('users')->onDelete('set null'); // Parrainé par
            
            // Adresse spécifique à l'adhésion (peut différer du compte utilisateur)
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_country')->default('France');
            
            // Suivi administratif
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->boolean('auto_renewal')->default(true);
            $table->boolean('newsletter_subscription')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour optimiser les requêtes
            $table->index(['status', 'end_date']);
            $table->index(['user_id', 'status']);
            $table->index('membership_number');
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memberships');
    }
}
