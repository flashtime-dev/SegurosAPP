import { Contacto } from "@/types";
import { format, parseISO } from "date-fns";
import { Card } from "@/components/ui/card";
import { Link } from "@inertiajs/react";

interface Props {
    contactos: Contacto[];
}

export default function TablaSiniestros({ contactos }: Props) {
    return (
        <div className="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
        {contactos.map((contacto) => (
            <Card key={contacto.id} className="relative border rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div className="m-5" >
                    <h2 className="text-lg font-semibold">Nombre: {contacto.nombre}</h2>
                    <p className="text-gray-600">Cargo: {contacto.cargo}</p>
                    <p className="text-gray-600">Piso: {contacto.piso}</p>
                    <p className="text-gray-600">Teléfono: {contacto.telefono}</p>
                </div>
            </Card>
            
        ))}</div>
    );
}