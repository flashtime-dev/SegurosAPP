import React from "react";
import { useForm } from "@inertiajs/react";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
    DialogClose,
    DialogDescription,
} from "@/components/ui/dialog";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { User } from "@/types";
import PhoneInputField from "@/components/PhoneInputField";

// Definición de los props que recibe el componente
type Props = {
    isOpen: boolean; // Indica si el modal está abierto
    onClose: () => void; // Función para cerrar el modal
    usuarios: User[]; // Lista de usuarios disponibles para asignar a la comunidad
};

// Componente principal para la creación de una nueva comunidad
export default function CrearComunidadModal({ isOpen, onClose, usuarios }: Props) {
    // Hook para manejar el estado del formulario (datos, errores, etc.)
    const { data, setData, post, processing, errors, reset } = useForm({
        nombre: '',
        cif: '',
        direccion: '',
        ubi_catastral: '',
        ref_catastral: '',
        telefono: '',
        usuarios: [] as number[],
    });

    // Función para agregar un usuario a la lista de usuarios seleccionados
    const agregarUsuario = (userId: string) => {
        const id = Number(userId); // Convierte el ID del usuario a número
        if (!data.usuarios.includes(id)) {
            // Evita duplicados
            setData('usuarios', [...data.usuarios, id]); // Agrega el ID del usuario
        }
    };
    // Función para eliminar un usuario de la lista de usuarios seleccionados
    const eliminarUsuario = (userId: number) => {
        setData(
            'usuarios',
            data.usuarios.filter((id) => id !== userId),
        );
    };
    // Función para manejar el envío del formulario
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        // Envia los datos del formulario al servidor
        post(route('comunidades.store'), {
            onSuccess: () => {
                reset(); // Limpia el formulario despues del envio
                onClose(); // Cierra el modal despues del envio
            },
        });
    };

    return (
        // Envia los datos del formulario al servidor
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Nueva Comunidad</DialogTitle>
                    <DialogDescription>Completa los campos para crear una nueva comunidad.</DialogDescription>
                </DialogHeader>

                <ScrollArea className="max-h-[70vh]">
                    {/* Formulario de creación de comunidad */}
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="grid gap-4">
                            {/* Campo de nombre de la comunidad */}
                            <div>
                                <Label htmlFor="nombre">Nombre *</Label>
                                <Input
                                    id="nombre"
                                    value={data.nombre}
                                    onChange={(e) => {
                                        const value = e.target.value;
                                        setData('nombre', value.charAt(0).toUpperCase() + value.slice(1));
                                    }}
                                    disabled={processing}
                                    required
                                    placeholder="Nombre de la comunidad"
                                />
                                {/* Muestra los errores si los hay */}
                                <InputError message={errors.nombre} className="mt-2" />
                            </div>
                            {/* Campo de CIF (Código de Identificación Fiscal) */}
                            <div>
                                <Label htmlFor="cif">CIF *</Label>
                                <Input
                                    id="cif"
                                    value={data.cif}
                                    onChange={(e) => setData('cif', e.target.value.toUpperCase())}
                                    disabled={processing}
                                    required
                                    placeholder="H12345678"
                                    title="El CIF debe comenzar con una letra (ABCDEFGHJKLMNPQRSUVW) y seguir con 8 dígitos."
                                />
                                <InputError message={errors.cif} className="mt-2" />
                            </div>

                            {/* Campo de dirección de la comunidad */}
                            <div>
                                <Label htmlFor="direccion">Dirección</Label>
                                <Input
                                    id="direccion"
                                    value={data.direccion}
                                    onChange={(e) => {
                                        const value = e.target.value;
                                        setData('direccion', value.charAt(0).toUpperCase() + value.slice(1));
                                    }}
                                    disabled={processing}
                                    placeholder="Dirección de la comunidad"
                                />
                                <InputError message={errors.direccion} className="mt-2" />
                            </div>
                            {/* Campo de ubicación catastral */}
                            <div>
                                <Label htmlFor="ubi_catastral">Ubicación Catastral</Label>
                                <Input
                                    id="ubi_catastral"
                                    value={data.ubi_catastral}
                                    onChange={(e) => {
                                        const value = e.target.value;
                                        setData('ubi_catastral', value.charAt(0).toUpperCase() + value.slice(1));
                                    }}
                                    disabled={processing}
                                    placeholder="Ubicación Catastral"
                                />
                                <InputError message={errors.ubi_catastral} className="mt-2" />
                            </div>
                            {/* Campo de referencia catastral */}
                            <div>
                                <Label htmlFor="ref_catastral">Referencia Catastral</Label>
                                <Input
                                    id="ref_catastral"
                                    value={data.ref_catastral}
                                    onChange={(e) => setData('ref_catastral', e.target.value.toUpperCase())}
                                    disabled={processing}
                                    placeholder="1234ABCD5678EFGH9012"
                                    minLength={14}
                                    maxLength={20}
                                />
                                <InputError message={errors.ref_catastral} className="mt-2" />
                            </div>
                            {/* Campo de teléfono */}
                            <div>
                                <Label htmlFor="telefono">Teléfono</Label>
                                <PhoneInputField
                                    value={data.telefono}
                                    onChange={(value) => {
                                        if (!value) {
                                            setData('telefono', '');
                                            return;
                                        }
                                        const cleaned = value.replace(/\s/g, '');
                                        const normalized = cleaned.startsWith('+') ? cleaned : `+${cleaned}`;
                                        setData('telefono', normalized);
                                    }}
                                    error={errors.telefono}
                                />
                            </div>

                            {/* Campo para seleccionar y añadir usuarios a la comunidad */}
                            <div>
                                <Label>Usuarios de la Comunidad</Label>
                                <div className="mb-2 flex flex-wrap gap-2">
                                    {/* Muestra los usuarios seleccionados */}
                                    {data.usuarios.map((userId) => {
                                        const usuario = usuarios.find((u) => u.id === userId);
                                        if (!usuario) return null;
                                        return (
                                            <div key={userId} className="flex items-center gap-2 rounded-lg bg-gray-100 px-3 py-2 dark:bg-gray-700">
                                                <span>{usuario.name}</span>
                                                <Button
                                                    type="button"
                                                    variant="ghost"
                                                    size="sm"
                                                    className="h-auto p-1"
                                                    onClick={() => eliminarUsuario(userId)}
                                                >
                                                    ✕
                                                </Button>
                                            </div>
                                        );
                                    })}
                                </div>
                                <div className="flex gap-2">
                                    {/* Selector desplegable para agregar usuarios */}
                                    <Select onValueChange={(value) => agregarUsuario(value)} value="">
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Selecciona un usuario para agregar" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {usuarios
                                                // Filtra los usuarios ya seleccionados y no los muestra
                                                .filter((u) => !data.usuarios.includes(u.id))
                                                .map((usuario) => (
                                                    <SelectItem className="hover:bg-gray-100" key={usuario.id} value={String(usuario.id)}>
                                                        {usuario.name} ({usuario.email})
                                                    </SelectItem>
                                                ))}
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                        </div>
                        {/* Botones de acción al final del modal */}
                        <DialogFooter>
                            <DialogClose asChild>
                                <Button type="button" variant="outline" onClick={onClose}>
                                    Cancelar
                                </Button>
                            </DialogClose>
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Creando...' : 'Crear'} {/* Texto que cambia dependiendo del estado de procesamiento */}
                            </Button>
                        </DialogFooter>
                    </form>
                </ScrollArea>
            </DialogContent>
        </Dialog>
    );
}
