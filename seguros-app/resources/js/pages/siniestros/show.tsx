import { Head, usePage } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { Card, CardContent } from "@/components/ui/card";
import { ChatSiniestro, Siniestro } from "@/types";
import { SiniestroMenu } from "@/components/siniestros/SiniestroMenu";

export default function Show() {
    const { props } = usePage<{ 
        siniestro: Siniestro, 
        chats: ChatSiniestro[];
        authUser: number;
    }>();
    const { siniestro } = props;
    const {chats} = props;
    const { authUser } = props;
    
    return (
        <AppLayout breadcrumbs={[{ title: "Siniestros", href: "/siniestros" }, { title: siniestro.expediente, href: "#" }]}>
            <Head title={`Detalles del Siniestro ${siniestro.expediente}`} />

            <div className="p-4">
                <Card>
                    <CardContent className="space-y-6">
                        {/* <SiniestroHeader comunidad={poliza.comunidad.nombre} estado={poliza.estado} /> */}
                        <SiniestroMenu siniestro={siniestro} chats={chats} authUser={authUser}/>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
