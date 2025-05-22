import * as React from "react";
import { User } from "@/types";
import { Link, router } from "@inertiajs/react";
import { Card } from "@/components/ui/card";
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from "@/components/ui/dropdown-menu";
import { Badge } from "@/components/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";

export function UserCard({ usuario, onEdit }: { usuario: User; onEdit?: () => void }) {
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
        <Card className="relative border rounded-lg shadow-md hover:shadow-lg transition-shadow">
            {/* Dropdown Menu */}
            <DropdownMenu open={isMenuOpen} onOpenChange={setIsMenuOpen}>
                <DropdownMenuTrigger 
                    ref={menuButtonRef}
                    className="absolute top-2 right-2 w-6 h-6 text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer"
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
                                router.delete(route('usuarios.destroy', usuario.id));
                            }
                        }}
                    >
                        <span className="text-red-600 w-full text-left">Borrar</span>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            {/* Contenido del Card */}
            <div className="p-4">
                <div className="flex items-center space-x-4">
                    {/* Avatar del usuario */}
                    <Avatar className="w-16 h-16">
                        <AvatarImage src={usuario.avatar || ""} alt={usuario.name} />
                        <AvatarFallback>{usuario.name[0]}</AvatarFallback>
                    </Avatar>

                    {/* Información del usuario */}
                    <div className="flex-1">
                        <div className="flex justify-between items-center mb-2">
                            <h2 className="text-lg font-bold text-gray-800">{usuario.name}</h2>
                            <Badge
                                variant={usuario.state === 1 ? "activo" : "inactivo"}
                                className="inline-block px-2 py-1 text-xs font-semibold rounded-full"
                            >
                                {usuario.state === 1 ? "Activo" : "Inactivo"}
                            </Badge>
                        </div>
                        <p className="text-gray-600 break-all">{usuario.email}</p>
                        <Badge
                            variant="default"
                            className="inline-block px-2 py-1 text-xs font-semibold rounded-full mt-2"
                        >
                            {usuario.rol.nombre}
                        </Badge>
                    </div>
                </div>
            </div>
        </Card>
    );
}