import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
//import { Siniestros } from "@/components/siniestros";
//import { Recibos } from "@/components/recibos";
import { Siniestro, ChatSiniestro, Poliza, Contacto } from "@/types";
import { Badge } from "@/components/ui/badge";
import { SiniestroLogoAcciones } from "./siniestro-logo-acciones";
import { SiniestroDetalles } from "./siniestro-detalles";
import { SiniestroChat } from "./siniestro-chat";
import TablaContactos from "./TablaContactos";

export function SiniestroMenu({ poliza, siniestro, contactos, chats, authUser }: { poliza: Poliza, siniestro: Siniestro, contactos: Contacto[],chats: ChatSiniestro[], authUser: number }) {
    console.log("SiniestroMenu", { poliza, siniestro, contactos, chats, authUser });
    const isClosed = siniestro.estado.toLowerCase() === 'cerrado';
    return (
        <div>
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-2">
                <div>
                    <h4 className="text-lg font-semibold">{siniestro.expediente}</h4>
                </div>
                <Badge variant={siniestro.estado as "Abierto"| "Cerrado" }>{siniestro.estado}</Badge>
            </div>
            <Tabs defaultValue="ficha" className="w-full">
                <TabsList className="mb-6">
                    <TabsTrigger value="ficha" className="cursor-pointer">Siniestro</TabsTrigger>
                    <TabsTrigger value="contactos" className="cursor-pointer">Contactos</TabsTrigger>
                </TabsList>

                <TabsContent value="ficha">
                    <div className="flex flex-col md:flex-row justify-between gap-6">
                        <SiniestroDetalles siniestro={siniestro} />
                        <div className="md:w-1/3 w-full">
                            <SiniestroLogoAcciones id={siniestro.id} logoUrl={poliza.compania.url_logo} />
                        </div>
                    </div>
                    <SiniestroChat chats={chats} authUser={authUser} siniestroId={siniestro.id} isClosed={isClosed} />
                </TabsContent>

                <TabsContent value="contactos">
                    <TablaContactos contactos={contactos} />
                </TabsContent>
            </Tabs>
        </div>
    );
}

