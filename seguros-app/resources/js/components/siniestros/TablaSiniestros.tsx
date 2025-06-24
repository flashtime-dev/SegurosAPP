import { Siniestro } from "@/types";
import { format, parseISO } from "date-fns";
import { Card } from "@/components/ui/card";
import { Link } from "@inertiajs/react";

interface Props {
    siniestros: Siniestro[];
}

export default function TablaSiniestros({ siniestros }: Props) {
    if (siniestros.length === 0) {
        return <p className="text-center text-gray-500 dark:text-gray-400">No hay siniestros para mostrar</p>;
    }
    return (
        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            {siniestros.map((siniestro) => (
                <Card key={siniestro.id} className="dark:bg-gray-900 relative border rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <Link href={`/siniestros/${siniestro.id}`} className="p-4">
                        <div>
                            <h2 className="text-lg font-semibold">{siniestro.expediente}</h2>
                            {/* <p className="text-gray-600">Estado: {siniestro.estado}</p> */} {/*HACE FALTA AÑADIR A BD*/}
                            <p className="text-gray-600 dark:text-gray-300">
                                Fecha:{" "}
                                {siniestro.fecha_ocurrencia
                                    ? format(parseISO(siniestro.fecha_ocurrencia), "dd/MM/yyyy")
                                    : "Fecha no disponible"}
                            </p>
                            <p className="text-gray-600 dark:text-gray-300">Descripcion: {siniestro.declaracion}</p>
                        </div>
                    </Link>
                </Card>

            ))}</div>
    );
}