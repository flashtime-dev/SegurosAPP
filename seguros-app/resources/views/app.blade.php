{{-- Este archivo es fundamental ya que actúa como el contenedor principal
donde se montará toda tu aplicación React a través de Inertia.js,
proporcionando la configuración necesaria para el correcto funcionamiento de
todas las tecnologías integradas. --}}

<!DOCTYPE html>
{{-- Define el idioma dinámicamente basado en la configuración de Laravel
Añade la clase 'dark' al HTML cuando el modo oscuro está activo --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        {{-- Establece la codificación UTF-8 --}}
        <meta charset="utf-8">
        {{-- Configura la vista responsive para dispositivos móviles --}}
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        {{-- Establece el título de la página dinámicamente usando Inertia.js --}}
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        {{-- Precarga la conexión a Bunny Fonts (alternativa a Google Fonts)
        Carga la fuente Instrument Sans
        Define el favicon de la aplicación --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png"  className="rounded">

        {{-- Hace las rutas de laravel disponibles para JS --}}
        @routes
        {{-- Habilita el hot reload para React --}}
        @viteReactRefresh
        {{-- Carga los archivos JS y TS principales --}}
        @vite(['resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])

        {{-- Maneja los meta tags dinamicos de inertia --}}
        {{-- Inertia.js maneja el CSRF automáticamente: El token se pasa a través del objeto `page` que Inertia proporciona --}}
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        {{-- Componente de Inertia.js que renderiza la página actual --}}
        {{-- Este componente se encarga de renderizar el contenido de la página actual --}}
        @inertia
    </body>
</html>
