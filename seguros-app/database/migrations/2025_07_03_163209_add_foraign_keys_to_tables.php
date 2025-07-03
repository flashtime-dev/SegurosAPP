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
        // Tabla Usuarios_Polizas
        Schema::table('usuarios_polizas', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_poliza')->references('id')->on('polizas')->onDelete('cascade');
        });

        // Tabla Usuarios_Siniestros
        Schema::table('usuarios_siniestros', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_siniestro')->references('id')->on('siniestros')->onDelete('cascade');
        });

        // Tabla Solicitudes
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->foreign('id_comunidad')->references('id')->on('comunidades')->onDelete('cascade');
        });

        // Tabla Reparadores
        Schema::table('reparadores', function (Blueprint $table) {
            $table->foreign('id_tipo_reparador')->references('id')->on('tipos_reparador')->onDelete('cascade');
        });

        // Tabla Solicitudes_Reparadores
        Schema::table('solicitudes_reparadores', function (Blueprint $table) {
            $table->foreign('id_solicitud')->references('id')->on('solicitudes')->onDelete('cascade');
            $table->foreign('id_reparador')->references('id')->on('reparadores')->onDelete('cascade');
        });

        // Tabla Contactos
        Schema::table('contactos', function (Blueprint $table) {
            $table->foreign('id_comunidad')->references('id')->on('comunidades')->onDelete('cascade');
        });

        // Tabla Siniestros_Contactos
        Schema::table('siniestros_contactos', function (Blueprint $table) {
            $table->foreign('id_siniestro')->references('id')->on('siniestros')->onDelete('cascade');
            $table->foreign('id_contacto')->references('id')->on('contactos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tabla Usuarios_Polizas
        Schema::table('usuarios_polizas', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
            $table->dropForeign(['id_poliza']);
        });

        // Tabla Usuarios_Siniestros
        Schema::table('usuarios_siniestros', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
            $table->dropForeign(['id_siniestro']);
        });

        // Tabla Solicitudes
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropForeign(['id_comunidad']);
        });

        // Tabla Reparadores
        Schema::table('reparadores', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_reparador']);
        });

        // Tabla Solicitudes_Reparadores
        Schema::table('solicitudes_reparadores', function (Blueprint $table) {
            $table->dropForeign(['id_solicitud']);
            $table->dropForeign(['id_reparador']);
        });

        // Tabla Contactos
        Schema::table('contactos', function (Blueprint $table) {
            $table->dropForeign(['id_comunidad']);
        });

        // Tabla Siniestros_Contactos
        Schema::table('siniestros_contactos', function (Blueprint $table) {
            $table->dropForeign(['id_siniestro']);
            $table->dropForeign(['id_contacto']);
        });
    }
};
