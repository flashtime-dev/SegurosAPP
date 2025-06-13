import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from "@/components/ui/alert-dialog";

import { router } from "@inertiajs/react";
// Definición de las propiedades que recibe el componente ConfirmarAnulacionModal
interface Props {
    isOpen: boolean;
    onClose: () => void;
    polizaId: number;
    numeroPoliza: string;
}
// Componente que muestra el modal de confirmación de anulación de póliza
export function ConfirmarAnulacionModal({ isOpen, onClose, polizaId, numeroPoliza }: Props) {
    // Función que maneja la confirmación de la anulación
    const handleConfirm = () => {
        // Enviamos la solicitud al backend para enviar el email
        router.post(
            route('polizas.solicitar-anulacion', polizaId),
            {},
            {
                onSuccess: () => {
                    onClose();
                    // Aquí podrías mostrar un mensaje de éxito si lo deseas
                    //console.log("Solicitud de anulación enviada para la póliza:", polizaId);
                    //alert(`Solicitud de anulación enviada para la póliza número ${numeroPoliza}.`);
                },
                onError: (error) => {
                    onClose();
                    // Aquí podrías mostrar un mensaje de error al usuario
                    //console.error("Error al enviar la solicitud de anulación:", error);
                    //alert("Error al enviar la solicitud de anulación. Por favor, inténtalo de nuevo más tarde.");
                },
            },
        );
        //console.log("Solicitud de anulación enviada para la póliza:", polizaId);
    };

    return (
        // Componente de diálogo de alerta (modal)
        <AlertDialog open={isOpen} onOpenChange={onClose}>
            <AlertDialogContent>
                {/* Encabezado del modal */}
                <AlertDialogHeader>
                    <AlertDialogTitle>¿Confirmar solicitud de anulación?</AlertDialogTitle>
                    {/* Descripción del modal */}
                    <AlertDialogDescription>
                        Se enviará un email solicitando la cancelación de la póliza número {numeroPoliza}. Esta acción no se puede deshacer.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                {/* Pie del modal (con los botones) */}
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancelar</AlertDialogCancel>
                    <AlertDialogAction onClick={handleConfirm} className="bg-red-600 text-white hover:bg-red-700">
                        Confirmar anulación
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
}
