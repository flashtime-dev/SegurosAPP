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
        Schema::create('siniestros_contactos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_siniestro');
            $table->unsignedBigInteger('id_contacto');
            $table->primary(['id_siniestro', 'id_contacto']); // Clave primaria compuesta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siniestros_contactos');
    }
};
