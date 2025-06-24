// Este archivo es el punto de entrada de tu aplicación React, configura el
// sistema de navegación SPA y establece la estructura básica de la aplicación
import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';
import  './echo.js';
import "react-phone-input-2/lib/style.css";


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();



//              #
//            ###
//          #####
//        #######
//      #########
//    ######################
//  ######################
//             #########
//             #######
//             #####
//             ###
//             #

// ⚡ Este rayo marca el inicio de algo poderoso... o no.
// Easter Egg by @Cristy