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
        Schema::create('desgloses_comparativos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_compania'); // Relación con compañías
            $table->unsignedBigInteger('id_presupuesto'); // Relación con presupuestos
            $table->string('incendio', 8)->nullable();
            $table->string('daños_electricos', 8)->nullable();
            $table->string('robo', 8)->nullable();
            $table->string('cristales', 8)->nullable();
            $table->boolean('agua_comun')->default(false);
            $table->boolean('agua_privada')->default(false);
            $table->integer('danios_esteticos_comunes')->nullable();
            $table->integer('danios_esteticos_privados')->nullable();
            $table->boolean('rc_daños_agua')->default(false);
            $table->integer('filtraciones')->nullable();
            $table->string('desatascos', 50)->nullable();
            $table->integer('fontaneria_sin_danios')->nullable();
            $table->string('averia_maquinaria', 50)->nullable();
            $table->string('control_plagas', 50)->nullable();
            $table->integer('defensa_juridica')->nullable();
            $table->boolean('tiene_api')->default(false);
            $table->integer('franquicia')->nullable();
            $table->boolean('requiere_peritacion')->default(false);
            $table->string('observaciones', 1000)->nullable();
            $table->decimal('prima_total', 10, 2)->nullable();
            $table->integer('capital_rc')->nullable();
            $table->integer('capital_ctdo')->nullable();
            $table->integer('capital_cte')->nullable();
            $table->string('pdf_desglose', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desgloses_comparativos');
    }
};
