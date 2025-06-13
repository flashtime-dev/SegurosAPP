import CrearSiniestroModal from "@/components/siniestros/CrearSiniestroModal";
import EditarSiniestroModal from "@/components/siniestros/EditarSiniestroModal";
import { SiniestroCard } from "@/components/siniestros/SiniestroCard";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import { Poliza, Siniestro } from "@/types";
import { Head, usePage } from "@inertiajs/react";
import { Plus } from "lucide-react";
import { useEffect, useState } from "react";

export default function Siniestros() {
    //Obtener datos de la pagina
    const { props } = usePage<{ siniestros: Siniestro[], polizas: Poliza[]}>();
    const siniestros = props.siniestros;
    const polizas = props.polizas;

    //Obtener estados de los componentes
    const [isCreating, setIsCreating] = useState(false); // Estado para el modal de creación
    const [isEditing, setIsEditing] = useState(false);
    const [siniestroSeleccionado, setSiniestroSeleccionado] = useState<Siniestro | null>(null);

    //Para manejar la edicion
    const handleEdit = (siniestro: Siniestro) => {
        setSiniestroSeleccionado(siniestro);
        setIsEditing(true);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Siniestros', href: '/siniestros' }]}>
            <Head title="Siniestros" />
            <div className="container mx-auto px-4 py-6 space-y-6">
                <div className="flex justify-between items-center">
                    <h1 className="text-2xl font-bold">Siniestros</h1>
                    <Button onClick={() => setIsCreating(true)} className="flex items-center space-x-2">
                        <Plus className="mr-2" />Crear siniestro
                    </Button>
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {/* Mostrar todos los siniestro */}
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
        </AppLayout>
    );
}