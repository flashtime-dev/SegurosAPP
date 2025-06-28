import * as React from "react";
import { Card } from "@/components/ui/card";
import { cn } from "@/lib/utils";
import { format, parseISO } from "date-fns"; // Importa las funciones necesarias
import { Siniestro } from "@/types";
import { router } from "@inertiajs/react";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { Link } from "@inertiajs/react";
import { Edit, Trash2 } from "lucide-react";

/**
 * Componente que muestra la información de una póliza de seguro en formato de tarjeta
 */
export function SiniestroCard({ siniestro, onEdit }: { siniestro: Siniestro; onEdit?: () => void }) {
    // Ref para el botón del menú
    const menuButtonRef = React.useRef<HTMLButtonElement>(null);
    // Ref para controlar si el menú está abierto
    const [isMenuOpen, setIsMenuOpen] = React.useState(false);

    const getEstadoColor = () => {
        switch (siniestro.estado.toLowerCase()) {
            case 'abierto':
                return 'bg-green-600 dark:bg-green-700';
            case 'cerrado':
                return 'bg-red-500 dark:bg-red-600';
            default:
                return 'bg-gray-500 dark:bg-gray-600';
        }
    };

    //Funcion para editar y controlar el foco al abrir el modal
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
                    className="cursor-default absolute top-2 right-2 w-6 h-6 hover:text-gray-700 dark:hover:text-gray-500"
                >
                    &#x22EE; {/* Icono de tres puntos */}
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="z-10">
                    {onEdit && (
                        <DropdownMenuItem onSelect={handleEdit}>
                            <Edit className="w-4 h-4 mr-1 inline dark:text-gray-300" />
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
                        <Trash2 className="w-4 h-4 mr-1 inline text-red-500 dark:text-red-400" /><span className="text-red-600 dark:text-red-400 w-full text-left">Borrar</span>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            <Link href={`/siniestros/${siniestro.id}`} className="p-4">
                <div >
                    <div className="flex justify-between items-center mb-2">
                        <h2 className="text-lg font-semibold dark:text-gray-100">{siniestro.poliza?.comunidad?.nombre ?? "Comunidad no disponible"}</h2>
                        <span
                            className={cn(
                                "text-white text-xs font-medium px-3 py-1 rounded-full",
                                getEstadoColor()
                            )}
                        >
                            {siniestro.estado}
                        </span>
                    </div>
                    {/* <p className="text-gray-600">Estado: {siniestro.estado}</p> */} {/*HACE FALTA AÑADIR A BD*/}
                    <p className="text-gray-600 dark:text-gray-300">Expediente: {siniestro.expediente}</p>
                    <p className="text-gray-600 dark:text-gray-300">Poliza: {siniestro.poliza.numero}</p>
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
    );
}