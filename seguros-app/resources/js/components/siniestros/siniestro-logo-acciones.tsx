import { Button } from "@/components/ui/button";
import { useState, useEffect } from "react";
import { router, usePage } from "@inertiajs/react";
import { toast } from "sonner";
import CustomToast from "@/components/ui/CustomToaster";

interface Props {
    id: number;
    logoUrl: string;
    telefono?: string;
}

export function SiniestroLogoAcciones({ id, logoUrl, telefono }: Props) {
    const [loading, setLoading] = useState(false);

    const { props } = usePage<{ info?: { id: string; mensaje: string } }>();
    const info = props.info;

    useEffect(() => {
        if (info) {
            toast.custom(() => <CustomToast type="info" message={info.mensaje} />);
        }
    }, [info]);

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
