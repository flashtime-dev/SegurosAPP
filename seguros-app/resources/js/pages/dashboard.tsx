import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { Building, FileText, AlertCircle } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Panel de Control',
        href: '/dashboard',
    },
];

export default function Dashboard({comunidades, polizas, siniestros}: { comunidades: number, polizas: number, siniestros: number }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Titulo de la pestaña */}
            <Head title="Panel de Control" />

            {/* Contenido del panel de control */}
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div className="flex items-center space-x-3">
                            <div className="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg">
                                <Building className="h-6 w-6 text-blue-600 dark:text-blue-300" />
                            </div>
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Nº de comunidades</p>
                                <h3 className="text-2xl font-semibold text-gray-900 dark:text-white">{comunidades}</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div className="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div className="flex items-center space-x-3">
                            <div className="bg-green-100 dark:bg-green-900 p-2 rounded-lg">
                                <FileText className="h-6 w-6 text-green-600 dark:text-green-300" />
                            </div>
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Pólizas activas</p>
                                <h3 className="text-2xl font-semibold text-gray-900 dark:text-white">{polizas}</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div className="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div className="flex items-center space-x-3">
                            <div className="bg-red-100 dark:bg-red-900 p-2 rounded-lg">
                                <AlertCircle className="h-6 w-6 text-red-600 dark:text-red-300" />
                            </div>
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Siniestros abiertos</p>
                                <h3 className="text-2xl font-semibold text-gray-900 dark:text-white">{siniestros}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                {/* Caja sombra para simular contenido */}
                {/* <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div> */}
            </div>
        </AppLayout>
    );
}
