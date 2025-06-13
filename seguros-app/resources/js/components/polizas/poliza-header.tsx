import { Badge } from "@/components/ui/badge"; // asumiendo que tienes uno, o lo puedes crear

// Esta función toma un estado de tipo string y devuelve una "variación" o "estilo" basado en ese estado.
function estadoToVariant(estado: string) {
    switch (estado.toLowerCase()) {
        case "en vigor":
            return "enVigor";
        case "vencida":
            return "vencida";
        case "anulada":
            return "anulada";
        case "solicitada":
            return "solicitada";
        case "externa":
            return "externa";
        default:
            return "vencida";
    }
}
// Componente principal que recibe los props 'comunidad' y 'estado'.
export function PolizaHeader({ comunidad, estado }: { comunidad: string; estado: string }) {
    return (
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-2">
            <div>
                <h4 className="text-lg font-semibold">{comunidad}</h4>
            </div>
            <Badge variant={estadoToVariant(estado)}>{estado}</Badge>
        </div>
        
    );
}
