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
        // No necesitamos jobs ni cache en este proyecto
    }

    public function down(): void
    {
        // Nada que revertir
    }
};
