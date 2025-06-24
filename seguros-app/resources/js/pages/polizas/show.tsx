import { Head, usePage } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { Card, CardContent } from "@/components/ui/card";
import { PolizaHeader } from "@/components/polizas/poliza-header";
import { Poliza, Siniestro } from "@/types";
import { PolizaMenu } from "@/components/polizas/poliza-menu";
import { ChatPoliza } from "@/types";
// Componente que muestra los detalles de una póliza específica
export default function Show() {
    // Obtener las propiedades pasadas desde la página
    const { props } = usePage<{
        poliza: Poliza;
        siniestros: Siniestro[];
        chats: ChatPoliza[];
        authUser: number; // ID del usuario autenticado
    }>();
    // Desestructuración de las propiedades para obtener la póliza, siniestros, chats y el usuario autenticado
    const { poliza } = props;
    const { siniestros } = props;
    const { chats } = props;
    const { authUser } = props;

    return (
        // Layout principal de la aplicación con breadcrumbs para navegar
        <AppLayout
            breadcrumbs={[
                { title: 'Pólizas', href: '/polizas' },
                { title: poliza.numero, href: '#' },
            ]}
        >
            {/* Título de la página en la etiqueta <head> */}
            <Head title={`Detalles de la Póliza ${poliza.numero}`} />

            <div className="p-4">
                {/* Card que contiene la información de la póliza */}
                <Card>
                    <CardContent className="space-y-6">
                        {/* Cabecera de la póliza, mostrando comunidad y estado */}
                        <PolizaHeader comunidad={poliza.comunidad.nombre} estado={poliza.estado} />
                        {/* Menú de la póliza, mostrando opciones relacionadas como siniestros y chats */}
                        <PolizaMenu poliza={poliza} siniestros={siniestros} chats={chats} authUser={authUser} />
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
