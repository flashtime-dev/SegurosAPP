import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';

import { User } from "@/types";

type Props = {
    usuarios: User[];
};

export default function Create({ usuarios }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        nombre: '',
        cif: '',
        direccion: '',
        ubi_catastral: '',
        ref_catastral: '',
        telefono: '',
        usuarios: [] as number[], // Array de IDs de usuarios
    });

    const agregarUsuario = (userId: number) => {
        if (!data.usuarios.includes(userId)) {
            setData('usuarios', [...data.usuarios, userId]);
        }
    };

    const eliminarUsuario = (userId: number) => {
        setData('usuarios', data.usuarios.filter(id => id !== userId));
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('comunidades.store'));
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Crear comunidad', href: route('comunidades.create') }]}>
            <Head title="Nueva comunidad" />

            <form className="max-w-2xl mx-auto p-4 space-y-6" onSubmit={submit}>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <Label htmlFor="nombre">Nombre</Label>
                        <Input
                            id="nombre"
                            value={data.nombre}
                            onChange={(e) => setData('nombre', e.target.value)}
                            disabled={processing}
                            required
                            autoFocus
                            placeholder="Nombre de la comunidad"
                        />
                        <InputError message={errors.nombre} className="mt-2" />
                    </div>

                    <div>
                        <Label htmlFor="cif">CIF</Label>
                        <Input
                            id="cif"
                            value={data.cif}
                            onChange={(e) => setData('cif', e.target.value)}
                            disabled={processing}
                            required
                            placeholder="CIF de la comunidad"
                        />
                        <InputError message={errors.cif} className="mt-2" />
                    </div>

                    <div className="sm:col-span-2">
                        <Label htmlFor="direccion">Dirección</Label>
                        <Input
                            id="direccion"
                            value={data.direccion}
                            onChange={(e) => setData('direccion', e.target.value)}
                            disabled={processing}
                            required
                            placeholder="Dirección de la comunidad"
                        />
                        <InputError message={errors.direccion} className="mt-2" />
                    </div>

                    <div>
                        <Label htmlFor="ubi_catastral">Ubicación catastral</Label>
                        <Input
                            id="ubi_catastral"
                            value={data.ubi_catastral}
                            onChange={(e) => setData('ubi_catastral', e.target.value)}
                            disabled={processing}
                            required
                            placeholder="Ubicación catastral"
                        />
                        <InputError message={errors.ubi_catastral} className="mt-2" />
                    </div>

                    <div>
                        <Label htmlFor="ref_catastral">Referencia catastral</Label>
                        <Input
                            id="ref_catastral"
                            value={data.ref_catastral}
                            onChange={(e) => setData('ref_catastral', e.target.value)}
                            disabled={processing}
                            required
                            placeholder="Referencia catastral"
                        />
                        <InputError message={errors.ref_catastral} className="mt-2" />
                    </div>

                    <div>
                        <Label htmlFor="telefono">Teléfono</Label>
                        <Input
                            id="telefono"
                            value={data.telefono}
                            onChange={(e) => setData('telefono', e.target.value)}
                            disabled={processing}
                            required
                            placeholder="Teléfono de contacto"
                        />
                        <InputError message={errors.telefono} className="mt-2" />
                    </div>

                    {/* Campo para añadir usuarios */}
                    <div className="sm:col-span-2 space-y-4">
                        <div className="flex flex-col gap-4">
                            <Label>Usuarios de la Comunidad</Label>
                            <div className="flex flex-wrap gap-2">
                                {data.usuarios.map((userId) => {
                                    const usuario = usuarios.find(u => u.id === userId);
                                    if (!usuario) return null;
                                    return (
                                        <div key={userId} className="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-2">
                                            <span>{usuario.name}</span>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="sm"
                                                className="h-auto p-1"
                                                onClick={() => eliminarUsuario(userId)}
                                            >
                                                ✕
                                            </Button>
                                        </div>
                                    );
                                })}
                            </div>
                            <div className="flex gap-2">
                                <Select
                                    onValueChange={(value) => agregarUsuario(Number(value))}
                                    value=""
                                >
                                    <SelectTrigger className="w-full">
                                        <SelectValue placeholder="Selecciona un usuario para agregar" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {usuarios
                                            .filter(u => !data.usuarios.includes(u.id))
                                            .map((usuario) => (
                                                <SelectItem key={usuario.id} value={String(usuario.id)}>
                                                    {usuario.name} ({usuario.email})
                                                </SelectItem>
                                            ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </div>
                </div>

                <Button type="submit" disabled={processing} className="w-full">
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2" />}
                    Crear comunidad
                </Button>
            </form>
        </AppLayout>
    );
}
