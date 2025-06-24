# Gestor de Seguros - Seguros Axarquia - TFG Desarrollo de Aplicaciones Web

Aplicación web para la gestión de seguros de edificios y comunidades de
propietarios, que permite administrar pólizas, registrar y gestionar siniestros, y facilitar la
comunicación en tiempo real entre los clientes, los administradores de fincas y la empresa
aseguradora, o el corredor de seguros. La interfaz tiene uso para que los usuarios puedan
visualizar las pólizas administradas, reportar siniestros y dar seguimiento a sus solicitudes
mediante un chat en vivo.

## Documentacion del Proyecto Integrado
Informacion en la carpeta docs.

## Tecnologías utilizadas

- [Laravel](https://laravel.com/)
- [React](https://reactjs.org/)

## Configuracion Previa

- [`XAMPP v.8.2.12` ](https://www.apachefriends.org/es/download.html)
- [`Composer v.2.8.4`](https://getcomposer.org/download/)
- [`NodeJS v.22.12.0`](https://nodejs.org/en/download/prebuilt-installer)

## Despliegue del Proyecto

1. Clona el repositorio:
    ```bash
    git clone https://github.com/cvaclop1911/proyecto-daw.git
    ```
2. Copiar el archivo `.env.example` a `.env`
    Configurar las variables base de nuestra aplicacion como la Base de Datos, el nombre de la aplicacion...

3. Instalacion de las dependencias de PHP
    ```bash
    composer global require laravel/installer
    composer install
    ```

4. En caso de tener XAMPP puede que no nos deje instalar algunos paquetes.
    Deberremos ir al archivo `php.ini`, buscar y descomentar la siguiente línea:
    ```ini
    extension=zip
    ```

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
    php artisan migrate --seed
    ```

8. Iniciar el servidor
    ```bash
    npm run build
    php artisan serve
    ```

9. Instalar broadcasting e iniciar reverb
    ```bash
    php artisan install:broadcasting
    php artisan reverb:start
    ```
