import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Head, usePage } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { Comunidad, User } from '@/types';
import { ComunidadCard } from '@/components/comunidades/comunidad-card';
import { useState } from 'react';
import CrearComunidadModal from '@/components/comunidades/CrearComunidadModal';
import EditarComunidadModal from '@/components/comunidades/EditarComunidadModal';

export default function Index() {
    const { props } = usePage<{ comunidades: Comunidad[], usuarios: User[] }>();
    const comunidades = props.comunidades;
    const usuarios = props.usuarios;
    
    const [isCreating, setIsCreating] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [comunidadSeleccionada, setComunidadSeleccionada] = useState<Comunidad | null>(null);

    const handleEdit = (comunidad: Comunidad) => {
        setComunidadSeleccionada(comunidad);
        setIsEditing(true);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Comunindades', href: route('comunidades.index') }]}>
            <Head title='Comunidades' />
            <div className="py-5 px-20 space-y-6">
                <h1 className="text-2xl font-bold">Comunidades</h1>
                <Button className="mb-5" onClick={() => setIsCreating(true)}>
                    <Plus className="mr-2"/>Nueva comunidad
                </Button>
                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {comunidades.map((comunidad) => (
                        <ComunidadCard
                            key={comunidad.id}
                            comunidad={comunidad}
                            onEdit={() => handleEdit(comunidad)}
                        />
                    ))}
                    {comunidades.length === 0 && (
                        <p className="col-span-full text-center text-gray-500">No se encontraron comunidades.</p>
                    )}
                </div>
            </div>

            {/* Modal para crear comunidad */}
            <CrearComunidadModal 
                isOpen={isCreating} 
                onClose={() => setIsCreating(false)}
                usuarios={usuarios} 
            />

            {/* Modal para editar comunidad */}
            {comunidadSeleccionada && (
                <EditarComunidadModal
                    isOpen={isEditing}
                    onClose={() => {
                        setIsEditing(false);
                        setComunidadSeleccionada(null);
                    }}
                    usuarios={usuarios}
                    comunidad={comunidadSeleccionada}
                />
            )}
        </AppLayout>
    );
}