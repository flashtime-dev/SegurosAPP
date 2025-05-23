import * as React from "react";
import { Card } from "@/components/ui/card";
import { format, parseISO } from "date-fns"; // Importa las funciones necesarias
import { Siniestro } from "@/types";
import { router } from "@inertiajs/react";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { Link } from "@inertiajs/react";

/**
 * Componente que muestra la información de una póliza de seguro en formato de tarjeta
 */
export function SiniestroCard({ siniestro, onEdit }: { siniestro: Siniestro; onEdit?: () => void }) {
    // Ref para el botón del menú
    const menuButtonRef = React.useRef<HTMLButtonElement>(null);
    // Ref para controlar si el menú está abierto
    const [isMenuOpen, setIsMenuOpen] = React.useState(false);

    const handleEdit = (e: Event) => {
        e.preventDefault();

        // Devolver el foco al botón del menú antes de cerrar
        if (menuButtonRef.current) {
            menuButtonRef.current.focus();
        }

        // Asegurarnos de que el menú se cierre correctamente
        setIsMenuOpen(false);

        // Esperar a que termine la animación de cierre antes de abrir el modal
        setTimeout(() => {
            if (onEdit) onEdit();
        }, 100);
    };

    return (
        <Card key={siniestro.id} className="relative border rounded-lg shadow-md hover:shadow-lg transition-shadow">
            {/* Dropdown Menu */}
            <DropdownMenu open={isMenuOpen} onOpenChange={setIsMenuOpen}>
                <DropdownMenuTrigger
                    ref={menuButtonRef}
                    className="absolute top-2 right-2 w-6 h-6 text-gray-500 hover:text-gray-700 focus:outline-none"
                >
                    &#x22EE; {/* Icono de tres puntos */}
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="z-10">
                    {onEdit && (
                        <DropdownMenuItem onSelect={handleEdit}>
                            Editar
                        </DropdownMenuItem>
                    )}
                    <DropdownMenuItem
                        onClick={() => {
                            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                                router.delete(route('siniestros.destroy', siniestro.id));
                            }
                        }}
                    >
                        <span className="text-red-600 w-full text-left">Borrar</span>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

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
                    <p className="text-gray-600">Poliza: {siniestro.poliza.numero}</p>
                    <p className="text-gray-600">Descripcion: {siniestro.declaracion}</p>
                </div>
            </Link>
        </Card>
    );
}