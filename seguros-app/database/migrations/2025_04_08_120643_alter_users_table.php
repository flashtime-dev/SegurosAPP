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
        //Modificacion de la tabla de usuarios con los campos correspondientes al esquema, adaptandolos a la estructura base de laravel
        //Schema es una clase que nos permite crear, modificar y borrar tablas en la base de datos
        //Blueprint es una clase que nos permite definir la estructura de la tabla, como las columnas, tipos de datos, indices, claves, etc.
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rol')->default(3)->after('id'); // Agregar columna 'id_rol'
            $table->string('address', 500)->nullable()->after('email_verified_at'); // Agregar columna 'direccion'
            $table->string('phone', 15)->nullable()->after('address'); // Agregar columna 'telefono'
            $table->boolean('state')->default(0)->after('remember_token'); // Agregar columna 'estado'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['id_rol', 'address', 'phone', 'state']); // Eliminar columnas agregadas
        });
    }
};
