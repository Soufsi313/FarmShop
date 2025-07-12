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
        Schema::table('users', function (Blueprint $table) {
            // Ajout du username
            $table->string('username')->unique()->after('id');
            
            // Ajout du rôle (Admin ou User)
            $table->enum('role', ['Admin', 'User'])->default('User')->after('password');
            
            // Abonnement newsletter
            $table->boolean('newsletter_subscribed')->default(false)->after('role');
            
            // Soft delete
            $table->softDeletes();
            
            // Modification de la colonne name pour la rendre nullable (optionnelle)
            $table->string('name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'newsletter_subscribed']);
            $table->dropSoftDeletes();
            $table->string('name')->nullable(false)->change();
        });
    }
};
