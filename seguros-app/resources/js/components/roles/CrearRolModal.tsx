import { Dialog, DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { FormEventHandler, useState } from "react";
import { Permiso } from "@/types";
import { useForm } from "@inertiajs/react";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import InputError from "@/components/input-error";
import { LoaderCircle } from "lucide-react";

type Props = {
    isOpen: boolean;        // Controla si el modal está abierto
    onClose: () => void;    // Función para cerrar el modal
    permisos: Permiso[];    // Lista de permisos disponibles
};

export default function CrearRolModal({ isOpen, onClose, permisos }: Props) {
    //Datos para el formulario por defecto
    const { data, setData, post, processing, errors, reset } = useForm({
        nombre: '',
        permisos: [] as number[],
    });

    // Estado local para manejar la lista de permisos seleccionados
    const [selectedPermisos, setSelectedPermisos] = useState<Permiso[]>([]);

    // Función para agregar un permiso a la lista seleccionada
    // Evita duplicados y actualiza el formulario
    const addPermiso = (permiso: Permiso) => {
        if (!selectedPermisos.find((p) => p.id === permiso.id)) {
            setSelectedPermisos([...selectedPermisos, permiso]);
            setData('permisos', [...data.permisos, permiso.id]);
        }
    };

    // Función para remover un permiso de la lista seleccionada y actualizar el formulario
    const removePermiso = (permiso: Permiso) => {
        const updatedPermisos = selectedPermisos.filter((p) => p.id !== permiso.id);
        setSelectedPermisos(updatedPermisos);
        setData('permisos', data.permisos.filter((id) => id !== permiso.id));
    };

    // Función para manejar el envío del formulario
    // Previene comportamiento por defecto, envía datos vía Inertia.js, y al éxito resetea formulario, limpia permisos y cierra modal
    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('roles.store'), {
            onSuccess: () => {
                reset();
                setSelectedPermisos([]);
                onClose();
            },
        });
    };

    return (
        // Ventana modal responsive
        <Dialog open={isOpen} onOpenChange={(open) => !open && onClose()}>
            <DialogContent className="max-w-4xl max-h-[90vh] flex flex-col">
                <DialogHeader>
                    <DialogTitle>Nuevo Rol</DialogTitle>
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
                            //Capitalizar automaticamente la primera letra
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
                                        {/* Lista de permisos que se pueden agregar con filtrado para no mostrar los ya seleccionados */}
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
                                            {/* Muestra los permisos elegidos con opción para quitar cada permiso */}
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
                            Crear rol
                        </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    );
}
