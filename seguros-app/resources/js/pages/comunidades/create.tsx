import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        nombre: '',
        cif: '',
        direccion: '',
        ubi_catastral: '',
        ref_catastral: '',
        telefono: '',
    });

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

                </div>

                <Button type="submit" disabled={processing} className="w-full">
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2" />}
                    Crear comunidad
                </Button>
            </form>
        </AppLayout>
    );
}
function post(arg0: string, arg1: { onFinish: () => any; }) {
    throw new Error('Function not implemented.');
}

