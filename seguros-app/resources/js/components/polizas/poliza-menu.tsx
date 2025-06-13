import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { PolizaDetalles } from "@/components/polizas/poliza-detalles";
import { Poliza, Siniestro, ChatPoliza } from "@/types";
import { PolizaLogoAcciones } from "./poliza-logo-acciones";
import { PolizaChat } from "./poliza-chat";
import TablaSiniestros from "../siniestros/TablaSiniestros";
import { useState } from "react";
import CrearSiniestroModal from "../siniestros/CrearSiniestroModal";

export function PolizaMenu({ poliza, siniestros, chats, authUser }: { poliza: Poliza, siniestros: Siniestro[], chats: ChatPoliza[], authUser: number }) {
    // Estado local para manejar si el modal de creación de siniestros está abierto.
    const [isCreatingSiniestro, setIsCreatingSiniestro] = useState(false);
    // Variable para determinar si la póliza está "cerrada" (anulada o vencida).
    const isClosed = ['anulada', 'vencida'].includes(poliza.estado.toLowerCase());

    return (
        <>
            {/* Componente Tabs para mostrar diferentes secciones (Ficha y Siniestros) */}
            <Tabs defaultValue="ficha" className="w-full">
                {/* Lista de triggers de tabs (botones para cambiar entre secciones) */}
                <TabsList className="mb-6">
                    <TabsTrigger value="ficha">Ficha</TabsTrigger>
                    <TabsTrigger value="siniestros">Siniestros</TabsTrigger>
                </TabsList>
                {/* Contenido del Tab "ficha" */}
                <TabsContent value="ficha">
                    <div className="flex flex-col justify-between gap-6 md:flex-row">
                        {/* Muestra los detalles de la póliza */}
                        <PolizaDetalles poliza={poliza} />
                        {/* Muestra el logo de la compañía y las acciones que el usuario puede realizar con la póliza */}
                        <div className="w-full md:w-1/3">
                            <PolizaLogoAcciones
                                logoUrl={poliza.compania.url_logo}
                                polizaId={poliza.id}
                                numeroPoliza={poliza.numero}
                                estadoPoliza={poliza.estado}
                                onCrearSiniestro={() => setIsCreatingSiniestro(true)} // Abre el modal para crear un siniestro
                            />
                        </div>
                    </div>
                    {/* Muestra el chat de la póliza, donde los usuarios pueden interactuar sobre la póliza */}
                    <PolizaChat chats={chats} authUser={authUser} polizaId={poliza.id} isClosed={isClosed} />
                </TabsContent>
                {/* Contenido del Tab "siniestros" */}
                <TabsContent value="siniestros">
                    <TablaSiniestros siniestros={siniestros} />
                </TabsContent>
            </Tabs>
            {/* Modal para crear un siniestro, que se abre cuando el usuario interactúa con el botón correspondiente */}
            <CrearSiniestroModal
                isOpen={isCreatingSiniestro}
                onClose={() => setIsCreatingSiniestro(false)}
                polizas={[poliza]}
                polizaSeleccionada={poliza.id.toString()}
            />
        </>
    );
}

