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
        Schema::table('siniestros', function (Blueprint $table) {
            $table->enum('tipologia', [
                'Atasco',
                'Daños por Agua',
                'Filtraciones de Lluvia',
                'Inundación',
                'Incendio',
                'Robo',
                'Cristales',
                'Daños Eléctricos',
                'Avería de Maquinaria',
                'Control de Plagas',
                'Consultas',
                'Otros'
            ])->default('Daños por Agua');
            $table->enum('urgencia', [
                'Sin Luz',
                'Sin Agua',
                'Salida Masiva de Agua',
            ])->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siniestros', function (Blueprint $table) {
            $table->dropColumn('tipologia');
            $table->dropColumn('urgencia');
        });
    }
};
