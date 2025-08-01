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
    usuarios: User[];
    isOpen: boolean;
    onClose: () => void;
    roles: Rol[];
    user: User;
};

export default function EditarUsuarioModal({ usuarios, isOpen, onClose, roles, user }: Props) {
    //Filtro para solo mostrar solo los administradores principales
    const adminPrincipales = usuarios.filter(
        (usuario) => usuario.usuario_creador === null && usuario.rol.id === 2
    );

    //Datos para el formulario por defecto
    const { data, setData, put, processing, errors, reset } = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        address: "",
        phone: "",
        state: "",
        id_rol: "",
        id_usuario_creador: "",
    });

    //Si el rol es superadmin
    const isSuperadmin = data.id_rol === '1';

    //console.log("user", user); //Debug

    // Si hay cambios en el usuario actualizar los datos del formulario
    useEffect(() => {
        if (user) {
            //Asignar datos del usuario seleccionado (user)
            setData({
                name: user.name || "",
                email: user.email || "",
                password: "",
                password_confirmation: "",
                address: user.address || "",
                phone: user.phone || "",
                state: String(user.state),
                id_rol: String(user.rol.id || ""),
                id_usuario_creador: String(user.usuario_creador?.id_usuario_creador || ""),
            });
        } else {
            reset();
        }
    }, [user]);

    // Funcion al enviar el formulario
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Capitaliza la primera letra del nombre
        setData('name', data.name.charAt(0).toUpperCase() + data.name.slice(1));
        setData('address', data.address.charAt(0).toUpperCase() + data.address.slice(1));
        
        if (user) {
            //Mandar los datos a la ruta put
            put(route("usuarios.update", user.id), {
                onSuccess: () => {
                    reset();
                    onClose();
                },
            });
        }
    };

    // useEffect que se ejecuta cada vez que cambia el rol
    // Si el nuevo rol es superadmin, limpiamos el campo 'id_usuario_creador'
    React.useEffect(() => {
        if (isSuperadmin) {
            setData('id_usuario_creador', '');
        }
    }, [data.id_rol]);

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
                                <Label htmlFor="password">Contraseña</Label>
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
                                <Label htmlFor="password_confirmation">Confirmar contraseña</Label>
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
                                {/* Componente para los telefonos */}
                                <PhoneInputField
                                    value={data.phone}
                                    onChange={(value) => {
                                        if (!value) {
                                            setData("phone", "");
                                            return;
                                        }
                                        // Quitar todos los espacios
                                        const cleaned = value.replace(/\s/g, "");
                                        // Comprobar que empieza por +, si no se lo agregamos
                                        const normalized = cleaned === "" ? "" : (cleaned.startsWith("+") ? cleaned : `+${cleaned}`);
                                        setData("phone", normalized);
                                    }}
                                    error={errors.phone}
                                />
                            </div>

                            <div>
                                <Label htmlFor="state">Estado *</Label>
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
                                <Label htmlFor="id_rol">Rol *</Label>
                                <Select
                                    value={data.id_rol}
                                    onValueChange={(value) => setData('id_rol', value)}
                                    disabled={processing}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selecciona un rol" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {roles.map((rol) => (
                                            <SelectItem key={rol.id} value={String(rol.id)}>
                                                {rol.nombre}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError message={errors.id_rol} className="mt-2" />
                            </div>
                            <div>
                                <Label htmlFor="id_usuario_creador">Es empleado de otro usuario?</Label>
                                <Select
                                    // Valor actual del campo
                                    value={data.id_usuario_creador}
                                    // Al seleccionar un valor, actualizamos el estado
                                    onValueChange={(value) => setData('id_usuario_creador', value)}
                                    // Deshabilitamos el campo si estamos procesando o si el rol es superadministrador
                                    disabled={processing || isSuperadmin}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selecciona un usuario en caso afirmativo" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {adminPrincipales.map((usuario) => (
                                            <SelectItem key={usuario.id} value={String(usuario.id)}>
                                                {usuario.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError message={errors.id_usuario_creador} className="mt-2" />
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
