import { Label } from "@/components/ui/label";
import { Poliza } from "@/types";

interface Props {
    label: string;
    value?: string | React.ReactNode;
}

// Función para formatear la cuenta bancaria (IBAN)
const formatearCuenta = (cuenta?: string): string => {
    if (!cuenta) return '-';
    // Limpiar espacios y poner mayúsculas
    const clean = cuenta.toUpperCase().replace(/\s+/g, '');
    // Formatear con espacios cada 4 caracteres
    return clean.replace(/(.{4})/g, '$1 ').trim();
}

// Componente para mostrar un ítem de detalle con su etiqueta y valor.
const DetailItem = ({ label, value }: Props) => (
    <div className="space-y-1">
        <Label>{label}</Label>
        <p className="text-sm text-muted-foreground dark:text-gray-400">{value ?? '-'}</p>
    </div>
);
// Componente principal que muestra los detalles de una póliza.
export function PolizaDetalles({ poliza }: { poliza: Poliza }) {
    return (
        <div className="grid grid-cols-1 gap-8 text-sm sm:grid-cols-2 md:grid-cols-3">
            {/* Llamada al componente DetailItem para cada uno de los detalles de la póliza */}
            <DetailItem label="Nº de póliza" value={poliza.numero} />
            <DetailItem label="Forma de pago" value={poliza.forma_pago} />
            <DetailItem label="Cliente" value={poliza.comunidad.nombre} />
            <DetailItem label="Alias del Cliente" value={poliza.alias || '-'} />
            <DetailItem label="Domiciliación" value={formatearCuenta(poliza.cuenta)} />
            <DetailItem label="Dirección del riesgo" value={poliza.comunidad.direccion || '-'} />
            <DetailItem label="Efecto" value={new Date(poliza.fecha_efecto).toLocaleDateString()} />
            {/* Calcula la fecha de vencimiento, que es un año después de la fecha de efecto */}
            <DetailItem
                label="Vencimiento"
                value={new Date(new Date(poliza.fecha_efecto).setFullYear(new Date(poliza.fecha_efecto).getFullYear() + 1)).toLocaleDateString()}
            />
            {/* Si la póliza tiene un archivo PDF asociado, se muestra un enlace para visualizar el PDF */}
            {poliza.pdf_poliza && (
                <DetailItem
                    label="Poliza PDF"
                    value={
                        <a
                            href={`/polizas/${poliza.id}/pdf`}
                            target="_blank"
                            className="text-blue-600 underline transition-colors hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-500"
                        >
                            Ver la póliza
                        </a>
                    }
                />
            )}
            {/* Si existen observaciones, se muestra un campo con la información */}
            {poliza.observaciones && <DetailItem label="Observaciones" value={poliza.observaciones} />}
            <div className="col-span-full"></div>
        </div>
    );
}
