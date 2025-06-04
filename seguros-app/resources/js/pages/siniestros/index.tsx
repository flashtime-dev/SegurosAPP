import CrearSiniestroModal from "@/components/siniestros/CrearSiniestroModal";
import EditarSiniestroModal from "@/components/siniestros/EditarSiniestroModal";
import { SiniestroCard } from "@/components/siniestros/SiniestroCard";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import { Poliza, Siniestro } from "@/types";
import { Head, usePage } from "@inertiajs/react";
import { Plus } from "lucide-react";
import { useEffect, useState } from "react";
import { Toaster } from "@/components/ui/sonner"
import { toast } from "sonner";

export default function Siniestros() {
    const { props } = usePage<{ siniestros: Siniestro[], polizas: Poliza[], success?: { id: string; mensaje: string }, error?: string }>();
    const success = props.success;
    const error = props.error;
    const siniestros = props.siniestros;
    const polizas = props.polizas;
    const [isCreating, setIsCreating] = useState(false); // Estado para el modal de creación

    const [isEditing, setIsEditing] = useState(false);
    const [siniestroSeleccionado, setSiniestroSeleccionado] = useState<Siniestro | null>(null);

    const handleEdit = (siniestro: Siniestro) => {
        setSiniestroSeleccionado(siniestro);
        setIsEditing(true);
    };

    useEffect(() => {
        if (success) {
            toast.success(success.mensaje);
        }

        if (error) {
            toast.error(error);
        }

    }, [success, error]);

    return (
        <AppLayout breadcrumbs={[{ title: 'Siniestros', href: '/siniestros' }]}>
            <Head title="Siniestros" />
            <div className="container mx-auto px-4 py-6">
                <div className="flex justify-between items-center">
                    <h1 className="text-2xl font-bold mb-6">Siniestros</h1>
                    <Button onClick={() => setIsCreating(true)} className="flex items-center space-x-2">
                        <Plus className="w-4 h-4" />
                        <span>Crear siniestro</span>
                    </Button>
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {siniestros.map((siniestro) => (
                        <SiniestroCard siniestro={siniestro} key={siniestro.id} onEdit={() => { handleEdit(siniestro) }} />
                    ))}
                </div>
            </div>


            {/* Modal para crear siniestro */}
            <CrearSiniestroModal isOpen={isCreating} onClose={() => setIsCreating(false)} polizas={polizas} />

            <EditarSiniestroModal
                isOpen={isEditing}
                onClose={() => { setIsEditing(false) }}
                polizas={polizas} // Pasar póliza seleccionada
                siniestro={siniestroSeleccionado} // Pasar póliza seleccionada
            />

            <Toaster
                position="top-right"
                toastOptions={{
                    className: "text-sm font-medium",
                    classNames: {
                        success: "bg-green-100 text-green-800 border-green-300",
                        error: "bg-red-100 text-red-800 border-red-300",
                    },
                }}
            />
        </AppLayout>
    );
}