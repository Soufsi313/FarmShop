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
        // Skip index removal for now, we'll handle it differently
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip index restoration for now
    }
};
