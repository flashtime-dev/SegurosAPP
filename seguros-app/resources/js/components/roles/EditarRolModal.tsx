import { Dialog, DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { FormEventHandler, useState } from "react";
import { Rol, Permiso } from "@/types";
import { useForm } from "@inertiajs/react";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import InputError from "@/components/input-error";
import { LoaderCircle } from "lucide-react";

type Props = {
    isOpen: boolean;        // Controla la visibilidad del modal
    onClose: () => void;    // Función para cerrar el modal
    rol: Rol;              // El rol que se va a editar
    permisos: Permiso[];   // Lista de todos los permisos disponibles
    permisosRol: Permiso[]; // Permisos actuales del rol
};

export default function EditarRolModal({ isOpen, onClose, rol, permisos, permisosRol }: Props) {
    //Inicializacion del formulario
    const { data, setData, put, processing, errors, reset } = useForm({
        nombre: rol.nombre || '',  // Nombre actual del rol o cadena vacía
        permisos: (permisosRol || []).map((permiso) => permiso.id), // IDs de los permisos actuales
    });

    //Estado de permisos seleccionados
    const [selectedPermisos, setSelectedPermisos] = useState<Permiso[]>(
        // Filtra los permisos iniciales para mostrar solo los asignados al rol
        permisos.filter((permiso) => (permisosRol || []).some((p) => p.id === permiso.id))
    );

    //Añadir permiso
    const addPermiso = (permiso: Permiso) => {
        //Si no encuentra el permiso
        if (!selectedPermisos.find((p) => p.id === permiso.id)) {
            //Añade el permiso a los seleccionados
            setSelectedPermisos([...selectedPermisos, permiso]);
            //Actualiza los permisos del form
            setData('permisos', [...data.permisos, permiso.id]);
        }
    };

    //Borrar permiso
    const removePermiso = (permiso: Permiso) => {
        // Filtra el permiso de los seleccionados
        const updatedPermisos = selectedPermisos.filter((p) => p.id !== permiso.id);
        setSelectedPermisos(updatedPermisos);
        setData('permisos', data.permisos.filter((id) => id !== permiso.id));
    };

    //Envio del form
    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('roles.update', rol.id), {
            onSuccess: () => {
                reset();
                onClose();
            },
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={(open) => !open && onClose()}>
            <DialogContent className="max-w-4xl max-h-[90vh] flex flex-col">
                <DialogHeader>
                    <DialogTitle>Editar Rol: {rol.nombre}</DialogTitle>
                </DialogHeader>

                <form onSubmit={submit} className="space-y-6 flex-1 overflow-y-auto">
                    <div className="flex items-center gap-4">
                        <Input
                            id="nombre"
                            type="text"
                            value={data.nombre}
                            placeholder="Nombre del rol"
                            autoFocus
                            required
                            onChange={(e) => {
                                const value = e.target.value;
                                setData('nombre', value.charAt(0).toUpperCase() + value.slice(1).toLowerCase());
                            }}
                        />
                        <InputError message={errors.nombre} className="mt-2" />
                    </div>

                    <div className="flex flex-col md:flex-row gap-6 w-full">
                        {/* Permisos disponibles */}
                        <Card className="w-full md:w-1/2 shadow-md border rounded-2xl">
                            <CardHeader>
                                <CardTitle className="text-xl font-semibold text-gray-800">
                                    Permisos disponibles
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <ScrollArea className="h-72 pr-2">
                                    <ul className="space-y-2">
                                        {permisos
                                            .filter((permiso) => !selectedPermisos.find((p) => p.id === permiso.id))
                                            .map((permiso) => (
                                                <li key={permiso.id} className="flex items-center justify-between">
                                                    <span className="text-sm text-gray-800 dark:text-gray-100">{permiso.descripcion}</span>
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                        onClick={() => addPermiso(permiso)}
                                                    >
                                                        Añadir
                                                    </Button>
                                                </li>
                                            ))}
                                    </ul>
                                </ScrollArea>
                            </CardContent>
                        </Card>

                        {/* Permisos seleccionados */}
                        <Card className="w-full md:w-1/2 shadow-md border rounded-2xl">
                            <CardHeader>
                                <CardTitle className="text-xl font-semibold text-gray-800">
                                    Permisos seleccionados
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <ScrollArea className="h-72 pr-2">
                                    {selectedPermisos.length > 0 ? (
                                        <ul className="space-y-2">
                                            {selectedPermisos.map((permiso) => (
                                                <li key={permiso.id} className="flex items-center justify-between">
                                                    <span className="text-sm text-gray-800 dark:text-gray-100">{permiso.descripcion}</span>
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                        onClick={() => removePermiso(permiso)}
                                                    >
                                                        Quitar
                                                    </Button>
                                                </li>
                                            ))}
                                        </ul>
                                    ) : (
                                        <p className="text-gray-600 dark:text-gray-400 italic">No hay permisos seleccionados.</p>
                                    )}
                                </ScrollArea>
                            </CardContent>
                        </Card>
                    </div>

                    <div className="flex justify-end gap-4">
                        <Button type="button" variant="outline" onClick={onClose}>
                            Cancelar
                        </Button>
                        <Button type="submit" disabled={processing}>
                            {processing && <LoaderCircle className="mr-2 h-4 w-4 animate-spin" />}
                            Guardar cambios
                        </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    );
}
