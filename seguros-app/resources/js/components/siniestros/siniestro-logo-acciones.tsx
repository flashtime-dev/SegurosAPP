import { Button } from "@/components/ui/button";
import { useState } from "react";

interface Props {
    logoUrl: string;
    telefono?: string;
}

export function SiniestroLogoAcciones({ logoUrl, telefono }: Props) {

    return (
        <div className="flex flex-col items-center gap-4">
            <img src={logoUrl} alt="Logo Compañía" className="h-16 object-contain" />
            <p className="text-sm text-muted-foreground">{telefono}</p>
            <div className="w-full space-y-2">
                <Button variant="outline" className="w-full cursor-pointer">
                    Cerrar siniestro
                </Button>
            </div>
        </div>
    );
}
