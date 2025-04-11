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
        Schema::create('telefonos_companias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_compania'); // Relación con compañías
            $table->string('telefono', 15); // Teléfono obligatorio
            $table->string('descripcion', 255); // Descripción obligatoria
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telefonos_companias');
    }
};
