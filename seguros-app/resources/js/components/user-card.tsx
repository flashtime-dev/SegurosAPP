import { User } from "@/types";
import { Link, router } from "@inertiajs/react";
import { Card } from "@/components/ui/card";
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from "@/components/ui/dropdown-menu";
import { Badge } from "@/components/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";

export function UserCard({ usuario }: { usuario: User }) {
    return (
        <Card className="relative border rounded-lg shadow-md hover:shadow-lg transition-shadow">
            {/* Dropdown Menu */}
            <DropdownMenu>
                <DropdownMenuTrigger className="absolute top-2 right-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                    &#x22EE; {/* Icono de tres puntos */}
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="z-10">
                    <DropdownMenuItem
                        onClick={() => {
                                router.visit(route('usuarios.show', usuario.id));
                            }
                        }
                        className="text-gray-700"
                    >
                            Editar
                    </DropdownMenuItem>
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
            <Link href={`/usuarios/${usuario.id}`} className="flex items-center space-x-4 p-4">
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
            </Link>
        </Card>
    );
}