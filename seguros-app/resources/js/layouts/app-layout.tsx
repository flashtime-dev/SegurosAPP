import { useEffect } from "react";
import { usePage, router } from "@inertiajs/react";
import { toast, Toaster } from "sonner";
import AppLayoutTemplate from "@/layouts/app/app-sidebar-layout";
import CustomToast from "@/components/ui/CustomToaster"; // Ajusta la ruta a donde tengas el componente

import type { ReactNode } from "react";
import type { BreadcrumbItem } from "@/types";
import useKonamiCode, { MensajeOcultoModal } from "@/hooks/mensaje-oculto";


interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

/**
 * Layout principal de la aplicación
 * Maneja los toasts de notificación y el easter egg del código Konami
 */
export default function AppLayout({ children, breadcrumbs, ...props }: AppLayoutProps) {
    // Extraemos las props de Inertia (success, error, info)
    const { props: pageProps } = usePage<{
        success?: { id: string; mensaje: string };
        error?: { id: string; mensaje: string };
        info?: { id: string; mensaje: string };
    }>();

    const { success, error, info } = pageProps;

    // Hook para detectar el Konami Code
    useKonamiCode();

    // Efecto para mostrar las notificaciones toasts cuando cambian las props
    useEffect(() => {
        // Función para limpiar una notificación específica
        const clearNotification = (type: 'success' | 'error' | 'info') => {
            router.visit(window.location.href, {
                only: [type],
                data: { [type]: undefined },
                preserveState: true,
                preserveScroll: true
            });
        };
        if (success) {
            toast.custom(() => <CustomToast type="success" message={success.mensaje} />);
            clearNotification('success');
        }
        if (error) {
            toast.custom(() => <CustomToast type="error" message={error.mensaje} />);
            clearNotification('success');
        }
        if (info) {
            toast.custom(() => <CustomToast type="info" message={info.mensaje} />);
            clearNotification('success');
        }
    }, [success, error, info]);

    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
            {children}
            <Toaster position="top-right" />
            <MensajeOcultoModal />
        </AppLayoutTemplate>
    );
}
