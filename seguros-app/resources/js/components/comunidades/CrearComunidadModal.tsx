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

type Props = {
    isOpen: boolean;
    onClose: () => void;
    usuarios: User[];
};

export default function CrearComunidadModal({ isOpen, onClose, usuarios }: Props) {
    const { data, setData, post, processing, errors, reset } = useForm({
        nombre: '',
        cif: '',
        direccion: '',
        ubi_catastral: '',
        ref_catastral: '',
        telefono: '',
        usuarios: [] as number[],
    });

    const agregarUsuario = (userId: string) => {
        const id = Number(userId);
        if (!data.usuarios.includes(id)) {
            setData('usuarios', [...data.usuarios, id]);
        }
    };

    const eliminarUsuario = (userId: number) => {
        setData('usuarios', data.usuarios.filter(id => id !== userId));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route("comunidades.store"), {
            onSuccess: () => {
                reset(); // Limpia el formulario
                onClose(); // Cierra el modal
            },
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Nueva Comunidad</DialogTitle>
                    <DialogDescription>
                        Completa los campos para crear una nueva comunidad.
                    </DialogDescription>
                </DialogHeader>

                <ScrollArea className="max-h-[70vh]">
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="grid gap-4">
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
                                <InputError message={errors.nombre} className="mt-2" />
                            </div>

                            <div>
                                <Label htmlFor="cif">CIF *</Label>
                                <Input
                                    id="cif"
                                    value={data.cif}
                                    onChange={(e) => setData('cif', e.target.value.toUpperCase())}
                                    disabled={processing}
                                    required
                                    placeholder="H12345678"
                                />
                                <InputError message={errors.cif} className="mt-2" />
                            </div>

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

                            <div>
                                <Label htmlFor="ref_catastral">Referencia Catastral</Label>
                                <Input
                                    id="ref_catastral"
                                    value={data.ref_catastral}
                                    onChange={(e) => setData('ref_catastral', e.target.value.toUpperCase())}
                                    disabled={processing}
                                    placeholder="1234ABCD5678EFGH9012"
                                />
                                <InputError message={errors.ref_catastral} className="mt-2" />
                            </div>

                            <div>
                                <Label htmlFor="telefono">Teléfono</Label>
                                <PhoneInputField
                                    value={data.telefono}
                                    onChange={(value) => {
                                        if (!value) {
                                            setData("telefono", "");
                                            return;
                                        }
                                        const cleaned = value.replace(/\s/g, "");
                                        const normalized = cleaned.startsWith("+") ? cleaned : `+${cleaned}`;
                                        setData("telefono", normalized);
                                    }}
                                    error={errors.telefono}
                                />
                            </div>

                            {/* Campo para añadir usuarios */}
                            <div>
                                <Label>Usuarios de la Comunidad</Label>
                                <div className="flex flex-wrap gap-2 mb-2">
                                    {data.usuarios.map((userId) => {
                                        const usuario = usuarios.find(u => u.id === userId);
                                        if (!usuario) return null;
                                        return (
                                            <div key={userId} className="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 rounded-lg px-3 py-2">
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
                                    <Select
                                        onValueChange={(value) => agregarUsuario(value)}
                                        value=""
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Selecciona un usuario para agregar" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {usuarios
                                                .filter(u => !data.usuarios.includes(u.id))
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

                        <DialogFooter>
                            <DialogClose asChild>
                                <Button type="button" variant="outline" onClick={onClose}>
                                    Cancelar
                                </Button>
                            </DialogClose>
                            <Button type="submit" disabled={processing}>
                                {processing ? "Creando..." : "Crear"}
                            </Button>
                        </DialogFooter>
                    </form>
                </ScrollArea>
            </DialogContent>
        </Dialog>
    );
}
