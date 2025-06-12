import { useState } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Compania } from '@/types';
import { Head, usePage } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Teléfonos de Asistencia',
        href: '/telefonos-asistencia',
    },
];


export default function TelefonosAsistencia() {
    const { props } = usePage<{ companias: Compania[] }>();
    const companias = props.companias;

    // Estado para manejar el modal
    const [selectedCompania, setSelectedCompania] = useState<Compania | null>(null);

    // Estado para el término de búsqueda
    const [searchTerm, setSearchTerm] = useState('');

    // Filtrar las compañías según el término de búsqueda
    const filteredCompanias = companias.filter((compania) =>
        compania.nombre.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Teléfonos de Asistencia" />

            {/* Campo de búsqueda */}
            <div className="p-4">
                <input
                    type="text"
                    placeholder="Buscar compañía..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full rounded-lg border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                />
            </div>

            {/* Grid de tarjetas */}
            <div className="grid grid-cols-2 gap-4 p-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                {filteredCompanias.map((compania) => (
                    <div
                        key={compania.id}
                        className="cursor-pointer border rounded-lg p-4 shadow hover:shadow-lg transition bg-white dark:border-gray-700 dark:shadow-lg dark:hover:shadow-gray-700/17"
                        onClick={() => setSelectedCompania(compania)}
                    >
                        <img
                            src={compania.url_logo}
                            alt={`${compania.nombre} logo`}
                            className="h-24 w-auto mx-auto object-contain"
                        />
                    </div>
                ))}
            </div>

            {/* Modal para mostrar los detalles de la compañía, si hay compañia seleccionada renderiza el componente */}
            {selectedCompania && (
                // Controlamos si el modal esta abierto o cerrado. Si esta abierto y hay un cambio reseteamos la compañia
                <Dialog open={!!selectedCompania} onOpenChange={() => setSelectedCompania(null)}>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>{selectedCompania.nombre}</DialogTitle>
                            <DialogDescription>
                                <img
                                    src={selectedCompania.url_logo}
                                    alt={`${selectedCompania.nombre} logo`}
                                    className="h-24 w-auto mx-auto object-contain"
                                />
                            </DialogDescription>
                        </DialogHeader>
                        <div className="mt-4">
                            <ul className="space-y-2">
                                {selectedCompania.telefonos.map((telefono) => (
                                    <li key={telefono.id} className="flex justify-between">
                                        <span className="font-medium">{telefono.telefono}</span>
                                        <span className="text-sm text-gray-500">{telefono.descripcion}</span>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </DialogContent>
                </Dialog>
            )}
        </AppLayout>
    );
}
