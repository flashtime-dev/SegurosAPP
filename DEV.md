# Gestor de Seguros - Seguros Axarquia

Aplicación web para la gestión de seguros de edificios y comunidades de
propietarios, que permite administrar pólizas, registrar y gestionar siniestros, y facilitar la
comunicación en tiempo real entre los clientes, los administradores de fincas y la empresa
aseguradora, o el corredor de seguros. La interfaz tiene uso para que los usuarios puedan
visualizar las pólizas administradas, reportar siniestros y dar seguimiento a sus solicitudes
mediante un chat en vivo.

## Configuracion Previa

- [`VSCode`] (https://code.visualstudio.com)
- [`XAMPP v.8.2.12` ](https://www.apachefriends.org/es/download.html)
- [`Composer v.2.8.4`](https://getcomposer.org/download/)
- [`NodeJS v.22.12.0`](https://nodejs.org/en/download/prebuilt-installer)

- Confirmar variables de entorno Path (php y composer)

## Desarrollo del Proyecto

### 1. Instalamos Laravel:
``` bash
composer global require laravel/installer
```

### 2. Creamos el proyecto de Laravel:
``` bash
laravel new nombreproyecto
```

Utilizamos `Laravel` starter kit con `REACT`, `Inertia`, `Tailwind`, `Next.js`... Como complemento instalamos el framework para las pruebas `PHPUnit` y la gestion de usuarios de Laravel. Nuestra Base de datos será `MariaDB`.

- Instalacion de paquetes de node
`npm install`
`npm run build`

- Ejecutar servidor de Laravel
`php artisan serve`

### 3. Creacion de las migraciones.

- Modificar el archivo `.env`
``` bash
    APP_NAME='Seguros Axarquia'

    DB_CONNECTION=mariadb
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=seguros-app
    DB_USERNAME=root
    DB_PASSWORD=
```

- En caso de necesitar modificar la tabla de usuarios crearemos una nueva migracion indicando la tabla que queremos modificar:
`php artisan make:migration alter_nombredelcambio_table --table=NombreDeLaBaseDeDatos`

- Y si necesitamos crear nuevas tablas obviaremos `--table`.
`php artisan make:migration create_nombre_tabla_table`

- Importante crear las relaciones de claves foraneas en una migracion aparte al final de las tablas para no crear conflictos
`php artisan make:migration add_foraign_keys_to_nombre_table`

- Esquema de modificacion de migracion:

    `Schema` es el objeto que Laravel utiliza para interactuar con la BD, y `Blueprint` es el objeto que define las propiedades de cada campo de la tabla (tipo de dato, claves, valores unicos o por defecto...).

    En la funcion `up()`:
    ```php
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('userssssstype');
            $table->tinyInteger('id_rol')->default(1);
        });
    ```

- Esquema de creacion de migracion:
    En la funcion `up()`:
    ```php
        Schema::create('polizas', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('numero'); // Número de la póliza
            $table->string('estado'); // Estado de la póliza
            $table->date('fecha_efecto'); // Fecha de efecto
            $table->string('cuenta')->nullable(); // Cuenta (opcional)
            $table->string('forma_pago'); // Forma de pago
            $table->decimal('prima_neta', 10, 2); // Prima neta
            $table->decimal('prima_total', 10, 2); // Prima total
            $table->text('observaciones')->nullable(); // Observaciones (opcional)
            $table->timestamps(); // created_at y updated_at
        });
    ```

- Esquema para borrar una tabla o columna
    En la funcion `down()`:
    ``` php
    //Borrar columna
    Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('usertype');
        });

    //Borrar tabla
    Schema::dropIfExists('polizas');
    ```

- Ejecutar migraciones
    ``` bash
    php artisan migrate
    ```

- Volver atras la ultima migracion
    ``` bash
    php artisan migrate:rollback
    ```

- Borrar todas las migraciones y volver a migrar
    
    ``` bash
    php artisan migrate:refresh
    ```
    ``` bash
    php artisan migrate:refresh --seed
    ```

### 4. Creacion de modelos
- Crear un nuevo modelo:
El nombre del modelo ha de ir en singular.
``` bash
php artisan make:model Poliza
```
Los modelos en Laravel son las clases para luego crear objetos y asi facilitar la obtencion, edicion y borrado.

- En estos modelos tenemos varios tipos de variables:

``` php
$table //para identificar el nombre de la tabla en la base de datos
$fillable //para decir que campos se pueden almacenar masivamente
$guarded //datos que no se pueden guardar masivamente (como el id)
$casts //los datos con su casting, teniendo en cuenta que si no se pone laravel comprendera que son string
$hidden //para datos que tienen que ser ocultos en las columnas json como la contraseña, tokens...
$visible //datos que son visibles en JSON
$appends //datos calculables
$with //para cargar relaciones entre modelos
```

Hay que tener en cuenta que muchas de estas variables con complementarias, es decir si pones campos como guarded el resto seran automaticamente fillable. Cuando no las pones laravel las autoasigna a lo mas permisivo posible.

- Relaciones entre modelos:
Relación Uno a Uno `1:1`
``` php
// Modelo User
public function perfil()
{
    return $this->hasOne(Perfil::class);
}

// Modelo Perfil
public function usuario()
{
    return $this->belongsTo(User::class);
}
```

Relación Uno a Muchos `1:N`
``` php
// Modelo User
public function polizas()
{
    return $this->hasMany(Poliza::class);
}

// Modelo Poliza
public function usuario()
{
    return $this->belongsTo(User::class);
}
```

Relación Muchos a Muchos `N:M`
``` php
// Modelo User
public function roles()
{
    return $this->belongsToMany(Rol::class);
}

// Modelo Rol
public function usuarios()
{
    return $this->belongsToMany(User::class);
}
```

### 5. Creacion de seeders
- Creacion de un nuevo seeder
Los seeder son para introducir datos de prueba manualmente.
``` bash
php artisan make:seeder UserSeeder
```

Añadir en la funcion `up()` el modelo el cual vamos a crear los datos:
``` php
        Agente::create([
            'nombre' => 'Seguros Axarquía',
            'telefono' => '123456789',
        ]);
```

- Almacenar un seeder concreto
``` bash
php artisan db:seed --class=UserSeeder
```

- Almacenar todos los seeders
``` bash
php artisan db:seed
```

### 6. Creacion de controladores

### 7. Creacion de rutas

### 8. Creacion de vistas

### 9. Integracion de Request para formularios

### 10. Integracion de mensajes de error

### 11. Integracion de Sessions

### 12. Integracion de middlewares

### 13. Integracion de Chat en tiempo real

### 14. Integracion de Email

### 15. Integracion de Notificaciones




## Despliegue del Proyecto

1. Clona el repositorio:
    ```bash
    git clone https://github.com/cvaclop1911/proyecto-daw.git
    ```

2. Instalacion de las dependencias de PHP
    ```bash
    composer global require laravel/installer
    composer install
    ```

3. En caso de tener XAMPP puede que no nos deje instalar algunos paquetes.
    Deberremos ir al archivo `php.ini`, buscar y descomentar la siguiente línea:
    ```ini
    extension=zip
    ```

4. Copiar el archivo `.env.example` a `.env`
    Configurar las variables base de nuestra aplicacion como la Base de Datos, el nombre de la aplicacion...

5. Crear una app-key para el archivo `.env`
    ``` bash
    php artisan key:generate
    ```

6. Crear la carpeta `node_modules` (en la raiz) y la carpeta `build` (dentro de public)
    ``` bash
    npm install
    npm run dev
    ```

7. Ejecutar las migraciones y seeders
    ``` bash
    php artisan migrate
    ```

8. Iniciar el servidor
    ```bash
    npm run build
    php artisan serve
    ```
