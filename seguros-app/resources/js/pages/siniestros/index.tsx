import AppLayout from "@/layouts/app-layout";
import { Siniestro } from "@/types";
import { Head, usePage } from "@inertiajs/react";
import { format, parseISO } from "date-fns"; // Importa las funciones necesarias

export default function Siniestros() {
    const { props } = usePage<{ siniestros: Siniestro[] }>();
    const siniestros = props.siniestros;

    return (
        <AppLayout breadcrumbs={[{ title: 'Siniestros', href: '/siniestros' }]}>
            <Head title="Siniestros" />
            <div className="container mx-auto px-4 py-6">
                <h1 className="text-2xl font-bold mb-6">Siniestros</h1>

                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {siniestros.map((siniestro) => (
                        <div key={siniestro.id} className="bg-white shadow-md rounded-lg p-4">
                            <h2 className="text-lg font-semibold">{siniestro.expediente}</h2>
                            {/* <p className="text-gray-600">Estado: {siniestro.estado}</p> */} {/*HACE FALTA AÑADIR A BD*/}
                            <p className="text-gray-600">
                                Fecha:{" "}
                                {siniestro.fecha_ocurrencia
                                    ? format(parseISO(siniestro.fecha_ocurrencia), "dd/MM/yyyy")
                                    : "Fecha no disponible"}
                            </p>
                            <p className="text-gray-600">Poliza: {siniestro.poliza.numero}</p>
                            <p className="text-gray-600">Descripcion: {siniestro.declaracion}</p>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}