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
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_comunidad'); // Relación con comunidades
            $table->date('fecha'); // Fecha obligatoria
            $table->string('observaciones', 1000); // Observaciones obligatorias
            $table->string('comentarios', 500)->nullable(); // Comentarios opcionales
            $table->string('pdf_presupuesto', 255); // Ruta del PDF obligatoria
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
