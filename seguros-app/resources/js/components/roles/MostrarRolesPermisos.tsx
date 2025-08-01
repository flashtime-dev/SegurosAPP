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
    // Definimos los estados para manejar la creación y edición de usuarios
    const [selectedRol, setSelectedRol] = useState<Rol | null>(null);
    const [isMenuOpen, setIsMenuOpen] = useState<{ [key: number]: boolean }>({});

    //Referencias botones de id:elemento
    const menuButtonRefs = useRef<{ [key: number]: HTMLButtonElement | null }>({});

    // Funcion para cambiar el foco y evitar problemas al editar un rol
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

    // Funcion para eliminar un rol
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

    // Funcion con parametro 'id' que llama a otra funcion con otro parametro 'el'
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
                            {/* Mostrar cada rol */}
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
                                            {/* Menu de los 3 puntitos */}
                                            <DropdownMenu
                                                // Inicialmente estara cerrado
                                                open={isMenuOpen[rol.id]}
                                                //Aqui asignamos si el menu se abre o cierra
                                                onOpenChange={(open) => setIsMenuOpen(prev => ({ ...prev, [rol.id]: open }))}
                                            >
                                                {/* Le añadimos la referencia del boton con el id del rol que apuntara al elemento menu */}
                                                <DropdownMenuTrigger
                                                    ref={setMenuButtonRef(rol.id)}
                                                    className="ml-2 focus:outline-none"
                                                >
                                                    {/* Icono de 3 puntitos */}
                                                    <EllipsisVertical className="w-5 h-5 text-gray-500 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-400" />
                                                </DropdownMenuTrigger>
                                                {/* Contenido del menu */}
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
                    {/* Si hay un rol seleccionado... */}
                    {selectedRol ? (
                        <ScrollArea className="h-72 pr-2">
                            {/* Mostrar los permisos del rol */}
                            {selectedRol.permisos && selectedRol.permisos.length > 0 ? (
                                /* Mostrar permisos agrupados por tipo */
                                Object.entries( // Transforma el objeto en un array de pares [tipo, permisos[]], para poder iterarlo con .map
                                    // se agrupan los permisos en acc
                                    selectedRol.permisos.reduce((acumulador, permiso) => {
                                        // Si no hay tipo, asigna "Sin tipo"
                                        const tipo = permiso.id_tipo || "Sin tipo";

                                        // Si no existe el array para ese tipo, crea uno
                                        if (!acumulador[tipo]) acumulador[tipo] = [];

                                        // Añade el permiso al array del tipo correspondiente
                                        acumulador[tipo].push(permiso);

                                        //Se devuelve el acumulador para tomarlo como valor inicial al siguiente permiso
                                        return acumulador;
                                    }, {} as { [key: string]: any[] }) // Marca el formato del objeto
                                ).map(([tipo, permisos]) => (
                                    <div key={tipo} className="mb-4">
                                        <h3 className="text-md font-semibold mb-2">
                                            {/* encontrar el nombre del id del tipo */}
                                            {tipoPermisos.find(t => t.id.toString() === tipo)?.nombre || tipo}
                                        </h3>
                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            {/* Mostrar los permisos */}
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
                                // Mensaje si el rol no tiene permisos
                                selectedRol.nombre === 'Superadministrador'
                                    ? <p className="text-center text-gray-500 dark:text-gray-400">El superadministrador tiene acceso a todo, no necesita permisos</p>
                                    : <p className="text-center text-gray-500 dark:text-gray-400">Este rol no tiene permisos asignados</p>
                            )}
                        </ScrollArea>
                    ) : (
                        // Si no hay un rol seleccionado mostrar mensaje
                        <div className="h-72 flex items-center justify-center">
                            <p className="text-gray-500 dark:text-gray-400">Selecciona un rol para ver sus permisos asignados</p>
                        </div>
                    )}
                </CardContent>
            </Card>
        </div>
    );
}
