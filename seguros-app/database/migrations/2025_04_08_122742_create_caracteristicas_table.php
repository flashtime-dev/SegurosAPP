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
        Schema::create('caracteristicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_comunidad'); // Relación con comunidades
            $table->enum('tipo', ['Edificios de Viviendas', 'Zonas Comunes', 'Garajes', 'Vdas Unifamiliares', 'Oficinas']);
            $table->integer('num_plantas')->default(0);
            $table->integer('num_viviendas')->default(0);
            $table->boolean('ascensores')->default(false);
            $table->boolean('piscina')->default(false);
            $table->integer('num_plantas_sotano')->default(0);
            $table->integer('num_locales')->default(0);
            $table->boolean('garajes')->default(false);
            $table->integer('num_edificios')->default(0);
            $table->integer('num_oficinas')->default(0);
            $table->boolean('pista_deportiva')->default(false);
            $table->integer('m2_urbanizacion')->default(0);
            $table->year('anio_construccion')->default(0);
            $table->integer('metros_construidos')->default(0);
            $table->date('fecha_renovacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caracteristicas');
    }
};
