import { Button } from "@/components/ui/button";
import { useState } from "react";
import { ConfirmarAnulacionModal } from "./ConfirmarAnulacionModal";
import { cn } from "@/lib/utils";

interface Props {
    logoUrl: string;
    telefono?: string;
    polizaId: number;
    numeroPoliza: string;
    estadoPoliza?: string;
    onCrearSiniestro: () => void;
}

export function PolizaLogoAcciones({ logoUrl, telefono, polizaId, numeroPoliza, estadoPoliza, onCrearSiniestro }: Props) {
    // Declaración del estado local para manejar si el modal de anulación está abierto o no.
    const [isAnulacionModalOpen, setIsAnulacionModalOpen] = useState(false);
    // Convertimos el estado de la póliza a minúsculas para evitar problemas de capitalización al comparar.
    const estado = estadoPoliza?.toLowerCase();
    // Verificamos si el estado de la póliza es "vencida" o "anulada", en cuyo caso se deshabilitarán ciertas acciones.
    const noSePuede = estado === 'vencida' || estado === 'anulada';

    return (
        <div className="flex flex-col items-center gap-4">
            {/* Se muestra el logo de la compañía de seguros */}
            <img src={logoUrl} alt="Logo Compañía" className="h-16 object-contain" />
            {/* Se muestra el número de teléfono si se ha proporcionado */}
            <p className="text-muted-foreground text-sm">{telefono}</p>
            <div className="w-full space-y-2">
                {/* Botón para dar parte de un siniestro */}
                <Button
                    variant="outline"
                    className={`w-full dark:border-gray-500 ${
                        noSePuede
                            ? 'cursor-not-allowed bg-gray-300 opacity-50 hover:bg-gray-300 dark:hover:bg-gray-600'
                            : 'cursor-pointer hover:dark:bg-gray-700'
                    }`}
                    onClick={onCrearSiniestro}
                    disabled={noSePuede}
                >
                    ⚡ Dar parte
                </Button>
                {/* Botón para solicitar la carta de anulación de la póliza */}
                <Button
                    variant="outline"
                    className={`w-full dark:border-gray-500 ${
                        noSePuede
                            ? 'cursor-not-allowed bg-gray-300 opacity-50 hover:bg-gray-300 dark:hover:bg-gray-600'
                            : 'cursor-pointer hover:dark:bg-gray-700'
                    }`}
                    onClick={() => setIsAnulacionModalOpen(true)}
                    disabled={noSePuede}
                >
                    ✉️ Carta anulación
                </Button>
            </div>
            {/* Modal de confirmación para la anulación de la póliza */}
            <ConfirmarAnulacionModal
                isOpen={isAnulacionModalOpen} // Controla si el modal está abierto o cerrado.
                onClose={() => setIsAnulacionModalOpen(false)} // Cierra el modal.
                polizaId={polizaId} // Pasa el ID de la póliza al modal.
                numeroPoliza={numeroPoliza} // Pasa el número de la póliza al modal.
            />
        </div>
    );
}
