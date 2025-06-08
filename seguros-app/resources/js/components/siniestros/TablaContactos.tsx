import { Contacto } from "@/types";
import { format, parseISO } from "date-fns";
import { Card } from "@/components/ui/card";
import { Link } from "@inertiajs/react";
import { parsePhoneNumberFromString } from "libphonenumber-js";

interface Props {
    contactos: Contacto[];
}

function formatPhoneNumber(phone?: string): string {
    if (!phone) return "-";
    try {
        const parsed = phone.startsWith("+")
            ? parsePhoneNumberFromString(phone)
            : parsePhoneNumberFromString(phone, "ES"); // 🇪🇸
        return parsed ? parsed.formatInternational() : phone;
    } catch {
        return phone;
    }
}

export default function TablaSiniestros({ contactos }: Props) {
    if (contactos.length === 0) {
        return <p className="text-center text-gray-500 dark:text-gray-400">No hay contactos para mostrar</p>;
    }
    return (
        <div className="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
        {contactos.map((contacto) => (
            <Card key={contacto.id} className="dark:bg-gray-900 relative border rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div className="m-5" >
                    <h2 className="text-lg font-semibold">Nombre: {contacto.nombre || "-"}</h2>
                    <p className="text-gray-600 dark:text-gray-300">Cargo: {contacto.cargo || "-"}</p>
                    <p className="text-gray-600 dark:text-gray-300">Piso: {contacto.piso || "-"}</p>
                    <p className="text-gray-600 dark:text-gray-300">Teléfono: {formatPhoneNumber(contacto.telefono)}</p>
                </div>
            </Card>
            
        ))}</div>
    );
}