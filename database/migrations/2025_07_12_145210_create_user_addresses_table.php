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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('shipping'); // shipping, billing, both
            $table->string('label')->nullable(); // Maison, Travail, etc.
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->string('state_province')->nullable();
            $table->string('country')->default('France');
            $table->string('phone')->nullable();
            $table->text('instructions')->nullable(); // Instructions de livraison
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // infos supplémentaires
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'is_default']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
