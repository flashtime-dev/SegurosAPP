import { useRef, useState } from "react";
import { TipoPermiso, Rol } from "@/types";
import {
    Card,
    CardHeader,
    CardTitle,
    CardContent,
} from "@/components/ui/card";
import { ScrollArea } from "@/components/ui/scroll-area";
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from "@/components/ui/dropdown-menu";
import { Edit, EllipsisVertical, Trash2 } from "lucide-react";
import { router } from "@inertiajs/react";

interface Props {
    roles: Rol[];
    tipoPermisos: TipoPermiso[];
    onEditRol?: (rol: Rol) => void;
}

export function MostrarRolesPermisos({ roles, tipoPermisos, onEditRol }: Props) {
    const [selectedRol, setSelectedRol] = useState<Rol | null>(null);
    const [isMenuOpen, setIsMenuOpen] = useState<{ [key: number]: boolean }>({});
    const menuButtonRefs = useRef<{ [key: number]: HTMLButtonElement | null }>({});

    const handleEditRol = (e: Event, rol: Rol) => {
        e.preventDefault();
        
        // Devolver el foco al botón del menú antes de cerrar
        if (menuButtonRefs.current[rol.id]) {
            menuButtonRefs.current[rol.id]?.focus();
        }
        
        // Asegurarnos de que el menú se cierre correctamente
        setIsMenuOpen(prev => ({ ...prev, [rol.id]: false }));
        
        // Esperar a que termine la animación de cierre antes de abrir el modal
        setTimeout(() => {
            if (onEditRol) onEditRol(rol);
        }, 100);
    };

    const handleDeleteRol = (rolId: number) => {
        if (confirm('¿Estás seguro de que deseas eliminar este rol?')) {
            router.delete(route('roles.destroy', rolId), {
                onSuccess: () => {
                    if (selectedRol?.id === rolId) {
                        setSelectedRol(null);
                    }
                },
            });
        }
    };

    const setMenuButtonRef = (id: number) => (el: HTMLButtonElement | null) => {
        menuButtonRefs.current[id] = el;
    };

    return (
        <div className="flex flex-col md:flex-row gap-6 w-full">
            {/* Lista de Roles */}
            <Card className="w-full md:w-1/3 shadow-md border rounded-2xl">
                <CardHeader>
                    <CardTitle className="text-xl font-semibold text-gray-800">
                        Roles disponibles
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <ScrollArea className="h-72 pr-2">
                        <ul className="space-y-2">
                            {roles.map((rol) => (
                                <li key={rol.id} className="flex items-center">
                                    <div
                                        className={`cursor-pointer break-all w-full justify-start font-medium rounded-lg transition-colors flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md text-sm text-gray-800 dark:text-gray-100 transition ${selectedRol?.id === rol.id ? "bg-blue-100" : ""
                                            }`}
                                        role="button"
                                        onClick={() => setSelectedRol(rol)}
                                    >
                                        {rol.nombre}
                                        <div className="justify-end ml-auto flex items-center">
                                            <DropdownMenu
                                                open={isMenuOpen[rol.id]}
                                                onOpenChange={(open) => setIsMenuOpen(prev => ({ ...prev, [rol.id]: open }))}
                                            >
                                                <DropdownMenuTrigger
                                                    ref={setMenuButtonRef(rol.id)}
                                                    className="ml-2 focus:outline-none"
                                                >
                                                    <EllipsisVertical className="w-5 h-5 text-gray-500 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-400" />
                                                </DropdownMenuTrigger>
                                                <DropdownMenuContent align="end" className="z-10">
                                                    <DropdownMenuItem onSelect={(e) => handleEditRol(e, rol)}>
                                                        <Edit className="w-4 h-4 mr-1 inline dark:text-gray-300" />
                                                        Editar
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem onClick={() => handleDeleteRol(rol.id)}>
                                                        <span className="text-red-600 dark:text-red-400">
                                                            <Trash2 className="text-red-600 dark:text-red-400 w-4 h-4 mr-1 inline" />
                                                            Eliminar
                                                        </span>
                                                    </DropdownMenuItem>
                                                </DropdownMenuContent>
                                            </DropdownMenu>
                                        </div>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    </ScrollArea>
                </CardContent>
            </Card>

            {/* Permisos del Rol */}
            <Card className="w-full md:w-2/3 shadow-md border rounded-2xl relative">
                <CardHeader>
                    <CardTitle className="text-xl font-semibold text-gray-800">
                        {selectedRol
                            ? `Permisos asignados a "${selectedRol.nombre}"`
                            : "Selecciona un rol para ver sus permisos"}
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    {selectedRol ? (
                        <ScrollArea className="h-72 pr-2">
                            {selectedRol.permisos && selectedRol.permisos.length > 0 ? (
                                Object.entries(
                                    selectedRol.permisos.reduce((acc, permiso) => {
                                        const tipo = permiso.id_tipo || "Sin tipo";
                                        if (!acc[tipo]) acc[tipo] = [];
                                        acc[tipo].push(permiso);
                                        return acc;
                                    }, {} as { [key: string]: any[] })
                                ).map(([tipo, permisos]) => (
                                    <div key={tipo} className="mb-4">
                                        <h3 className="text-md font-semibold mb-2">
                                            {tipoPermisos.find(t => t.id.toString() === tipo)?.nombre || tipo}
                                        </h3>
                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            {permisos.map(permiso => (
                                                <div
                                                    key={permiso.id}
                                                    className="p-2 bg-gray-50 dark:bg-gray-600 rounded-lg text-sm dark:text-gray-100"
                                                >
                                                    {permiso.descripcion}
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <p className="text-center text-gray-500">Este rol no tiene permisos asignados</p>
                            )}
                        </ScrollArea>
                    ) : (
                        <div className="h-72 flex items-center justify-center">
                            <p className="text-gray-500">Selecciona un rol para ver sus permisos asignados</p>
                        </div>
                    )}
                </CardContent>
            </Card>
        </div>
    );
}
