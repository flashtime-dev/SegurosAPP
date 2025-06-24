import * as React from "react";
import { Head, usePage } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { PolizaCard } from "@/components/polizas/poliza-card";
import { Poliza } from "@/types";
import FiltroPolizas from "@/components/polizas/polizas-filtro";
import PaginacionPolizas from "@/components/polizas/polizas-paginacion";
import { Button } from "@/components/ui/button";
import { Plus } from "lucide-react";
import CrearPolizaModal from "@/components/polizas/CrearPolizaModal";
import { Compania, Comunidad, Agente } from "@/types";
import EditarPolizaModal from "@/components/polizas/EditarPolizaModal";

// Componente principal que maneja el listado de pólizas
export default function Index() {
    // Obtener las propiedades pasadas desde la página (polizas, companias, comunidades y agentes)
    const { props } = usePage<{ polizas: Poliza[]; companias: Compania[]; comunidades: Comunidad[]; agentes: Agente[] }>();
    const polizas = props.polizas;
    const companias = props.companias;
    const comunidades = props.comunidades;
    const agentes = props.agentes;

    // Estado para los filtros aplicados
    const [filtros, setFiltros] = React.useState({
        nombreCompania: '',
        nombreComunidad: '',
        numeroPoliza: '',
        cif: '',
    });

    // Estado para el modal de creación y edición de pólizas
    const [isCreating, setIsCreating] = React.useState(false); // Estado para el modal de creación
    const [isEditing, setIsEditing] = React.useState(false); // Estado para el modal de edición
    const [polizaSeleccionada, setPolizaSeleccionada] = React.useState<Poliza | null>(null); // Para almacenar la póliza seleccionada para edición

    // Función para manejar la edición de una póliza
    const handleEdit = (poliza: Poliza) => {
        setPolizaSeleccionada(poliza);
        setIsEditing(true);
    };

    // Función para filtrar las pólizas según los filtros aplicados
    const filtrarPolizas = (poliza: Poliza) => {
        const { nombreCompania, nombreComunidad, numeroPoliza, cif } = filtros;

        // Comprobaciones para cada filtro, si el valor del filtro está presente en los datos de la póliza
        const matchNombreCompania = poliza.compania.nombre.toLowerCase().includes(nombreCompania.toLowerCase());
        const matchNombreComunidad = poliza.comunidad.nombre.toLowerCase().includes(nombreComunidad.toLowerCase());
        // La condición para el número de póliza maneja el caso de que el filtro esté vacío
        const matchNumeroPoliza = poliza.numero?.toLowerCase().includes(numeroPoliza.toLowerCase() || '') ?? true;
        const matchCif = poliza.comunidad.cif.toLowerCase().includes(cif.toLowerCase());
        // Devuelve true si la póliza coincide con todos los filtros
        return matchNombreCompania && matchNombreComunidad && matchNumeroPoliza && matchCif;
    };
    // Filtra las pólizas según los filtros establecidos
    const polizasFiltradas = polizas.filter(filtrarPolizas);
    // Estado para la paginación
    const [paginaActual, setPaginaActual] = React.useState(1); // Página actual
    const porPagina = 9; // Número de pólizas por página
    // Páginas de pólizas que se mostrarán en la página actual
    const polizasPaginadas = polizasFiltradas.slice(
        (paginaActual - 1) * porPagina, // Desplazamiento según la página actual
        paginaActual * porPagina, // Fin de la página
    );
    // Total de páginas calculado en base a las pólizas filtradas
    const totalPaginas = Math.ceil(polizasFiltradas.length / porPagina);

    return (
        <AppLayout breadcrumbs={[{ title: 'Pólizas', href: '/polizas' }]}>
            {/* Título de la página */}
            <Head title="Pólizas" />

            <div className="container mx-auto px-4 py-6">
                <div className="flex items-center justify-between">
                    {/* Título principal */}
                    <h1 className="mb-6 text-2xl font-bold">Pólizas</h1>
                    {/* Botón para crear nueva póliza */}
                    <Button onClick={() => setIsCreating(true)} className="flex items-center space-x-2">
                        <Plus className="h-4 w-4" />
                        <span>Crear póliza</span>
                    </Button>
                </div>
                {/* Filtro de pólizas */}
                <FiltroPolizas onFilter={(newFiltros) => setFiltros(newFiltros)} />

                {/* Listado de pólizas filtradas y paginadas */}
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    {polizasPaginadas.map((poliza) => (
                        <PolizaCard
                            key={poliza.id}
                            poliza={poliza}
                            onEdit={() => handleEdit(poliza)} // Función para editar la póliza
                        />
                    ))}
                    {/* Mensaje cuando no se encuentran pólizas */}
                    {polizasPaginadas.length === 0 && (
                        <p className="col-span-full text-center text-gray-500 dark:text-gray-400">No se encontraron pólizas.</p>
                    )}
                </div>
            </div>
            {/* Componente de paginación */}
            {polizasPaginadas.length > 0 && (
                <PaginacionPolizas paginaActual={paginaActual} totalPaginas={totalPaginas} onPageChange={(nueva) => setPaginaActual(nueva)} />
            )}

            {/* Modal para crear póliza */}
            <CrearPolizaModal
                isOpen={isCreating}
                onClose={() => setIsCreating(false)}
                companias={companias}
                comunidades={comunidades}
                agentes={agentes}
            />
            {/* Modal para editar una póliza */}
            <EditarPolizaModal
                isOpen={isEditing}
                onClose={() => {
                    setIsEditing(false);
                }}
                companias={companias}
                comunidades={comunidades}
                agentes={agentes}
                poliza={polizaSeleccionada} // Pasar póliza seleccionada al modal
            />
        </AppLayout>
    );
}
