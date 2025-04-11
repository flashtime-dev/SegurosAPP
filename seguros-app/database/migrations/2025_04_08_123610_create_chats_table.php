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
        // Tabla ChatsPolizas
        Schema::create('chats_polizas', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->unsignedBigInteger('id_poliza'); // Relación con pólizas
            $table->unsignedBigInteger('id_usuario'); // Relación con usuarios
            $table->string('mensaje', 1000); // Mensaje obligatorio
            $table->boolean('adjunto')->default(false); // Adjunto booleano con valor por defecto
            $table->timestamps(); // Timestamps para crear y actualizar
        });

        // Tabla AdjuntosPolizas
        Schema::create('adjuntos_polizas', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->unsignedBigInteger('id_chat'); // Relación con chats_polizas
            $table->string('nombre', 255); // Nombre del adjunto obligatorio
            $table->string('url_adjunto', 255); // URL del adjunto obligatorio
            $table->timestamps(); // Timestamps para crear y actualizar
        });

        // Tabla ChatsSiniestros
        Schema::create('chats_siniestros', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->unsignedBigInteger('id_siniestro'); // Relación con siniestros
            $table->unsignedBigInteger('id_usuario'); // Relación con usuarios
            $table->string('mensaje', 1000); // Mensaje obligatorio
            $table->boolean('adjunto')->default(false); // Adjunto booleano con valor por defecto
            $table->timestamps(); // Timestamps para crear y actualizar
        });

        // Tabla AdjuntosSiniestros
        Schema::create('adjuntos_siniestros', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->unsignedBigInteger('id_siniestro'); // Relación con siniestros
            $table->unsignedBigInteger('id_chat')->nullable(); // Relación con chats_siniestros
            $table->string('nombre', 255); // Nombre del adjunto obligatorio
            $table->string('url_adjunto', 255); // URL del adjunto obligatorio
            $table->timestamps(); // Timestamps para crear y actualizar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjuntos_siniestros');
        Schema::dropIfExists('chats_siniestros');
        Schema::dropIfExists('adjuntos_polizas');
        Schema::dropIfExists('chats_polizas');
    }
};
