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
        Schema::create('polizas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_compania'); // Relación con compañías
            $table->unsignedBigInteger('id_comunidad'); // Relación con comunidades
            $table->unsignedBigInteger('id_agente')->nullable(); // Relación con agentes
            $table->string('alias', 20)->nullable(); // Alias opcional
            $table->string('numero', 20)->nullable(); // Número obligatorio
            $table->date('fecha_efecto'); // Fecha de efecto obligatoria
            $table->string('cuenta', 24)->nullable(); // Cuenta opcional
            $table->enum('forma_pago', ['Bianual', 'Anual', 'Semestral', 'Trimestral', 'Mensual']); // Forma de pago obligatoria
            $table->decimal('prima_neta', 10, 2); // Prima neta obligatoria
            $table->decimal('prima_total', 10, 2); // Prima total obligatoria
            $table->string('pdf_poliza', 255)->nullable(); // PDF de la póliza opcional
            $table->text('observaciones', 1000)->nullable(); // Observaciones opcionales
            $table->enum('estado', ['En Vigor', 'Anulada', 'Solicitada', 'Externa', 'Vencida']); // Estado obligatorio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polizas');
    }
};
