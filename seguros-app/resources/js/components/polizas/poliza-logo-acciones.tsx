import { Button } from "@/components/ui/button";
import { useState } from "react";
import { ConfirmarAnulacionModal } from "./ConfirmarAnulacionModal";

interface Props {
    logoUrl: string;
    telefono?: string;
    polizaId: number;
    numeroPoliza: string;
    onCrearSiniestro: () => void;
}

export function PolizaLogoAcciones({ logoUrl, telefono, polizaId, numeroPoliza, onCrearSiniestro }: Props) {
    const [isAnulacionModalOpen, setIsAnulacionModalOpen] = useState(false);

    return (
        <div className="flex flex-col items-center gap-4">
            <img src={logoUrl} alt="Logo Compañía" className="h-16 object-contain" />
            <p className="text-sm text-muted-foreground">{telefono}</p>
            <div className="w-full space-y-2">
                <Button variant="outline" className="w-full cursor-pointer" onClick={onCrearSiniestro}>
                    ⚡ Dar parte
                </Button>
                <Button
                    variant="outline"
                    className="w-full cursor-pointer"
                    onClick={() => setIsAnulacionModalOpen(true)}
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
