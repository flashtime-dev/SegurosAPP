import * as React from "react";
import { Card, CardHeader, CardContent, CardTitle, CardDescription } from "@/components/ui/card";
import { Comunidad } from "@/types";
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from "@/components/ui/dropdown-menu";
import { Edit, EllipsisVertical, Trash2 } from "lucide-react";
import { router } from "@inertiajs/react";

export function ComunidadCard({ comunidad, onEdit }: { comunidad: Comunidad; onEdit?: () => void }) {
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
        <Card className="w-full relative" key={comunidad.id}>
            {/* Menú desplegable */}
            <DropdownMenu open={isMenuOpen} onOpenChange={setIsMenuOpen}>
                <DropdownMenuTrigger
                    ref={menuButtonRef}
                    // className="absolute top-2 right-2"
                    className="absolute top-2 right-2 w-6 h-6 hover:text-gray-700 dark:hover:text-gray-400"
                >
                    {/* <EllipsisVertical className="text-gray-500 hover:text-gray-700 cursor-pointer" /> */}
                    &#x22EE; {/* Icono de tres puntos */}
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="z-10">
                    <DropdownMenuItem onSelect={handleEdit}>
                        <Edit className="w-4 h-4 mr-1 inline dark:text-gray-300" />
                        Editar
                    </DropdownMenuItem>
                    <DropdownMenuItem
                        onClick={() => {
                            if (confirm('¿Estás seguro de que deseas eliminar esta comunidad?')) {
                                router.delete(route('comunidades.destroy', comunidad.id));
                            }
                        }}
                    >
                        <Trash2 className="w-4 h-4 mr-1 inline text-red-500 dark:text-red-400" /><span className="text-red-600 dark:text-red-400 w-full text-left">Borrar</span>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            {/* Contenido de la tarjeta */}
            <div className="p-4">
                <CardHeader className="flex-row items-center justify-between p-0 mb-4">
                    <div>
                        <CardTitle>{comunidad.nombre}</CardTitle>
                        <CardDescription>{comunidad.cif}</CardDescription>
                    </div>
                </CardHeader>
                <CardContent className="p-0">
                    <p className="text-sm text-gray-500 dark:text-gray-300">{comunidad.direccion}</p>
                    <p className="text-sm text-gray-500 dark:text-gray-300 mt-2">{comunidad.telefono}</p>
                </CardContent>
            </div>
        </Card>
    );
}