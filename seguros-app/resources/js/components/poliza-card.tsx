import * as React from "react";
import { Card } from "@/components/ui/card";
import { cn } from "@/lib/utils";
import { Poliza } from "@/types";
import { router } from "@inertiajs/react";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { Link } from "@inertiajs/react";

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
                return 'bg-green-600';
            case 'vencida':
                return 'bg-gray-500';
            case 'anulada':
                return 'bg-red-500';
            case 'solicitada':
                return 'bg-yellow-500';
            case 'externa':
                return 'bg-blue-500';
            default:
                return 'bg-gray-500';
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
                    className="absolute top-2 right-2 text-gray-500 hover:text-gray-700 focus:outline-none"
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
                                router.delete(route('polizas.destroy', poliza.id));
                            }
                        }}
                    >
                        <span className="text-red-600 w-full text-left">Borrar</span>
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
                    <h5 className="text-lg font-bold text-gray-800 leading-tight">
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

                <p className="text-gray-500 text-sm mb-2">
                    {poliza.comunidad.direccion}
                </p>

                <p className="text-sm text-gray-600 font-medium">
                    Póliza {poliza.numero}
                    <span className="float-right">{formatDate(poliza.fecha_efecto)}</span>
                </p>
            </Link>
        </Card>
    );
}