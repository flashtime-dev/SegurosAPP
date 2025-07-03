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
        Schema::create('solicitudes_reparadores', function (Blueprint $table) {
            $table->unsignedBigInteger('id_solicitud');
            $table->unsignedBigInteger('id_reparador');
            $table->primary(['id_solicitud', 'id_reparador']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_reparadores');
    }
};
