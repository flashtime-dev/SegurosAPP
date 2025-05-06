import { AlertDialog, AlertDialogContent, AlertDialogHeader, AlertDialogFooter, AlertDialogTitle, AlertDialogDescription, AlertDialogCancel, AlertDialogAction, AlertDialogTrigger } from '@/components/ui/alert-dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { router } from '@inertiajs/react';

export function DeleteAlert({ id, ruta, titulo, descripcion }: { id: number, ruta: string, titulo: string, descripcion: string }) {
    const handleDelete = () => {
        router.delete(route(ruta, id));
    };

    return (
        <AlertDialog>
            <AlertDialogTrigger asChild>
                <DropdownMenuItem className="text-red-600">
                    Borrar
                </DropdownMenuItem>
            </AlertDialogTrigger>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>{titulo}</AlertDialogTitle>
                    <AlertDialogDescription>
                        {descripcion}
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancelar</AlertDialogCancel>
                    <AlertDialogAction onClick={handleDelete} className="bg-red-600 hover:bg-red-700">
                        Borrar
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
}
