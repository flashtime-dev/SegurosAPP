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
        Schema::create('subusuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('id_usuario_creador');
            $table->primary(['id', 'id_usuario_creador']);
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subusuarios');
    }
};
