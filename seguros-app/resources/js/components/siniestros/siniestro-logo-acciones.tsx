import { Button } from "@/components/ui/button";
import { useState } from "react";
import { router } from "@inertiajs/react";
interface Props {
    id: number;
    logoUrl: string;
    telefono?: string;
    estadoSiniestro?: string;
}

export function SiniestroLogoAcciones({ id, logoUrl, telefono, estadoSiniestro }: Props) {
    const [loading, setLoading] = useState(false);
    const estaCerrado = estadoSiniestro?.toLowerCase() === "cerrado";

    // Función para cerrar o reabrir el siniestro según estado actual
    function handleAccion() {
        const accion = estaCerrado ? "reabrir" : "cerrar";
        const mensaje = estaCerrado
            ? "¿Estás seguro de que deseas reabrir este siniestro?"
            : "¿Estás seguro de que deseas cerrar este siniestro?";
        const ruta = `/siniestros/${id}/${accion}`;

        setLoading(true);
        if (confirm(mensaje)) {
            router.post(ruta, {}, {
                onFinish: () => setLoading(false),
            });
        } else {
            setLoading(false);
        }
    }

    return (
        <div className="flex flex-col items-center gap-4">
            {/* Informacion de la compañia */}
            <img src={logoUrl} alt="Logo Compañía" className="h-16 object-contain" />
            <p className="text-sm text-muted-foreground">{telefono}</p>
            <div className="w-full space-y-2">
                <Button
                    variant="outline"
                    className={`w-full dark:border-gray-500 ${loading
                            ? "cursor-not-allowed bg-gray-300 hover:bg-gray-300 opacity-50 dark:hover:bg-gray-600"
                            : "cursor-pointer hover:dark:bg-gray-700"
                        }`}
                    onClick={handleAccion}
                    disabled={loading}
                >
                    {loading
                        ? (estaCerrado ? "Reabriendo..." : "Cerrando...")
                        : (estaCerrado ? "Reabrir siniestro" : "Cerrar siniestro")}
                </Button>
            </div>
        </div>
    );
}
