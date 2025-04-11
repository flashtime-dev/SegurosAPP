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
        Schema::create('siniestros_contactos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_contacto'); // Relación con contactos
            $table->unsignedBigInteger('id_siniestro'); // Relación con siniestros
            $table->primary(['id_contacto', 'id_siniestro']); // Clave primaria compuesta
            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siniestros_contactos');
    }
};
