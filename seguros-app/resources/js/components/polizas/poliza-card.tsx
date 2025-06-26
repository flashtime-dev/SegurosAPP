import * as React from "react";
import { Card } from "@/components/ui/card";
import { cn } from "@/lib/utils";
import { Poliza } from "@/types";
import { router } from "@inertiajs/react";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { Link } from "@inertiajs/react";
import { Edit, Trash2 } from "lucide-react";

/**
 * Componente que muestra la información de una póliza de seguro en formato de tarjeta
 */
export function PolizaCard({ poliza, onEdit }: { poliza: Poliza; onEdit?: () => void }) {
    // Ref para el botón del menú (usado para controlar el foco)
    const menuButtonRef = React.useRef<HTMLButtonElement>(null);
    // Ref para controlar si el menú está abierto o cerrado
    const [isMenuOpen, setIsMenuOpen] = React.useState(false);
    // Función que devuelve el color del estado de la póliza basado en su estado
    const getEstadoColor = () => {
        switch (poliza.estado.toLowerCase()) {
            case 'en vigor':
                return 'bg-green-600 dark:bg-green-700';
            case 'vencida':
                return 'bg-gray-500 dark:bg-gray-600';
            case 'anulada':
                return 'bg-red-500 dark:bg-red-600';
            case 'solicitada':
                return 'bg-yellow-500 dark:bg-yellow-600';
            case 'externa':
                return 'bg-blue-500 dark:bg-blue-600';
            default:
                return 'bg-gray-500 dark:bg-gray-600';
        }
    };
    // Función para formatear las fechas en un formato legible
    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString();
    };
    // Función para manejar la edición de la póliza (abre el modal de edición)
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
        // Card que contiene la información de la póliza
        <Card className="relative rounded-lg border shadow-md transition-shadow hover:shadow-lg">
            {/* Menú desplegable */}
            <DropdownMenu open={isMenuOpen} onOpenChange={setIsMenuOpen}>
                <DropdownMenuTrigger
                    ref={menuButtonRef}
                    className="absolute top-2 right-2 h-6 w-6 cursor-default hover:text-gray-700 dark:hover:text-gray-500"
                >
                    &#x22EE; {/* Icono de tres puntos */}
                </DropdownMenuTrigger>
                {/* Contenido del menú */}
                <DropdownMenuContent align="end" className="z-10">
                    {onEdit && (
                        // Opción de editar si la función onEdit está disponible
                        <DropdownMenuItem onSelect={handleEdit}>
                            <Edit className="mr-1 inline h-4 w-4 dark:text-gray-300" />
                            Editar
                        </DropdownMenuItem>
                    )}
                    {/* Opción de eliminar la póliza */}
                    <DropdownMenuItem
                        onClick={() => {
                            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                                router.delete(route('polizas.destroy', poliza.id));
                            }
                        }}
                    >
                        <Trash2 className="mr-1 inline h-4 w-4 text-red-500 dark:text-red-400" />
                        <span className="w-full text-left text-red-600 dark:text-red-400">Borrar</span>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            {/* Enlace a la página de detalles de la póliza */}
            <Link href={`/polizas/${poliza.id}`} className="p-4">
                {/* Logo de la compañía de seguros */}
                <img src={poliza.compania.url_logo} alt={`${poliza.compania.nombre} logo`} className="mx-auto mb-4 h-16 w-auto object-contain" />

                {/* Información principal de la póliza */}
                <div className="mb-2 flex items-center justify-between">
                    {/* Nombre de la comunidad asegurada */}
                    <h5 className="text-lg leading-tight font-bold text-gray-800 dark:text-gray-100">{poliza.comunidad.nombre}</h5>
                    {/* Estado de la póliza con el color adecuado */}
                    <span className={cn('rounded-full px-3 py-1 text-xs font-medium text-white', getEstadoColor())}>{poliza.estado}</span>
                </div>
                {/* Dirección de la comunidad asegurada */}
                <p className="mb-2 text-sm text-gray-500 dark:text-gray-300">{poliza.comunidad.direccion}</p>
                {/* Información adicional (número de póliza y fecha de efecto) */}
                <p className="text-sm font-medium text-gray-600 dark:text-gray-300">
                    Póliza {poliza.numero}
                    <span className="float-right">{formatDate(poliza.fecha_efecto)}</span>
                </p>
            </Link>
        </Card>
    );
}