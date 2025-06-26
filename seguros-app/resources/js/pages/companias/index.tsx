import CrearCompaniaModal from '@/components/companias/CrearCompaniaModal';
import EditarCompaniaModal from '@/components/companias/EditarCompaniaModal';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardContent, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { Compania, Telefono } from '@/types';
import { Head, router, useForm } from '@inertiajs/react';
import { Edit, Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';

export default function Index({ companias }: { companias: Compania[] }) {
    const [editingCompania, setEditingCompania] = useState<Compania | null>(null);
    const [isCreating, setIsCreating] = useState(false);

    const { data, setData, post, put, processing, errors, reset } = useForm<{
        nombre: string;
        url_logo: string;
        telefonos: { telefono: string; descripcion: string }[];
    }>({
        nombre: '',
        url_logo: '',
        telefonos: [],
    });

    const handleEdit = (compania: Compania) => {
        setEditingCompania(compania);
        setData({
            nombre: compania.nombre,
            url_logo: compania.url_logo,
            telefonos: compania.telefonos,
        });
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Compañías', href: route('companias.index') }]}>
            <Head title="Lista de Compañías" />
            <div className="space-y-6 px-4 py-5">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold text-gray-800 dark:text-gray-100">Lista de Compañías</h1>
                    <Button onClick={() => setIsCreating(true)} className="flex items-center space-x-2">
                        <Plus className="h-4 w-4" />
                        <span>Nueva Compañía</span>
                    </Button>
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {companias.map((compania) => (
                        <Card key={compania.id} className="relative">
                            <CardHeader>
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center space-x-2">
                                        {compania.url_logo && (
                                            <img src={compania.url_logo} alt={compania.nombre} className="h-8 w-8 object-contain rounded-full border" />
                                        )}
                                        <CardTitle className="text-lg">{compania.nombre}</CardTitle>
                                    </div>
                                    <div className="flex space-x-2">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            onClick={() => handleEdit(compania)}
                                            className="border-blue-600 text-blue-600 hover:text-blue-600 dark:border-blue-400 dark:text-blue-400 dark:bg-gray-800 dark:hover:bg-gray-900"
                                        >
                                            <Edit className="h-4 w-4" />
                                        </Button>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            className="border-red-600 text-red-600 hover:text-red-600 dark:border-red-400 dark:text-red-400 dark:bg-gray-800 dark:hover:bg-gray-900"
                                            onClick={() => {
                                                if (confirm('¿Estás seguro de que deseas eliminar esta compañía?')) {
                                                    router.delete(route('companias.destroy', compania.id));
                                                }
                                            }}
                                        >
                                            <Trash2 className="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="mt-4">
                                    <ul className="space-y-2">
                                        {compania.telefonos && compania.telefonos.length > 0 ? (
                                            compania.telefonos.map((tel) => (
                                                <li key={tel.id} className="flex justify-between">
                                                    <span className="font-medium dark:text-gray-400">{tel.descripcion}</span>
                                                    <span className="text-sm text-gray-900 dark:text-gray-200">{tel.telefono}</span>
                                                </li>
                                            ))
                                        ) : (
                                            <span className="text-sm text-gray-500 dark:text-gray-400">Sin teléfonos</span>
                                        )}
                                    </ul>

                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>
                {/* Modal para crear compañía */}
                {isCreating && <CrearCompaniaModal isOpen={isCreating} onClose={() => setIsCreating(false)} />}
                {/* Modal para editar */}
                {editingCompania && <EditarCompaniaModal isOpen={!!editingCompania} onClose={() => setEditingCompania(null)} compania={editingCompania} />}
            </div>
        </AppLayout>
    );
}