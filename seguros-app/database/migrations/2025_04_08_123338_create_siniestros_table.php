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
        Schema::create('siniestros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_poliza'); // Relación con pólizas
            $table->text('declaracion'); // Declaración obligatoria
            $table->string('tramitador', 255)->nullable(); // Tramitador opcional
            $table->string('expediente', 50); // Expediente obligatorio
            $table->string('exp_cia', 50)->nullable(); // Expediente de la compañía opcional
            $table->string('exp_asist', 50)->nullable(); // Expediente de asistencia opcional
            $table->date('fecha_ocurrencia')->nullable(); // Fecha de ocurrencia opcional
            $table->boolean('adjunto')->default(false); // Adjunto booleano con valor por defecto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siniestros');
    }
};
