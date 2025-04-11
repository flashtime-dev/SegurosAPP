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
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_poliza'); // Relación con pólizas
            $table->string('num_recibo', 20)->nullable(); // Número de recibo opcional
            $table->date('fecha_recibo'); // Fecha del recibo obligatoria
            $table->enum('estado', ['Pendiente', 'Pagado', 'Anulado', 'Devuelto']); // Estado obligatorio
            $table->decimal('prima_neta', 10, 2); // Prima neta obligatoria
            $table->decimal('prima_total', 10, 2); // Prima total obligatoria
            $table->string('observaciones', 1000)->nullable(); // Observaciones opcionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibos');
    }
};
