import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { PolizaDetalles } from "@/components/polizas/poliza-detalles";
import { Poliza, Siniestro, ChatPoliza } from "@/types";
import { PolizaLogoAcciones } from "./poliza-logo-acciones";
import { PolizaChat } from "./poliza-chat";
import TablaSiniestros from "../siniestros/TablaSiniestros";
import { useState } from "react";
import CrearSiniestroModal from "../siniestros/CrearSiniestroModal";

export function PolizaMenu({ poliza, siniestros, chats, authUser }: { poliza: Poliza, siniestros: Siniestro[], chats: ChatPoliza[], authUser: number }) {
    const [isCreatingSiniestro, setIsCreatingSiniestro] = useState(false);
    const isClosed = ['anulada', 'vencida'].includes(poliza.estado.toLowerCase());

    return (
        <>
            <Tabs defaultValue="ficha" className="w-full">
                <TabsList className="mb-6">
                    <TabsTrigger value="ficha">Ficha</TabsTrigger>
                    <TabsTrigger value="siniestros">Siniestros</TabsTrigger>
                </TabsList>

                <TabsContent value="ficha">
                    <div className="flex flex-col md:flex-row justify-between gap-6">
                        <PolizaDetalles poliza={poliza} />
                        <div className="md:w-1/3 w-full">
                            <PolizaLogoAcciones 
                                logoUrl={poliza.compania.url_logo}
                                polizaId={poliza.id}
                                numeroPoliza={poliza.numero}
                                onCrearSiniestro={() => setIsCreatingSiniestro(true)}
                            />
                        </div>
                    </div>
                    <PolizaChat chats={chats} authUser={authUser} polizaId={poliza.id} isClosed={isClosed} />
                </TabsContent>

                <TabsContent value="siniestros">
                    <TablaSiniestros siniestros={siniestros} />
                </TabsContent>
            </Tabs>

            <CrearSiniestroModal 
                isOpen={isCreatingSiniestro} 
                onClose={() => setIsCreatingSiniestro(false)} 
                polizas={[poliza]}
                polizaSeleccionada={poliza.id.toString()}
            />
        </>
    );
}

