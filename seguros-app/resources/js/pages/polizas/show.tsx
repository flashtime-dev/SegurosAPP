import { Head, usePage } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { Card, CardContent } from "@/components/ui/card";
import { PolizaHeader } from "@/components/polizas/poliza-header";
import { Poliza } from "@/types";
import { PolizaMenu } from "@/components/polizas/poliza-menu";
import { ChatPoliza } from "@/types";

export default function Show() {
    const { props } = usePage<{ 
        poliza: Poliza, 
        chats: ChatPoliza[];
        authUser: number;
    }>();
    const { poliza } = props;
    const {chats} = props;
    const { authUser } = props;
    
    return (
        <AppLayout breadcrumbs={[{ title: "Pólizas", href: "/polizas" }, { title: poliza.numero, href: "#" }]}>
            <Head title={`Detalles de la Póliza ${poliza.numero}`} />

            <div className="p-4">
                <Card>
                    <CardContent className="space-y-6">
                        <PolizaHeader comunidad={poliza.comunidad.nombre} estado={poliza.estado} />
                        <PolizaMenu poliza={poliza} chats={chats} authUser={authUser}/>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
