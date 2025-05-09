import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';

type Comunidad = {
    id: number;
    nombre: string;
    cif: string;
    direccion: string;
    ubi_catastral: string;
    ref_catastral: string;
    telefono: string;
};

type Props = {
    comunidad: Comunidad;
};

export default function Edit({ comunidad }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        nombre: comunidad.nombre || '',
        cif: comunidad.cif || '',
        direccion: comunidad.direccion || '',
        ubi_catastral: comunidad.ubi_catastral || '',
        ref_catastral: comunidad.ref_catastral || '',
        telefono: comunidad.telefono || '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('comunidades.update', comunidad.id), {
            onFinish: () => console.log('Comunidad actualizada'),
        });
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Editar comunidad', href: route('comunidades.edit', comunidad.id) }]}>
            <Head title={`Editar comunidad: ${comunidad.nombre}`} />

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
                            placeholder="Teléfono de contacto"
                        />
                        <InputError message={errors.telefono} className="mt-2" />
                    </div>

                </div>

                <Button type="submit" disabled={processing} className="w-full">
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2" />}
                    Guardar cambios
                </Button>
            </form>
        </AppLayout>
    );
}