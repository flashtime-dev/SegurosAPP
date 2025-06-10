import { useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { toast, Toaster } from "sonner";
import AppLayoutTemplate from "@/layouts/app/app-sidebar-layout";
import CustomToast from "@/components/ui/CustomToaster"; // Ajusta la ruta a donde tengas el componente

import type { ReactNode } from "react";
import type { BreadcrumbItem } from "@/types";
import useKonamiCode from "@/hooks/mensaje-oculto";

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

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

    // Efecto para lanzar los toasts cuando cambian las props
    useEffect(() => {
        if (success) {
            toast.custom(() => <CustomToast type="success" message={success.mensaje} />);
        }
        if (error) {
            toast.custom(() => <CustomToast type="error" message={error.mensaje} />);
        }
        if (info) {
            toast.custom(() => <CustomToast type="info" message={info.mensaje} />);
        }
    }, [success, error, info]);

    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
            {children}
            <Toaster position="top-right" />
        </AppLayoutTemplate>
    );
}
