import * as React from "react";
import { Head, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { PolizaCard, Poliza } from '@/components/poliza-card';
import FiltroPolizas from '@/components/polizas-filtro';
import PaginacionPolizas from "@/components/polizas-paginacion";

/**
 * Página principal que muestra el listado de pólizas con filtros
 */
export default function Polizas() {
    const { props } = usePage<{ polizas: Poliza[] }>();
    const polizas = props.polizas;

    const [filtros, setFiltros] = React.useState({
        nombreCompania: '',
        nombreComunidad: '',
        numeroPoliza: '',
        cif: ''
    });

    const filtrarPolizas = (poliza: Poliza) => {
        const { nombreCompania, nombreComunidad, numeroPoliza, cif } = filtros;

        const matchNombreCompania = poliza.compania.nombre.toLowerCase().includes(nombreCompania.toLowerCase());
        const matchNombreComunidad = poliza.comunidad.nombre.toLowerCase().includes(nombreComunidad.toLowerCase());
        const matchNumeroPoliza = poliza.numero.toLowerCase().includes(numeroPoliza.toLowerCase());
        const matchCif = poliza.comunidad.cif.toLowerCase().includes(cif.toLowerCase());

        return matchNombreCompania && matchNombreComunidad && matchNumeroPoliza && matchCif;
    };

    const handleFilterChange = (newFiltros: typeof filtros) => {
        setFiltros(newFiltros);
        setPaginaActual(1); // Reiniciar a la primera página
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
        <AppLayout breadcrumbs={[{ title: 'Pólizas', href: '/polizas' }]}>
            <Head title="Pólizas" />

            <div className="container mx-auto px-4 py-6">
                <h1 className="text-2xl font-bold mb-6">Pólizas</h1>

                {/* Filtro de pólizas */}
                <FiltroPolizas onFilter={handleFilterChange} />

                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {polizasPaginadas.map((poliza) => (
                        <PolizaCard
                            key={poliza.id}
                            poliza={poliza}
                            onClick={() => window.location.href = `/polizas/${poliza.id}`}
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
        </AppLayout>
    );
}
