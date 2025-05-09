import { Card, CardHeader, CardContent, CardTitle, CardDescription } from "@/components/ui/card";
import { Comunidad } from "@/types";
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from "@/components/ui/dropdown-menu";
import { Edit, EllipsisVertical, Trash2 } from "lucide-react";
import { router } from "@inertiajs/react";
import { Link } from "@inertiajs/react";



export function ComunidadCard({ comunidad }: { comunidad: Comunidad }) {
    return (
        <Card className="w-full relative" key={comunidad.id}>
            <DropdownMenu>
                <DropdownMenuTrigger className="absolute top-2 right-2">
                    <EllipsisVertical className="w-5 h-5 text-gray-500 hover:text-gray-700" />
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="z-10">
                    <DropdownMenuItem onClick={() => { router.visit(route('comunidades.edit', comunidad.id)); }}>
                        <Edit className="w-4 h-4 mr-1 inline" />
                        Editar
                    </DropdownMenuItem>
                    <DropdownMenuItem onClick={() => {
                        if (confirm('¿Estás seguro de que deseas eliminar esta comunidad?')) {
                            router.delete(route('comunidades.destroy', comunidad.id));
                        }
                    }}>
                        <span className="text-red-600">
                            <Trash2 className="text-red-600 w-4 h-4 mr-1 inline" />
                            Eliminar
                        </span>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            <Link href={`/comunidades/${comunidad.id}/edit`}>
                <CardHeader className="flex-row items-center justify-between">
                    <div>
                        <CardTitle>{comunidad.nombre}</CardTitle>
                        <CardDescription>{comunidad.cif}</CardDescription>
                    </div>
                </CardHeader>
                <CardContent>
                    {/* Contenido de la tarjeta */}
                    <p className="text-sm text-gray-500">{comunidad.direccion}</p>
                </CardContent>
            </Link>
        </Card>
    );
};