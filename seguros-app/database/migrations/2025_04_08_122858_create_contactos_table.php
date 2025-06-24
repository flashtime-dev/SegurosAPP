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
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siniestro'); // Relación con comunidades
            $table->string('nombre', 255); // Nombre del contacto
            $table->string('cargo', 100)->nullable(); // Cargo opcional
            $table->string('piso', 100)->nullable(); // Piso opcional
            $table->string('telefono', 15); // Teléfono obligatorio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
