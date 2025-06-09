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
    // Ref para el botón del menú
    const menuButtonRef = React.useRef<HTMLButtonElement>(null);
    // Ref para controlar si el menú está abierto
    const [isMenuOpen, setIsMenuOpen] = React.useState(false);

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


    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString();
    };

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
        <Card className="relative border rounded-lg shadow-md hover:shadow-lg transition-shadow">
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
                                router.delete(route('polizas.destroy', poliza.id));
                            }
                        }}
                    >
                        <Trash2 className="w-4 h-4 mr-1 inline text-red-500 dark:text-red-400" /><span className="text-red-600 dark:text-red-400 w-full text-left">Borrar</span>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            <Link href={`/polizas/${poliza.id}`} className="p-4">
                <img
                    src={poliza.compania.url_logo}
                    alt={`${poliza.compania.nombre} logo`}
                    className="mx-auto h-16 w-auto object-contain mb-4"
                />

                <div className="flex justify-between items-center mb-2">
                    <h5 className="text-lg font-bold text-gray-800 dark:text-gray-100 leading-tight">
                        {poliza.comunidad.nombre}
                    </h5>
                    <span
                        className={cn(
                            "text-white text-xs font-medium px-3 py-1 rounded-full",
                            getEstadoColor()
                        )}
                    >
                        {poliza.estado}
                    </span>
                </div>

                <p className="text-gray-500 dark:text-gray-300 text-sm mb-2">
                    {poliza.comunidad.direccion}
                </p>

                <p className="text-sm text-gray-600 dark:text-gray-300 font-medium">
                    Póliza {poliza.numero}
                    <span className="float-right">{formatDate(poliza.fecha_efecto)}</span>
                </p>
            </Link>
        </Card>
    );
}