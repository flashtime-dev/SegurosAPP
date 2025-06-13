import CrearAgenteModal from '@/components/agentes/CrearAgenteModal';
import EditarAgenteModal from '@/components/agentes/EditarAgenteModal';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { Agente } from '@/types';
import { Head, router, useForm } from '@inertiajs/react';
import { Edit, Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';

export default function Index({ agentes }: { agentes: Agente[] }) {
    // Estados para modales y edición
    const [editingAgente, setEditingAgente] = useState<Agente | null>(null);
    const [isCreating, setIsCreating] = useState(false); // Estado para el modal de creación
    
    //Datos para formulario
    const { data, setData, post, put, processing, errors, reset } = useForm({
        nombre: '',
        email: '',
        telefono: '',
    });

    //Actualizar al editar
    const handleEdit = (agente: Agente) => {
        setEditingAgente(agente);
        setData({
            nombre: agente.nombre,
            email: agente.email,
            telefono: agente.telefono,
        });
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Agentes', href: route('agentes.index') }]}>
            <Head title="Lista de Agentes" />

            <div className="mx-auto max-w-6xl space-y-6 p-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold text-gray-800 dark:text-gray-100">Lista de Agentes</h1>
                    <Button onClick={() => setIsCreating(true)} className="flex items-center space-x-2">
                        <Plus className="h-4 w-4" />
                        <span>Nuevo Agente</span>
                    </Button>
                </div>

                <div className="overflow-x-auto">
                    <table className="min-w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <thead>
                            <tr className="bg-gray-100 dark:bg-gray-900 text-left text-sm font-semibold text-gray-600 dark:text-gray-200">
                                <th className="border-b px-4 py-2">Nombre</th>
                                <th className="border-b px-4 py-2">Email</th>
                                <th className="border-b px-4 py-2">Teléfono</th>
                                <th className="border-b px-4 py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {/* Mostrar agentes */}
                            {agentes.map((agente) => (
                                <tr key={agente.id} className="text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td className="border-b px-4 py-2">{agente.nombre}</td>
                                    <td className="border-b px-4 py-2">{agente.email}</td>
                                    <td className="border-b px-4 py-2">{agente.telefono}</td>
                                    <td className="flex space-x-2 border-b px-4 py-2">
                                        {/* Boton para abrir modal de editar */}
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            onClick={() => handleEdit(agente)}
                                            className="border-blue-600 text-blue-600 hover:text-blue-600 dark:border-blue-400 dark:text-blue-400 dark:bg-gray-800 dark:hover:bg-gray-900"
                                        >
                                            <Edit className="h-4 w-4" />
                                        </Button>

                                        {/* Boton para eliminar */}
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            className="border-red-600 text-red-600 hover:text-red-600 dark:border-red-400 dark:text-red-400 dark:bg-gray-800 dark:hover:bg-gray-900"
                                            onClick={() => {
                                                if (confirm('¿Estás seguro de que deseas eliminar este agente?')) {
                                                    router.delete(route('agentes.destroy', agente.id));
                                                }
                                            }}
                                        >
                                            <Trash2 className="h-4 w-4" />
                                        </Button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                {/* Modal para crear agente */}
                {isCreating && <CrearAgenteModal isOpen={isCreating} onClose={() => setIsCreating(false)} />}
                {/* Modal para editar */}
                {editingAgente && <EditarAgenteModal isOpen={!!editingAgente} onClose={() => setEditingAgente(null)} agente={editingAgente} />}
            </div>
        </AppLayout>
    );
}
