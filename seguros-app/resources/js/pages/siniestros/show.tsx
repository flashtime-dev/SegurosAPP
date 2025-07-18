import { Head, usePage } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { Card, CardContent } from "@/components/ui/card";
import { ChatSiniestro, Contacto, Poliza, Siniestro } from "@/types";
import { SiniestroMenu } from "@/components/siniestros/SiniestroMenu";

//Pagina para visualizar la informacion de cada siniestro
export default function Show() {
    const { props } = usePage<{
        poliza: Poliza,
        siniestro: Siniestro,
        contactos: Contacto[],
        chats: ChatSiniestro[];
        authUser: number;
    }>();
    const { poliza } = props;
    const { siniestro } = props;
    const { contactos } = props;
    const {chats} = props;
    const { authUser } = props;
    
    return (
        <AppLayout breadcrumbs={[{ title: "Siniestros", href: "/siniestros" }, { title: siniestro.expediente, href: "#" }]}>
            <Head title={`Detalles del Siniestro ${siniestro.expediente}`} />

            <div className="p-4">
                <Card>
                    <CardContent className="space-y-6">
                        {/* <SiniestroHeader comunidad={poliza.comunidad.nombre} estado={poliza.estado} /> */}
                        <SiniestroMenu poliza={poliza} siniestro={siniestro} contactos={contactos} chats={chats} authUser={authUser}/>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
