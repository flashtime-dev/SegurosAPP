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
    const [isAnulacionModalOpen, setIsAnulacionModalOpen] = useState(false);

    const estado = estadoPoliza?.toLowerCase();
    const noSePuede = estado === "vencida" || estado === "anulada";


    return (
        <div className="flex flex-col items-center gap-4">
            <img src={logoUrl} alt="Logo Compañía" className="h-16 object-contain" />
            <p className="text-sm text-muted-foreground">{telefono}</p>
            <div className="w-full space-y-2">
                <Button
                    variant="outline"
                    className={cn(
                        "w-full dark:border-gray-500",
                        noSePuede
                            ? "cursor-not-allowed bg-gray-300 hover:bg-gray-300 opacity-50 dark:hover:bg-gray-600"
                            : "cursor-pointer hover:dark:bg-gray-700"
                    )}
                    onClick={onCrearSiniestro}
                    disabled={noSePuede}
                >
                    ⚡ Dar parte
                </Button>
                <Button
                    variant="outline"
                    className={cn(
                        "w-full dark:border-gray-500",
                        noSePuede
                            ? "cursor-not-allowed bg-gray-300 hover:bg-gray-300 opacity-50 dark:hover:bg-gray-600"
                            : "cursor-pointer hover:dark:bg-gray-700"
                    )}
                    onClick={() => setIsAnulacionModalOpen(true)}
                    disabled={noSePuede}
                >
                    ✉️ Carta anulación
                </Button>
            </div>

            <ConfirmarAnulacionModal
                isOpen={isAnulacionModalOpen}
                onClose={() => setIsAnulacionModalOpen(false)}
                polizaId={polizaId}
                numeroPoliza={numeroPoliza}
            />
        </div>
    );
}
