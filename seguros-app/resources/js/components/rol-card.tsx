import { useState } from "react";
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

export function MostrarRolesPermisos({ roles, tipoPermisos }: { roles: Rol[], tipoPermisos: TipoPermiso[] }) {
    const [selectedRol, setSelectedRol] = useState<Rol | null>(null);

    const handleDeleteRol = (rolId: number) => {
        if (confirm('¿Estás seguro de que deseas eliminar este rol?')) {
            router.delete(route('roles.destroy', rolId), {
                onSuccess: () => {
                    // Si el rol eliminado es el seleccionado, reseteamos el estado
                    if (selectedRol?.id === rolId) {
                        setSelectedRol(null);
                    }
                },
            });
        }
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
                                        className={`w-full justify-start font-medium rounded-lg transition-colors flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm text-gray-800 transition ${selectedRol?.id === rol.id ? "bg-blue-100" : ""
                                            }`}
                                        role="button"
                                        onClick={() => setSelectedRol(rol)}
                                    >
                                        {rol.nombre}
                                        <div className="justify-end ml-auto flex items-center">
                                            <DropdownMenu>
                                                <DropdownMenuTrigger className="ml-2">
                                                    <EllipsisVertical className="w-5 h-5 text-gray-500 hover:text-gray-700" />
                                                </DropdownMenuTrigger>
                                                <DropdownMenuContent align="end" className="z-10">
                                                    <DropdownMenuItem onClick={() => { router.visit(route('roles.edit', rol.id)); }}>
                                                        <Edit className="w-4 h-4 mr-1 inline" />
                                                        Editar
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem onClick={() => handleDeleteRol(rol.id)}>
                                                        <span className="text-red-600">
                                                            <Trash2 className="text-red-600 w-4 h-4 mr-1 inline" />
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
                                    }, {} as Record<string, typeof selectedRol.permisos>)
                                ).map(([tipo, permisos]) => (
                                    <div key={tipo} className="mb-6">
                                        <h3 className="text-md font-semibold text-gray-700 mb-3 border-b pb-1">
                                            {tipoPermisos.find((tp) => tp.id === Number(tipo))?.nombre || "Otros"}
                                        </h3>
                                        <ul className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            {permisos.map((permiso) => (
                                                <li
                                                    key={permiso.id}
                                                    className="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm text-gray-800 transition"
                                                >
                                                    {permiso.descripcion}
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                ))
                            ) : (
                                <p className="text-gray-600 italic">Este rol no tiene permisos asignados.</p>
                            )}
                        </ScrollArea>
                    ) : (
                        <p className="text-gray-600 italic">Haz clic en un rol para ver sus permisos.</p>
                    )}
                </CardContent>
            </Card>
        </div>
    );
}
