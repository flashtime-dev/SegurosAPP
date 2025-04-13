import AppLayout from '@/layouts/app-layout';
import { Head, usePage } from '@inertiajs/react';

interface Poliza {
    id: number;
    numero: string;
    estado: string;
    fecha_efecto: string;
    compania: {
        nombre: string;
        url_logo: string;
    };
    comunidad: {
        nombre: string;
        direccion: string;
    };
}

export default function Polizas() {
    const { props } = usePage<{ polizas: Poliza[] }>();
    const polizas = props.polizas;

    return (
        <AppLayout breadcrumbs={[{ title: 'Pólizas', href: '/polizas' }]}>
            <Head title="Pólizas" />

            <div className="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                {polizas.map((poliza) => (
                    <div
                        key={poliza.id}
                        className="cursor-pointer border rounded-lg p-4 shadow hover:shadow-lg transition"
                        onClick={() => window.location.href = `/polizas/${poliza.id}`}
                    >
                        <img
                            src={poliza.compania.url_logo}
                            alt={`${poliza.compania.nombre} logo`}
                            className="h-16 w-auto mx-auto object-contain"
                        />
                        <h2 className="mt-2 text-lg font-bold text-center">{poliza.comunidad.nombre}</h2>
                        <p className="text-sm text-gray-500 text-center">{poliza.comunidad.direccion}</p>
                        <p className="mt-2 text-sm text-gray-700">
                            <strong>Número:</strong> {poliza.numero}
                        </p>
                        <p className="text-sm text-gray-700">
                            <strong>Estado:</strong> {poliza.estado}
                        </p>
                        <p className="text-sm text-gray-700">
                            <strong>Fecha:</strong> {new Date(poliza.fecha_efecto).toLocaleDateString()}
                        </p>
                    </div>
                ))}
            </div>
        </AppLayout>
    );
}