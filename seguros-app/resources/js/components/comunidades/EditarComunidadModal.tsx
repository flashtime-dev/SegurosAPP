import React, { useEffect } from "react";
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
import { User, Comunidad } from "@/types";
import PhoneInputField from "@/components/PhoneInputField";

// Definición de los props que recibe el componente
type Props = {
    isOpen: boolean; // Controla si el modal está abierto o cerrado
    onClose: () => void; // Función para cerrar el modal
    usuarios: User[]; // Lista de usuarios disponibles para la comunidad
    comunidad: Comunidad; // Datos de la comunidad a editar
};
// Componente para editar los datos de una comunidad existente
export default function EditarComunidadModal({ isOpen, onClose, usuarios, comunidad }: Props) {
    const { data, setData, put, processing, errors, reset } = useForm({
        nombre: '',
        cif: '',
        direccion: '',
        ubi_catastral: '',
        ref_catastral: '',
        telefono: '',
        usuarios: [] as number[], // Lista de IDs de usuarios asociados a la comunidad
    });
    // useEffect para cargar los datos de la comunidad cuando se abre el modal
    useEffect(() => {
        if (comunidad) {
            // Si se recibe una comunidad, se establece el estado con sus datos
            setData({
                nombre: comunidad.nombre || '',
                cif: comunidad.cif || '',
                direccion: comunidad.direccion || '',
                ubi_catastral: comunidad.ubi_catastral || '',
                ref_catastral: comunidad.ref_catastral || '',
                telefono: comunidad.telefono || '',
                usuarios: comunidad.users?.map((u) => u.id) || [], // Asocia los usuarios de la comunidad
            });
        } else {
            // Si no hay comunidad, resetea el formulario
            reset();
        }
    }, [comunidad]); // Se ejecuta cada vez que cambia el prop 'comunidad'
    // Función para manejar el envío del formulario (actualizar la comunidad)
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault(); // Evita la acción predeterminada del formulario (recarga de página)
        // Si hay una comunidad, se actualiza usando el método put
        if (comunidad) {
            put(route('comunidades.update', comunidad.id), {
                onSuccess: () => {
                    reset();
                    onClose();
                },
            });
        }
    };

    return (
        // Modal para editar la comunidad, se controla con el prop 'isOpen'
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Editar Comunidad</DialogTitle>
                    <DialogDescription>Modifica los campos para actualizar la comunidad.</DialogDescription>
                </DialogHeader>

                <ScrollArea className="max-h-[70vh]">
                    {/* Formulario para editar los datos de la comunidad */}
                    {/* Campo de nombre de la comunidad */}
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
                                        // Convierte la primera letra a mayúscula
                                        setData('nombre', value.charAt(0).toUpperCase() + value.slice(1));
                                    }}
                                    disabled={processing}
                                    required
                                    placeholder="Nombre de la comunidad"
                                />
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
                            {/* Campo de dirección */}
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
                                    placeholder="Ubicación catastral"
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

                            {/* Campo para seleccionar y añadir usuarios */}
                            <div>
                                <Label>Usuarios de la Comunidad</Label>
                                <div className="mb-2 flex flex-wrap gap-2">
                                    {/* Muestra los usuarios ya añadidos a la comunidad */}
                                    {data.usuarios.map((userId) => {
                                        const usuario = usuarios.find((u) => u.id === userId);
                                        if (!usuario) return null;
                                        return (
                                            <div key={userId} className="flex items-center gap-2 rounded-lg bg-gray-100 px-3 py-2 dark:bg-gray-700">
                                                <span>{usuario.name}</span>
                                                {/* Botón para eliminar usuario de la lista */}
                                                <Button
                                                    type="button"
                                                    variant="ghost"
                                                    size="sm"
                                                    className="h-auto p-1"
                                                    onClick={() =>
                                                        setData(
                                                            'usuarios',
                                                            data.usuarios.filter((id) => id !== userId),
                                                        )
                                                    }
                                                >
                                                    ✕
                                                </Button>
                                            </div>
                                        );
                                    })}
                                </div>
                                <div className="flex gap-2">
                                    {/* Selector desplegable para agregar usuarios */}
                                    <Select
                                        onValueChange={(value) => {
                                            const id = Number(value);
                                            if (!data.usuarios.includes(id)) {
                                                setData('usuarios', [...data.usuarios, id]);
                                            }
                                        }}
                                        value=""
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Selecciona un usuario para agregar" />
                                        </SelectTrigger>
                                        {/* Muestra los usuarios disponibles que no han sido seleccionados */}
                                        <SelectContent>
                                            {usuarios
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
                                {processing ? 'Guardando...' : 'Guardar cambios'}
                            </Button>
                        </DialogFooter>
                    </form>
                </ScrollArea>
            </DialogContent>
        </Dialog>
    );
}
