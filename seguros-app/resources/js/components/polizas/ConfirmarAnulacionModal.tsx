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

interface Props {
    isOpen: boolean;
    onClose: () => void;
    polizaId: number;
    numeroPoliza: string;
}

export function ConfirmarAnulacionModal({ isOpen, onClose, polizaId, numeroPoliza }: Props) {
    const handleConfirm = () => {
        // Enviamos la solicitud al backend para enviar el email
        router.post(route("polizas.solicitar-anulacion", polizaId), {}, {
            onSuccess: () => {
                onClose();
                // Aquí podrías mostrar un mensaje de éxito si lo deseas
                console.log("Solicitud de anulación enviada para la póliza:", polizaId);
                alert(`Solicitud de anulación enviada para la póliza número ${numeroPoliza}.`);
            },
            onError: (error) => {
                onClose();
                // Aquí podrías mostrar un mensaje de error al usuario
                console.error("Error al enviar la solicitud de anulación:", error);
                alert("Error al enviar la solicitud de anulación. Por favor, inténtalo de nuevo más tarde.");
            }
        });
        //console.log("Solicitud de anulación enviada para la póliza:", polizaId);
    };

    return (
        <AlertDialog open={isOpen} onOpenChange={onClose}>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>¿Confirmar solicitud de anulación?</AlertDialogTitle>
                    <AlertDialogDescription>
                        Se enviará un email solicitando la cancelación de la póliza número {numeroPoliza}. 
                        Esta acción no se puede deshacer.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancelar</AlertDialogCancel>
                    <AlertDialogAction
                        onClick={handleConfirm}
                        className="bg-red-600 hover:bg-red-700 text-white"
                    >
                        Confirmar anulación
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
}
