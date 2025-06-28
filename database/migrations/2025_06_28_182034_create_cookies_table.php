<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCookiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cookies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nom du cookie (ex: analytics, marketing, etc.)
            $table->string('category'); // Catégorie: essential, analytics, marketing, preferences
            $table->text('description'); // Description du cookie
            $table->text('purpose'); // Finalité du cookie
            $table->string('provider')->nullable(); // Fournisseur (Google, Facebook, etc.)
            $table->integer('duration_days')->nullable(); // Durée en jours
            $table->enum('type', ['session', 'persistent', 'third_party']); // Type de cookie
            $table->boolean('is_essential')->default(false); // Cookie essentiel (ne peut être refusé)
            $table->boolean('is_active')->default(true); // Cookie actif
            $table->json('domains')->nullable(); // Domaines où le cookie est utilisé
            $table->json('technical_details')->nullable(); // Détails techniques (path, secure, etc.)
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
            $table->index('is_essential');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cookies');
    }
}
