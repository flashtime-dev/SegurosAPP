import { Button } from "@/components/ui/button";
import { useState } from "react";
import { router} from "@inertiajs/react";
interface Props {
    id: number;
    logoUrl: string;
    telefono?: string;
}

export function SiniestroLogoAcciones({ id, logoUrl, telefono }: Props) {
    const [loading, setLoading] = useState(false);

    function handleCerrar() {
        setLoading(true);
        router.post(`/siniestros/${id}/cerrar`, {}, {
            onFinish: () => setLoading(false),
        });
    }

    return (
        <div className="flex flex-col items-center gap-4">
            <img src={logoUrl} alt="Logo Compañía" className="h-16 object-contain" />
            <p className="text-sm text-muted-foreground">{telefono}</p>
            <div className="w-full space-y-2">
                <Button variant="outline" className="w-full cursor-pointer" onClick={handleCerrar} disabled={loading}>
                    {loading ? "Cerrando..." : "Cerrar siniestro"}
                </Button>
            </div>
        </div>
    );
}
