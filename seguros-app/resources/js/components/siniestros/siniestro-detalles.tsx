import { Label } from "@/components/ui/label";
import { Siniestro } from "@/types";
import { isValid, parseISO, format } from "date-fns";
import { es } from "date-fns/locale";
import { FileIcon } from "lucide-react";

interface Props {
    label: string;
    value?: string | React.ReactNode;
}

const DetailItem = ({ label, value }: Props) => (
    <div className="space-y-1">
        <Label>{label}</Label>
        <div className="text-sm text-muted-foreground dark:text-gray-400">{value || '-'}</div>
    </div>
);

function safeFormatDate(fecha?: string | null) {
    if (!fecha) return "-";
    const parsed = parseISO(fecha);
    if (!isValid(parsed)) return "-";
    return format(parsed, "d 'de' MMMM 'de' yyyy", { locale: es });
}

export function SiniestroDetalles({ siniestro }: { siniestro: Siniestro }) {
    return (
        <div className="space-y-6">
            {/* Información principal */}
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 text-sm dark:text-gray-100">
                <DetailItem
                    label="Nº Expediente"
                    value={
                        <p className="flex items-center gap-2">
                            {siniestro.expediente}
                        </p>
                    }
                />
                <DetailItem
                    label="Nº Póliza"
                    value={siniestro.poliza.numero}
                />
                <DetailItem
                    label="Fecha de Ocurrencia"
                    value={safeFormatDate(siniestro.fecha_ocurrencia)}
                />
                <DetailItem
                    label="Tramitador"
                    value={siniestro.tramitador}
                />
                <DetailItem
                    label="Expediente CIA"
                    value={siniestro.exp_cia}
                />
                <DetailItem
                    label="Expediente Asistencia"
                    value={siniestro.exp_asist}
                />
            </div>

            {/* Declaración */}
            <div className="space-y-2 dark:text-gray-100">
                <Label>Declaración</Label>
                <div className="p-4 bg-muted rounded-lg text-sm whitespace-pre-wrap dark:bg-gray-600">
                    {siniestro.declaracion}
                </div>
            </div>

            {/* Documentación */}
            {siniestro.adjuntos && siniestro.adjuntos.length > 0 && (
                <div className="space-y-2">
                    <Label>Documentación</Label>
                    <ul className="space-y-1">
                        {siniestro.adjuntos.map((archivo, index) => (
                            <li key={index}>
                                <a
                                    href={`/siniestros/${siniestro.id}/archivo/${archivo.nombre}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="flex items-center gap-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-500 transition-colors"
                                >
                                    <FileIcon className="h-4 w-4" />
                                    <span>{archivo.nombre || 'Documento adjunto'}</span>
                                </a>
                            </li>
                        ))}
                    </ul>
                </div>
            )}

        </div>
    );
}
