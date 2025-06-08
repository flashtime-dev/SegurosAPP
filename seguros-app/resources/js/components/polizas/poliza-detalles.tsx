import { Label } from "@/components/ui/label";
import { Poliza } from "@/types";

interface Props {
    label: string;
    value?: string | React.ReactNode;
}

const DetailItem = ({ label, value }: Props) => (
    <div className="space-y-1">
        <Label>{label}</Label>
        <p className="text-sm text-muted-foreground dark:text-gray-400">{value ?? '-'}</p>
    </div>
);

export function PolizaDetalles({ poliza }: { poliza: Poliza }) {
    return (
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 text-sm">
            <DetailItem label="Nº de póliza" value={poliza.numero} />
            <DetailItem label="Forma de pago" value={poliza.forma_pago} />
            <DetailItem label="Cliente" value={poliza.comunidad.nombre} />
            <DetailItem label="Alias del Cliente" value={poliza.alias || '-'} />
            <DetailItem label="Domiciliación" value={poliza.cuenta} />
            <DetailItem label="Dirección del riesgo" value={poliza.comunidad.direccion || '-'} />
            <DetailItem label="Efecto" value={new Date(poliza.fecha_efecto).toLocaleDateString()} />
            <DetailItem label="Vencimiento" value={new Date(new Date(poliza.fecha_efecto).setFullYear(new Date(poliza.fecha_efecto).getFullYear() + 1)).toLocaleDateString()} />
            {poliza.pdf_poliza && (
                <DetailItem
                    label="Poliza PDF"
                    value={<a href={`/polizas/${poliza.id}/pdf`} target="_blank" className="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-500 transition-colors underline">
                        Ver la póliza
                    </a>}
                />
            )}
            {poliza.observaciones && <DetailItem label="Observaciones" value={poliza.observaciones} />}
            <div className="col-span-full">
            </div>
        </div>
    );
}
