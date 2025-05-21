import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
//import { Siniestros } from "@/components/siniestros";
//import { Recibos } from "@/components/recibos";
import { Siniestro, ChatSiniestro } from "@/types";
import { PolizaLogoAcciones } from "../polizas/poliza-logo-acciones";
import { PolizaChat } from "../polizas/poliza-chat";

export function SiniestroMenu({ siniestro, chats, authUser }: { siniestro: Siniestro, chats: ChatSiniestro[], authUser: number }) {
    return (
        <div>
            <Tabs defaultValue="ficha" className="w-full">
                <TabsList className="mb-6">
                    <TabsTrigger value="ficha">Siniestro</TabsTrigger>
                    <TabsTrigger value="siniestros">Contactos</TabsTrigger>
                </TabsList>

                <TabsContent value="ficha">
                    <div className="flex flex-col md:flex-row justify-between gap-6">
                        {/* <PolizaDetalles poliza={poliza} /> */}
                        <div className="md:w-1/3 w-full">
                            {/* <PolizaLogoAcciones logoUrl={poliza.compania.url_logo} telefono="934165046" /> */}
                        </div>
                    </div>
                    {/* <PolizaChat chats={chats} authUser={authUser} polizaId={poliza.id} /> */}
                </TabsContent>

                <TabsContent value="contactos">
                    {/* <Contactos siniestro={siniestro} /> */}
                </TabsContent>
            </Tabs>
        </div>
    );
}

