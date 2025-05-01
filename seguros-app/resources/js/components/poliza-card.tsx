import * as React from "react";
import { Card, CardHeader, CardContent, CardTitle, CardDescription } from "@/components/ui/card";
import { cn } from "@/lib/utils";

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

type PolizaCardProps = React.ComponentProps<typeof Card> & {
    poliza: Poliza;
    onClick?: () => void;
};

/**
 * Componente que muestra la información de una póliza de seguro en formato de tarjeta
 */
function PolizaCard({
    className,
    poliza,
    onClick,
    ...props
}: PolizaCardProps) {
    const getEstadoColor = () => {
        switch (poliza.estado.toLowerCase()) {
            case 'en vigor':
                return 'bg-green-600';
            case 'vencida':
                return 'bg-gray-500';
            case 'anulada':
                return 'bg-red-500';
            case 'solicitada':
                return 'bg-yellow-500';
            case 'externa':
                return 'bg-blue-500';
            default:
                return 'bg-gray-500';
        }
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString();
    };

    return (
        <div
            className={cn(
                "bg-white border border-gray-200 rounded-lg shadow transition-transform duration-200 hover:shadow-lg hover:-translate-y-1 cursor-pointer p-4",
                className
            )}
            onClick={onClick}
            {...props}
        >
            <img
                src={poliza.compania.url_logo}
                alt={`${poliza.compania.nombre} logo`}
                className="mx-auto h-16 w-auto object-contain mb-4"
            />

            <div className="flex justify-between items-center mb-2">
                <h5 className="text-lg font-bold text-gray-800 leading-tight">
                    {poliza.comunidad.nombre}
                </h5>
                <span
                    className={cn(
                        "text-white text-xs font-medium px-3 py-1 rounded-full",
                        getEstadoColor()
                    )}
                >
                    {poliza.estado}
                </span>
            </div>

            <p className="text-gray-500 text-sm mb-2">
                {poliza.comunidad.direccion}
            </p>

            <p className="text-sm text-gray-600 font-medium">
                Póliza {poliza.numero}
                <span className="float-right">{formatDate(poliza.fecha_efecto)}</span>
            </p>
        </div>
    );
}


export { PolizaCard };
export type { Poliza };