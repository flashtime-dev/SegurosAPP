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

    function handleCerrar() {
        setLoading(true);
        if (confirm('¿Estás seguro de que deseas cerrar este siniestro?')) {
            router.post(`/siniestros/${id}/cerrar`, {}, {
                onFinish: () => setLoading(false),
            });
        } else {
            setLoading(false);
        }
    }

    return (
        <div className="flex flex-col items-center gap-4">
            <img src={logoUrl} alt="Logo Compañía" className="h-16 object-contain" />
            <p className="text-sm text-muted-foreground">{telefono}</p>
            <div className="w-full space-y-2">
                <Button
                    variant="outline"
                    className={`w-full dark:border-gray-500 ${loading || estaCerrado
                            ? "cursor-not-allowed bg-gray-300 hover:bg-gray-300 opacity-50 dark:hover:bg-gray-600"
                            : "cursor-pointer hover:dark:bg-gray-700"
                        }`}
                    onClick={handleCerrar}
                    disabled={loading || estaCerrado}
                >
                    {loading ? "Cerrando..." : "Cerrar siniestro"}
                </Button>
            </div>
        </div>
    );
}
