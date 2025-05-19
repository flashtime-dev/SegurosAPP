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
        Schema::create('comunidades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_propietario')->default(1); // Agregar columna 'idusuario'
            $table->string('nombre', 255); // Nombre de la comunidad
            $table->string('cif', 15)->unique(); // CIF único
            $table->string('direccion', 255)->nullable(); // Dirección opcional
            $table->string('ubi_catastral', 255)->nullable(); // Ubicación catastral opcional
            $table->string('ref_catastral', 20)->nullable(); // Referencia catastral opcional
            $table->string('telefono', 15)->nullable(); // Teléfono opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunidades');
    }
};
