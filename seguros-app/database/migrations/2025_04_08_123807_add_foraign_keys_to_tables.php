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
        // Tabla Usuarios
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_rol')->references('id')->on('roles')->onDelete('cascade');
        });

        // Tabla Subusuarios
        Schema::table('subusuarios', function (Blueprint $table) {
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_usuario_creador')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabla Permisos
        Schema::table('permisos', function (Blueprint $table) {
            $table->foreign('id_tipo')->references('id')->on('tipos_permisos')->onDelete('cascade');
        });

        // Tabla RolesPermisos
        Schema::table('roles_permisos', function (Blueprint $table) {
            $table->foreign('id_rol')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('id_permiso')->references('id')->on('permisos')->onDelete('cascade');
        });

        // Tabla UsuariosComunidades
        Schema::table('usuarios_comunidades', function (Blueprint $table) {
            $table->foreign('id_comunidad')->references('id')->on('comunidades')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabla Caracteristicas
        Schema::table('caracteristicas', function (Blueprint $table) {
            $table->foreign('id_comunidad')->references('id')->on('comunidades')->onDelete('cascade');
        });

        // Tabla Contactos
        Schema::table('contactos', function (Blueprint $table) {
            $table->foreign('id_comunidad')->references('id')->on('comunidades')->onDelete('cascade');
        });

        // Tabla Presupuestos
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->foreign('id_comunidad')->references('id')->on('comunidades')->onDelete('cascade');
        });

        // Tabla TelefonosCompanias
        Schema::table('telefonos_companias', function (Blueprint $table) {
            $table->foreign('id_compania')->references('id')->on('companias')->onDelete('cascade');
        });

        // Tabla GarantiasDefectos
        Schema::table('garantias_defectos', function (Blueprint $table) {
            $table->foreign('id_compania')->references('id')->on('companias')->onDelete('cascade');
        });

        // Tabla DesglosesComparativos
        Schema::table('desgloses_comparativos', function (Blueprint $table) {
            $table->foreign('id_compania')->references('id')->on('companias')->onDelete('cascade');
            $table->foreign('id_presupuesto')->references('id')->on('presupuestos')->onDelete('cascade');
        });

        // Tabla Siniestros
        Schema::table('siniestros', function (Blueprint $table) {
            $table->foreign('id_poliza')->references('id')->on('polizas')->onDelete('cascade');
        });

        // Tabla SiniestrosContactos
        Schema::table('siniestros_contactos', function (Blueprint $table) {
            $table->foreign('id_contacto')->references('id')->on('contactos')->onDelete('cascade');
            $table->foreign('id_siniestro')->references('id')->on('siniestros')->onDelete('cascade');
        });

        // Tabla Polizas
        Schema::table('polizas', function (Blueprint $table) {
            $table->foreign('id_compania')->references('id')->on('companias')->onDelete('cascade');
            $table->foreign('id_comunidad')->references('id')->on('comunidades')->onDelete('cascade');
            $table->foreign('id_agente')->references('id')->on('agentes')->onDelete('cascade');
        });

        // Tabla Recibos
        Schema::table('recibos', function (Blueprint $table) {
            $table->foreign('id_poliza')->references('id')->on('polizas')->onDelete('cascade');
        });

        // Tabla ChatsPolizas
        Schema::table('chats_polizas', function (Blueprint $table) {
            $table->foreign('id_poliza')->references('id')->on('polizas')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabla AdjuntosPolizas
        Schema::table('adjuntos_polizas', function (Blueprint $table) {
            $table->foreign('id_chat')->references('id')->on('chats_polizas')->onDelete('cascade');
        });

        // Tabla ChatsSiniestros
        Schema::table('chats_siniestros', function (Blueprint $table) {
            $table->foreign('id_siniestro')->references('id')->on('siniestros')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabla AdjuntosSiniestros
        Schema::table('adjuntos_siniestros', function (Blueprint $table) {
            $table->foreign('id_siniestro')->references('id')->on('siniestros')->onDelete('cascade');
            $table->foreign('id_chat')->references('id')->on('chats_siniestros')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar claves foráneas en orden inverso
        Schema::table('adjuntos_siniestros', function (Blueprint $table) {
            $table->dropForeign(['id_siniestro']);
            $table->dropForeign(['id_chat']);
        });

        Schema::table('chats_siniestros', function (Blueprint $table) {
            $table->dropForeign(['id_siniestro']);
            $table->dropForeign(['id_usuario']);
        });

        Schema::table('adjuntos_polizas', function (Blueprint $table) {
            $table->dropForeign(['id_chat']);
        });

        Schema::table('chats_polizas', function (Blueprint $table) {
            $table->dropForeign(['id_poliza']);
            $table->dropForeign(['id_usuario']);
        });

        Schema::table('recibos', function (Blueprint $table) {
            $table->dropForeign(['id_poliza']);
        });

        Schema::table('polizas', function (Blueprint $table) {
            $table->dropForeign(['id_compania']);
            $table->dropForeign(['id_comunidad']);
            $table->dropForeign(['id_agente']);
        });

        Schema::table('siniestros_contactos', function (Blueprint $table) {
            $table->dropForeign(['id_contacto']);
            $table->dropForeign(['id_siniestro']);
        });

        Schema::table('siniestros', function (Blueprint $table) {
            $table->dropForeign(['id_poliza']);
        });

        Schema::table('desgloses_comparativos', function (Blueprint $table) {
            $table->dropForeign(['id_compania']);
            $table->dropForeign(['id_presupuesto']);
        });

        Schema::table('garantias_defectos', function (Blueprint $table) {
            $table->dropForeign(['id_compania']);
        });

        Schema::table('telefonos_companias', function (Blueprint $table) {
            $table->dropForeign(['id_compania']);
        });

        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropForeign(['id_comunidad']);
        });

        Schema::table('contactos', function (Blueprint $table) {
            $table->dropForeign(['id_comunidad']);
        });

        Schema::table('caracteristicas', function (Blueprint $table) {
            $table->dropForeign(['id_comunidad']);
        });

        Schema::table('usuarios_comunidades', function (Blueprint $table) {
            $table->dropForeign(['id_comunidad']);
            $table->dropForeign(['id_usuario']);
        });

        Schema::table('roles_permisos', function (Blueprint $table) {
            $table->dropForeign(['id_rol']);
            $table->dropForeign(['id_permiso']);
        });

        Schema::table('permisos', function (Blueprint $table) {
            $table->dropForeign(['id_tipo']);
        });

        Schema::table('subusuarios', function (Blueprint $table) {
            $table->dropForeign(['id']);
            $table->dropForeign(['id_usuario_creador']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_rol']);
        });
    }
};
