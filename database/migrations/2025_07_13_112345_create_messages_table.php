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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('type')->nullable(); // 'notification', 'message', 'alert', etc.
            $table->string('subject');
            $table->text('content');
            $table->json('metadata')->nullable(); // Pour stocker des données supplémentaires
            $table->string('status')->default('unread'); // 'read', 'unread', 'archived'
            $table->string('priority')->default('normal'); // 'low', 'normal', 'high', 'urgent'
            $table->timestamp('read_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->boolean('is_important')->default(false);
            $table->string('action_url')->nullable();
            $table->string('action_label')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'archived_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
