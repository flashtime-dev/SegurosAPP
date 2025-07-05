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
        Schema::table('polizas', function (Blueprint $table) {
            $table->enum('estado', [
                'En Vigor',
                'Anulada',
                'Solicitada',
                'Externa',
                'Vencida',
                'Anulada a Vto.',
                'No Renueva'
            ])->default('En Vigor')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polizas', function (Blueprint $table) {
            $table->enum('estado', [
                'En Vigor',
                'Anulada',
                'Solicitada',
                'Externa',
                'Vencida',
            ])->default('En Vigor')->change();
        });
    }
};
