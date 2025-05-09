import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler, useEffect, useState } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { Rol, Permiso } from '@/types';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { ScrollArea } from "@/components/ui/scroll-area";

type Props = {
    rol: Rol;
    permisos: Permiso[];
    permisosRol: Permiso[];
};


export default function Edit({ rol, permisosRol, permisos }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        nombre: rol.nombre || '',
        permisos: (permisosRol || []).map((permiso) => permiso.id), // ✅ usar permisosRol
    });
    
    const [selectedPermisos, setSelectedPermisos] = useState<Permiso[]>(
        permisos.filter((permiso) => (permisosRol || []).some((p) => p.id === permiso.id)) // ✅ usar permisosRol
    );
    
    

    // Función para añadir un permiso a la lista seleccionada
    const addPermiso = (permiso: Permiso) => {
        if (!selectedPermisos.find((p) => p.id === permiso.id)) {
            setSelectedPermisos([...selectedPermisos, permiso]);
            setData('permisos', [...data.permisos, permiso.id]);
        }
    };

    // Función para eliminar un permiso de la lista seleccionada
    const removePermiso = (permiso: Permiso) => {
        const updatedPermisos = selectedPermisos.filter((p) => p.id !== permiso.id);
        setSelectedPermisos(updatedPermisos);
        setData('permisos', data.permisos.filter((id) => id !== permiso.id));
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('roles.update', rol.id)); // Actualizar el rol
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Editar rol', href: route('roles.edit', rol.id) }]}>
            <Head title={`Editar rol: ${rol.nombre}`} />

            <form className="py-5 px-20 space-y-6" onSubmit={submit}>
                {/* Nombre del rol */}
                <div className='flex align-items-center gap-4'>
                    <Input
                        id="nombre"
                        type="text"
                        value={data.nombre}
                        placeholder="Nombre del rol"
                        autoFocus
                        required
                        onChange={(e) => setData('nombre', e.target.value)}
                    />
                    <InputError message={errors.nombre} className="mt-2" />

                    <Button type="submit" disabled={processing}>
                        {processing ? <LoaderCircle className="animate-spin" /> : 'Guardar cambios'}
                    </Button>
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
                                                <span className="text-sm text-gray-800">{permiso.nombre}</span>
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
                                                <span className="text-sm text-gray-800">{permiso.nombre}</span>
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
                                    <p className="text-gray-600 italic">No hay permisos seleccionados.</p>
                                )}
                            </ScrollArea>
                        </CardContent>
                    </Card>
                </div>
            </form>
        </AppLayout>
    );
}