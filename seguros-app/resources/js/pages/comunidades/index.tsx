import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Head, usePage } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { Comunidad } from '@/types';
import { ComunidadCard } from '@/components/comunidad-card';

export default function Index() {
    const { props } = usePage<{ comunidades: Comunidad[] }>();
    const comunidades = props.comunidades;

    return (
        <AppLayout breadcrumbs={[{ title: 'Comunindades', href: route('comunidades.index') }]}>
            <Head title='Comunidades' />
            <div className="py-5 px-20 space-y-6">
                <h1 className="text-2xl font-bold">Comunidades</h1>
                <Button className="mb-5" onClick={() => window.location.href = route('comunidades.create')}>
                    <Plus></Plus>Nueva comunidad
                </Button>
                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {comunidades.map((comunidad) => (
                        <ComunidadCard
                            key={comunidad.id}
                            comunidad={comunidad}
                        />
                    ))}
                    {comunidades.length === 0 && (
                        <p className="col-span-full text-center text-gray-500">No se encontraron pólizas.</p>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}