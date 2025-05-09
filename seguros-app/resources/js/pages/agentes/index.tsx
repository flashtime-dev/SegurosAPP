import { Head, Link, router, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { Edit, Trash2, Plus } from 'lucide-react';
import { useState } from 'react';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
    DialogClose,
} from '@/components/ui/dialog';

type Agente = {
    id: number;
    nombre: string;
    email: string;
    telefono: string;
};

type Props = {
    agentes: Agente[];
};

export default function Index({ agentes }: Props) {
    const [editingAgente, setEditingAgente] = useState<Agente | null>(null);
    const [isCreating, setIsCreating] = useState(false); // Estado para el modal de creación
    const { data, setData, post, put, processing, errors, reset } = useForm({
        nombre: '',
        email: '',
        telefono: '',
    });

    const handleEdit = (agente: Agente) => {
        setEditingAgente(agente);
        setData({
            nombre: agente.nombre,
            email: agente.email,
            telefono: agente.telefono,
        });
    };

    const handleCancelEdit = () => {
        setEditingAgente(null);
    };

    const handleCancelCreate = () => {
        setIsCreating(false);
        reset(); // Limpia los datos del formulario
    };

    const handleSubmitEdit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editingAgente) {
            put(route('agentes.update', editingAgente.id), {
                onSuccess: () => setEditingAgente(null),
            });
        }
    };

    const handleSubmitCreate = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('agentes.store'), {
            onSuccess: () => setIsCreating(false),
        });
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Agentes', href: route('agentes.index') }]}>
            <Head title="Lista de Agentes" />

            <div className="max-w-6xl mx-auto p-4 space-y-6">
                <div className="flex justify-between items-center">
                    <h1 className="text-2xl font-bold text-gray-800">Lista de Agentes</h1>
                    <Button onClick={() => setIsCreating(true)} className="flex items-center space-x-2">
                        <Plus className="w-4 h-4" />
                        <span>Nuevo Agente</span>
                    </Button>
                </div>

                <div className="overflow-x-auto">
                    <table className="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead>
                            <tr className="bg-gray-100 text-left text-sm font-semibold text-gray-600">
                                <th className="px-4 py-2 border-b">Nombre</th>
                                <th className="px-4 py-2 border-b">Email</th>
                                <th className="px-4 py-2 border-b">Teléfono</th>
                                <th className="px-4 py-2 border-b">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {agentes.map((agente) => (
                                <tr key={agente.id} className="text-sm text-gray-700 hover:bg-gray-50">
                                    <td className="px-4 py-2 border-b">{agente.nombre}</td>
                                    <td className="px-4 py-2 border-b">{agente.email}</td>
                                    <td className="px-4 py-2 border-b">{agente.telefono}</td>
                                    <td className="px-4 py-2 border-b flex space-x-2">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            onClick={() => handleEdit(agente)}
                                            className="text-blue-600 border-blue-600"
                                        >
                                            <Edit className="w-4 h-4" />
                                        </Button>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            className="text-red-600 border-red-600"
                                            onClick={() => {
                                                if (confirm('¿Estás seguro de que deseas eliminar este agente?')) {
                                                    router.delete(route('agentes.destroy', agente.id));
                                                }
                                            }}
                                        >
                                            <Trash2 className="w-4 h-4" />
                                        </Button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {/* Modal para editar agente */}
                {editingAgente && (
                    <Dialog open={!!editingAgente} onOpenChange={handleCancelEdit}>
                        <DialogContent className="sm:max-w-lg">
                            <DialogHeader>
                                <DialogTitle>Editar Agente</DialogTitle>
                            </DialogHeader>

                            <form onSubmit={handleSubmitEdit} className="space-y-4">
                                <div>
                                    <Label htmlFor="nombre">Nombre</Label>
                                    <Input
                                        id="nombre"
                                        value={data.nombre}
                                        onChange={(e) => setData('nombre', e.target.value)}
                                        disabled={processing}
                                        required
                                    />
                                    <InputError message={errors.nombre} className="mt-2" />
                                </div>

                                <div>
                                    <Label htmlFor="email">Email</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        disabled={processing}
                                        required
                                    />
                                    <InputError message={errors.email} className="mt-2" />
                                </div>

                                <div>
                                    <Label htmlFor="telefono">Teléfono</Label>
                                    <Input
                                        id="telefono"
                                        value={data.telefono}
                                        onChange={(e) => setData('telefono', e.target.value)}
                                        disabled={processing}
                                        required
                                    />
                                    <InputError message={errors.telefono} className="mt-2" />
                                </div>

                                <DialogFooter>
                                    <DialogClose asChild>
                                        <Button type="button" variant="outline">
                                            Cancelar
                                        </Button>
                                    </DialogClose>
                                    <Button type="submit" disabled={processing}>
                                        {processing ? 'Guardando...' : 'Guardar'}
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                )}

                {/* Modal para crear agente */}
                {isCreating && (
                    <Dialog open={isCreating} onOpenChange={handleCancelCreate}>
                        <DialogContent className="sm:max-w-lg">
                            <DialogHeader>
                                <DialogTitle>Nuevo Agente</DialogTitle>
                            </DialogHeader>

                            <form onSubmit={handleSubmitCreate} className="space-y-4">
                                <div>
                                    <Label htmlFor="nombre">Nombre</Label>
                                    <Input
                                        id="nombre"
                                        value={data.nombre}
                                        onChange={(e) => setData('nombre', e.target.value)}
                                        disabled={processing}
                                        required
                                    />
                                    <InputError message={errors.nombre} className="mt-2" />
                                </div>

                                <div>
                                    <Label htmlFor="email">Email</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        disabled={processing}
                                        required
                                    />
                                    <InputError message={errors.email} className="mt-2" />
                                </div>

                                <div>
                                    <Label htmlFor="telefono">Teléfono</Label>
                                    <Input
                                        id="telefono"
                                        value={data.telefono}
                                        onChange={(e) => setData('telefono', e.target.value)}
                                        disabled={processing}
                                        required
                                    />
                                    <InputError message={errors.telefono} className="mt-2" />
                                </div>

                                <DialogFooter>
                                    <DialogClose asChild>
                                        <Button type="button" variant="outline">
                                            Cancelar
                                        </Button>
                                    </DialogClose>
                                    <Button type="submit" disabled={processing}>
                                        {processing ? 'Guardando...' : 'Guardar'}
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                )}
            </div>
        </AppLayout>
    );
}