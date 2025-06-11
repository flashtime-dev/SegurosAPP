<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// Este controlador base maneja las solicitudes comunes de la aplicación
// BaseController proporciona funcionalidades comunes como autorización, despacho de trabajos y validación de solicitudes.
// en este caso es necesario para los middlewares de permisos y validación de solicitudes.
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}