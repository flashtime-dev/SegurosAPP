import * as React from "react";
import { Head, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { PolizaCard, Poliza } from '@/components/poliza-card';

/**
 * Página principal que muestra el listado de pólizas
 */
export default function Polizas() {
    const { props } = usePage<{ polizas: Poliza[] }>();
    const polizas = props.polizas;

    return (
        <AppLayout breadcrumbs={[{ title: 'Pólizas', href: '/polizas' }]}>
            <Head title="Pólizas" />

            <div className="container mx-auto px-4 py-6">
                <h1 className="text-2xl font-bold mb-6">Pólizas</h1>

                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {polizas.map((poliza) => (
                        <PolizaCard
                            key={poliza.id}
                            poliza={poliza}
                            onClick={() => window.location.href = `/polizas/${poliza.id}`}
                        />
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}