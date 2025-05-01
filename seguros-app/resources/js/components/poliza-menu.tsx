import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { PolizaDetalles } from "@/components/poliza-detalles";
//import { Siniestros } from "@/components/siniestros";
//import { Recibos } from "@/components/recibos";
import { Poliza } from "@/types";
import { PolizaLogoAcciones } from "./poliza-logo-acciones";
import { ChatPoliza } from "@/types";
import { PolizaChat } from "./poliza-chat";

export function PolizaMenu({ poliza, chats, authUser }: { poliza: Poliza, chats: ChatPoliza[], authUser: number }) {
    return (
        <div>
            <Tabs defaultValue="ficha" className="w-full">
                <TabsList className="mb-6">
                    <TabsTrigger value="ficha">Ficha</TabsTrigger>
                    <TabsTrigger value="siniestros">Siniestros</TabsTrigger>
                    <TabsTrigger value="recibos">Recibos</TabsTrigger>
                </TabsList>

                <TabsContent value="ficha">
                    <div className="flex flex-col md:flex-row justify-between gap-6">
                        <PolizaDetalles poliza={poliza} />
                        <div className="md:w-1/3 w-full">
                            <PolizaLogoAcciones logoUrl={poliza.compania.url_logo} telefono="934165046" />
                        </div>
                    </div>
                    <PolizaChat chats={chats} authUser={authUser} />
                </TabsContent>

                {/* <TabsContent value="siniestros">
                    <Siniestros polizaId={poliza.id} />
                </TabsContent> */}

                {/*<TabsContent value="recibos">
                    <Recibos polizaId={poliza.id} />
                </TabsContent>*/}
            </Tabs>
        </div>
    );
}

