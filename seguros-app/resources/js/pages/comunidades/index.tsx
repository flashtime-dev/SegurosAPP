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
    // Accede a las props compartidas desde el backend con Inertia.js
    const { props } = usePage<{ comunidades: Comunidad[]; usuarios: User[] }>();
    const comunidades = props.comunidades;
    const usuarios = props.usuarios;

    // Estados locales para controlar los modales y la comunidad seleccionada
    const [isCreating, setIsCreating] = useState(false); // Controla si se muestra el modal de creación
    const [isEditing, setIsEditing] = useState(false); // Controla si se muestra el modal de creación
    const [comunidadSeleccionada, setComunidadSeleccionada] = useState<Comunidad | null>(null); // Comunidad que se está editando

    // Función que se ejecuta al hacer clic en "Editar", define la comunidad a editar y abre el modal
    const handleEdit = (comunidad: Comunidad) => {
        setComunidadSeleccionada(comunidad);
        setIsEditing(true);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Comunidades', href: route('comunidades.index') }]}>
            {/* Define el título de la pestaña del navegador */}
            <Head title="Comunidades" />

            {/* Contenedor principal*/}
            <div className="space-y-6 px-4 py-5">
                {/* Encabezado con título y botón para crear nueva comunidad */}
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">Comunidades</h1>
                    <Button className="flex items-center space-x-2" onClick={() => setIsCreating(true)}>
                        <Plus className="mr-2" />
                        Nueva comunidad
                    </Button>
                </div>
                {/* Mostrar las tarjetas de comunidades */}
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    {/* Renderiza una tarjeta por cada comunidad */}
                    {comunidades.map((comunidad) => (
                        <ComunidadCard key={comunidad.id} comunidad={comunidad} onEdit={() => handleEdit(comunidad)} /> // Llama a handleEdit al hacer clic en "Editar"
                    ))}
                    {comunidades.length === 0 && (
                        <p className="col-span-full text-center text-gray-500 dark:text-gray-400">No se encontraron comunidades.</p>
                    )}
                </div>
            </div>

            {/* Modal para crear una comunidad */}
            <CrearComunidadModal isOpen={isCreating} onClose={() => setIsCreating(false)} usuarios={usuarios} />

            {/* Modal para editar una comunidad */}
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