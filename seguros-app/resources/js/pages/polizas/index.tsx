import * as React from "react";
import { Head, usePage } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { PolizaCard } from "@/components/poliza-card";
import { Poliza } from "@/types";
import FiltroPolizas from "@/components/polizas-filtro";
import PaginacionPolizas from "@/components/polizas-paginacion";
import { Button } from "@/components/ui/button";
import { Plus } from "lucide-react";
import CrearPolizaModal from "@/components/polizas/CrearPolizaModal";
import { Compania, Comunidad, Agente } from "@/types";
import EditarPolizaModal from "@/components/polizas/EditarPolizaModal";

export default function Index() {
    const { props } = usePage<{ polizas: Poliza[], companias: Compania[], comunidades: Comunidad[], agentes: Agente[] }>();
    const polizas = props.polizas;
    const companias = props.companias;
    const comunidades = props.comunidades;
    const agentes = props.agentes;

    const [filtros, setFiltros] = React.useState({
        nombreCompania: "",
        nombreComunidad: "",
        numeroPoliza: "",
        cif: "",
    });

    const [isCreating, setIsCreating] = React.useState(false); // Estado para el modal de creación
    const [isEditing, setIsEditing] = React.useState(false);
    const [polizaSeleccionada, setPolizaSeleccionada] = React.useState<Poliza | null>(null);

    const handleEdit = (poliza: Poliza) => {
        setPolizaSeleccionada(poliza);
        setIsEditing(true);
    };

    const filtrarPolizas = (poliza: Poliza) => {
        const { nombreCompania, nombreComunidad, numeroPoliza, cif } = filtros;

        const matchNombreCompania = poliza.compania.nombre.toLowerCase().includes(nombreCompania.toLowerCase());
        const matchNombreComunidad = poliza.comunidad.nombre.toLowerCase().includes(nombreComunidad.toLowerCase());
        const matchNumeroPoliza = poliza.numero.toLowerCase().includes(numeroPoliza.toLowerCase());
        const matchCif = poliza.comunidad.cif.toLowerCase().includes(cif.toLowerCase());

        return matchNombreCompania && matchNombreComunidad && matchNumeroPoliza && matchCif;
    };

    const polizasFiltradas = polizas.filter(filtrarPolizas);

    const [paginaActual, setPaginaActual] = React.useState(1);
    const porPagina = 9; // Número de pólizas por página

    const polizasPaginadas = polizasFiltradas.slice(
        (paginaActual - 1) * porPagina,
        paginaActual * porPagina
    );

    const totalPaginas = Math.ceil(polizasFiltradas.length / porPagina);

    return (
        <AppLayout breadcrumbs={[{ title: "Pólizas", href: "/polizas" }]}>
            <Head title="Pólizas" />

            <div className="container mx-auto px-4 py-6">
                <div className="flex justify-between items-center">
                    <h1 className="text-2xl font-bold mb-6">Pólizas</h1>
                    <Button onClick={() => setIsCreating(true)} className="flex items-center space-x-2">
                        <Plus className="w-4 h-4" />
                        <span>Crear póliza</span>
                    </Button>
                </div>
                {/* Filtro de pólizas */}
                <FiltroPolizas onFilter={(newFiltros) => setFiltros(newFiltros)} />

                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {polizasPaginadas.map((poliza) => (
                        <PolizaCard
                            key={poliza.id}
                            poliza={poliza}
                            onEdit={() => handleEdit(poliza)} // Pasar función de edición
                        />
                    ))}
                    {polizasPaginadas.length === 0 && (
                        <p className="col-span-full text-center text-gray-500">No se encontraron pólizas.</p>
                    )}
                </div>
            </div>

            <PaginacionPolizas
                paginaActual={paginaActual}
                totalPaginas={totalPaginas}
                onPageChange={(nueva) => setPaginaActual(nueva)}
            />

            {/* Modal para crear póliza */}
            <CrearPolizaModal isOpen={isCreating} onClose={() => setIsCreating(false)} companias={companias} comunidades={comunidades} agentes={agentes} />

            <EditarPolizaModal
                isOpen={isEditing}
                onClose={() => {setIsEditing(false)}}
                companias={companias}
                comunidades={comunidades}
                agentes={agentes}
                poliza={polizaSeleccionada} // Pasar póliza seleccionada
            />
        </AppLayout>
    );
}
