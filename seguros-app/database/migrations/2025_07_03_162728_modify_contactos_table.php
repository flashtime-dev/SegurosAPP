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
        Schema::table('contactos', function (Blueprint $table) {
            //quitar clave foranea a siniestro
            $table->dropForeign(['id_siniestro']);

            //borrar id siniestro
            $table->dropColumn('id_siniestro');

            //añadir id comunidad
            $table->unsignedBigInteger('id_comunidad')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contactos', function (Blueprint $table) {
            //borrar id comunidad
            $table->dropColumn('id_comunidad');
            //añadir id siniestro
            $table->unsignedBigInteger('id_siniestro')->after('id');
            //añadir clave foranea a siniestro
            $table->foreign('id_siniestro')->references('id')->on('siniestros')->onDelete('cascade');
        });
    }
};
