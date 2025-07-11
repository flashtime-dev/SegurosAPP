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
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Rol, User } from "@/types";
import PhoneInputField from "@/components/PhoneInputField";

type Props = {
    isOpen: boolean;
    onClose: () => void;
    roles: Rol[];
    user: User;
    rolUsuarioActual: number;
};

export default function EditarEmpleadoModal({ isOpen, onClose, roles, user, rolUsuarioActual }: Props) {
    //Datos del formulario
    const { data, setData, put, processing, errors, reset } = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        address: "",
        phone: "",
        state: "",
        id_rol: "",
    });

    //console.log("user", user);

    //Actualizar automaticamente los datos del formulario en base al usuario
    useEffect(() => {
        if (user) {
            setData({
                name: user.name || "",
                email: user.email || "",
                password: "",
                password_confirmation: "",
                address: user.address || "",
                phone: user.phone || "",
                state: String(user.state),
                id_rol: String(user.rol.id || ""),
            });
        } else {
            reset();
        }
    }, [user]);

    // Filtrar roles permitidos según el rol del usuario actual
    const rolesPermitidos = roles.filter((rol) => {
        if (rolUsuarioActual !== 1) { // Si no es superadministrador (id 1)
            // No permitir super admin (id 1)
            return rol.id !== 1;
        }
        return true; // Otros roles pueden ver todos
    });

    //Enviar formulario
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        // Capitaliza la primera letra del nombre
        setData('name', data.name.charAt(0).toUpperCase() + data.name.slice(1));
        setData('address', data.address.charAt(0).toUpperCase() + data.address.slice(1));
        if (user) {
            put(route("usuarios.update", user.id), {
                onSuccess: () => {
                    reset();
                    onClose();
                },
            });
        }
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Editar Usuario</DialogTitle>
                    <DialogDescription>
                        Modifica los campos para actualizar el usuario.
                    </DialogDescription>
                </DialogHeader>

                <ScrollArea className="max-h-[70vh]">
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="grid gap-4">
                            <div>
                                <Label htmlFor="name">Nombre *</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e) => {
                                        const value = e.target.value;
                                        setData('name', value.charAt(0).toUpperCase() + value.slice(1));
                                    }}
                                    disabled={processing}
                                    required
                                    placeholder="Nombre completo"
                                />
                                <InputError message={errors.name} className="mt-2" />
                            </div>

                            <div>
                                <Label htmlFor="email">Correo electrónico *</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    disabled={processing}
                                    required
                                    placeholder="email@example.com"
                                />
                                <InputError message={errors.email} className="mt-2" />
                            </div>

                            <div>
                                <Label htmlFor="password">Contraseña *</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    disabled={processing}
                                    placeholder="Nueva contraseña"
                                    title="La contraseña debe ser de 8 caracteres y contener al menos: una letra mayúscula, una minúscula, un número y un carácter especial (@$!%*?&#_.-)"
                                />
                                <InputError message={errors.password} className="mt-2" />
                            </div>

                            <div>
                                <Label htmlFor="password_confirmation">Confirmar contraseña *</Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    disabled={processing}
                                    placeholder="Confirmar contraseña"
                                    title="La contraseña debe ser de 8 caracteres y contener al menos: una letra mayúscula, una minúscula, un número y un carácter especial (@$!%*?&#_.-)"
                                />
                                <InputError message={errors.password_confirmation} className="mt-2" />
                            </div>

                            <div>
                                <Label htmlFor="address">Dirección</Label>
                                <Input
                                    id="address"
                                    value={data.address}
                                    onChange={(e) => {
                                        const value = e.target.value;
                                        setData('address', value.charAt(0).toUpperCase() + value.slice(1));
                                    }}
                                    disabled={processing}
                                    placeholder="Dirección de residencia"
                                />
                                <InputError message={errors.address} className="mt-2" />
                            </div>

                            <div>
                                <Label htmlFor="phone">Teléfono</Label>
                                <PhoneInputField
                                    value={data.phone}
                                    onChange={(value) => {
                                        if (!value) {
                                            setData("phone", "");
                                            return;
                                        }
                                        const cleaned = value.replace(/\s/g, "");
                                        const normalized = cleaned === "" ? "" : (cleaned.startsWith("+") ? cleaned : `+${cleaned}`);
                                        setData("phone", normalized);
                                    }}
                                    error={errors.phone}
                                />
                            </div>

                            <div>
                                <Label htmlFor="state">Estado</Label>
                                <Select
                                    value={data.state}
                                    onValueChange={(value) => setData('state', value)}
                                    disabled={processing}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selecciona estado" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="1">Activo</SelectItem>
                                        <SelectItem value="0">Inactivo</SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError message={errors.state} className="mt-2" />
                            </div>

                            <div>
                                <Label htmlFor="id_rol">Rol</Label>
                                <Select
                                    value={data.id_rol}
                                    onValueChange={(value) => setData('id_rol', value)}
                                    disabled={processing}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selecciona un rol" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {rolesPermitidos.map((rol) => (
                                            <SelectItem key={rol.id} value={String(rol.id)}>
                                                {rol.nombre}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError message={errors.id_rol} className="mt-2" />
                            </div>
                        </div>

                        <DialogFooter>
                            <DialogClose asChild>
                                <Button type="button" variant="outline" onClick={onClose}>
                                    Cancelar
                                </Button>
                            </DialogClose>
                            <Button type="submit" disabled={processing}>
                                {processing ? "Guardando..." : "Guardar cambios"}
                            </Button>
                        </DialogFooter>
                    </form>
                </ScrollArea>
            </DialogContent>
        </Dialog>
    );
}
