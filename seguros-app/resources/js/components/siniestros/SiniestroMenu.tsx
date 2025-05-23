import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
//import { Siniestros } from "@/components/siniestros";
//import { Recibos } from "@/components/recibos";
import { Siniestro, ChatSiniestro, Poliza } from "@/types";
import { Badge } from "@/components/ui/badge";
import { SiniestroLogoAcciones } from "./siniestro-logo-acciones";
import { SiniestroDetalles } from "./siniestro-detalles";
import { SiniestroChat } from "./siniestro-chat";

export function SiniestroMenu({ poliza, siniestro, chats, authUser }: { poliza: Poliza, siniestro: Siniestro, chats: ChatSiniestro[], authUser: number }) {
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
                    <TabsTrigger value="ficha">Siniestro</TabsTrigger>
                    <TabsTrigger value="siniestros">Contactos</TabsTrigger>
                </TabsList>

                <TabsContent value="ficha">
                    <div className="flex flex-col md:flex-row justify-between gap-6">
                        <SiniestroDetalles siniestro={siniestro} />
                        <div className="md:w-1/3 w-full">
                            <SiniestroLogoAcciones logoUrl={poliza.compania.url_logo} />
                        </div>
                    </div>
                    <SiniestroChat chats={chats} authUser={authUser} siniestroId={siniestro.id} />
                </TabsContent>

                <TabsContent value="contactos">
                    {/* <Contactos siniestro={siniestro} /> */}
                </TabsContent>
            </Tabs>
        </div>
    );
}

