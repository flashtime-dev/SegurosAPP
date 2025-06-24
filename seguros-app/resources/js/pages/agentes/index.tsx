import CrearAgenteModal from '@/components/agentes/CrearAgenteModal';
import EditarAgenteModal from '@/components/agentes/EditarAgenteModal';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardContent, CardTitle, CardDescription } from '@/components/ui/card';
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

            <div className="space-y-6 px-4 py-5">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold text-gray-800 dark:text-gray-100">Lista de Agentes</h1>
                    <Button onClick={() => setIsCreating(true)} className="flex items-center space-x-2">
                        <Plus className="h-4 w-4" />
                        <span>Nuevo Agente</span>
                    </Button>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {agentes.map((agente) => (
                        <Card key={agente.id} className="relative">
                            <CardHeader>
                                <div className="flex items-center justify-between">
                                    <CardTitle className="text-lg">{agente.nombre}</CardTitle>
                                    <div className="flex space-x-2">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            onClick={() => handleEdit(agente)}
                                            className="border-blue-600 text-blue-600 hover:text-blue-600 dark:border-blue-400 dark:text-blue-400 dark:bg-gray-800 dark:hover:bg-gray-900"
                                        >
                                            <Edit className="h-4 w-4" />
                                        </Button>
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
                                    </div>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <dl className="grid gap-2 text-sm">
                                    <div className="flex justify-between items-center">
                                        <dt className="font-medium text-gray-500 dark:text-gray-400">Email:</dt>
                                        <dd className="text-gray-900 dark:text-gray-200">{agente.email}</dd>
                                    </div>
                                    <div className="flex justify-between items-center">
                                        <dt className="font-medium text-gray-500 dark:text-gray-400">Teléfono:</dt>
                                        <dd className="text-gray-900 dark:text-gray-200">{agente.telefono}</dd>
                                    </div>
                                </dl>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {/* Modal para crear agente */}
                {isCreating && <CrearAgenteModal isOpen={isCreating} onClose={() => setIsCreating(false)} />}
                {/* Modal para editar */}
                {editingAgente && <EditarAgenteModal isOpen={!!editingAgente} onClose={() => setEditingAgente(null)} agente={editingAgente} />}
            </div>
        </AppLayout>
    );
}
