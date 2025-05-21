import { Button } from "@/components/ui/button";

export function PolizaLogoAcciones({ logoUrl, telefono }: { logoUrl: string; telefono: string }) {
    return (
        <div className="flex flex-col items-center gap-4">
            <img src={logoUrl} alt="Logo Compañía" className="h-16 object-contain" />
            <p className="text-sm text-muted-foreground">{telefono}</p>
            <div className="w-full space-y-2">
                <Button variant="outline" className="w-full">⚡ Dar parte</Button>
                <Button variant="outline" className="w-full">✉️ Carta anulación</Button>
            </div>
        </div>
    );
}
