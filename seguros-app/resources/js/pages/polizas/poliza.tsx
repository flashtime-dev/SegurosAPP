import AppLayout from '@/layouts/app-layout';
import { Head, usePage, router } from '@inertiajs/react';

interface Poliza {
    id: number;
    numero: string;
    estado: string;
    fecha_efecto: string;
    cuenta: string;
    forma_pago: string;
    prima_neta: number;
    prima_total: number;
    pdf_poliza: string | null;
    observaciones: string | null;
    compania: {
        nombre: string;
        url_logo: string;
    };
    comunidad: {
        nombre: string;
        direccion: string;
    };
}

interface Chat {
    id: number;
    mensaje: string;
    created_at: string;
    usuario: { name: string };
}

export default function Poliza() {
    const { props } = usePage<{ poliza: Poliza; chats: Chat[] }>();
    const poliza = props.poliza;
    
    return (
        <AppLayout breadcrumbs={[{ title: 'Pólizas', href: '/polizas' }, { title: poliza.numero, href: '#' }]}>
            <Head title={`Detalles de la Póliza ${poliza.numero}`} />

            <div className="p-4">
                <div className="border rounded-lg p-4 shadow">
                    <img
                        src={poliza.compania.url_logo}
                        alt={`${poliza.compania.nombre} logo`}
                        className="h-16 w-auto mx-auto object-contain"
                    />
                    <h2 className="mt-4 text-lg font-bold text-center">{poliza.comunidad.nombre}</h2>
                    <p className="text-sm text-gray-500 text-center">{poliza.comunidad.direccion}</p>

                    <div className="mt-4 space-y-2">
                        <p>
                            <strong>Número:</strong> {poliza.numero}
                        </p>
                        <p>
                            <strong>Estado:</strong> {poliza.estado}
                        </p>
                        <p>
                            <strong>Fecha de Efecto:</strong> {new Date(poliza.fecha_efecto).toLocaleDateString()}
                        </p>
                        <p>
                            <strong>Cuenta:</strong> {poliza.cuenta || 'No disponible'}
                        </p>
                        <p>
                            <strong>Forma de Pago:</strong> {poliza.forma_pago}
                        </p>
                        <p>
                            <strong>Prima Neta:</strong> {poliza.prima_neta ? poliza.prima_neta.toFixed(2) : '0.00'} €
                        </p>
                        <p>
                            <strong>Prima Total:</strong> {poliza.prima_total ? poliza.prima_total.toFixed(2) : '0.00'} €
                        </p>
                        {poliza.pdf_poliza && (
                            <p>
                                <strong>PDF:</strong>{' '}
                                <a href={poliza.pdf_poliza} target="_blank" className="text-blue-500 underline">
                                    Ver PDF
                                </a>
                            </p>
                        )}
                        {poliza.observaciones && (
                            <p>
                                <strong>Observaciones:</strong> {poliza.observaciones}
                            </p>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}