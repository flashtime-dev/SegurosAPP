import { Siniestro } from "@/types";
import { format, parseISO } from "date-fns";
import { Card } from "@/components/ui/card";
import { Link } from "@inertiajs/react";

interface Props {
    siniestros: Siniestro[];
}

export default function TablaSiniestros({ siniestros }: Props) {
    return (
        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        {siniestros.map((siniestro) => (
            <Card key={siniestro.id} className="relative border rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <Link href={`/siniestros/${siniestro.id}`} className="p-4">
                <div >
                    <h2 className="text-lg font-semibold">{siniestro.expediente}</h2>
                    {/* <p className="text-gray-600">Estado: {siniestro.estado}</p> */} {/*HACE FALTA AÑADIR A BD*/}
                    <p className="text-gray-600">
                        Fecha:{" "}
                        {siniestro.fecha_ocurrencia
                            ? format(parseISO(siniestro.fecha_ocurrencia), "dd/MM/yyyy")
                            : "Fecha no disponible"}
                    </p>
                    <p className="text-gray-600">Descripcion: {siniestro.declaracion}</p>
                </div>
            </Link>
            </Card>
            
        ))}</div>
    );
}