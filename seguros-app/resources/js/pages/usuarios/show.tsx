import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Rol, User } from '@/types';

type Props = {
    user: User;
    roles: Rol[];
};

export default function Show({ user, roles }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        name: user.name || '',
        email: user.email || '',
        password: '',
        password_confirmation: '',
        address: String(user.address || ''),
        phone: String(user.phone || ''),
        state: String(user.state),
        id_rol: String(user.id_rol || ''),
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('usuarios.update', user.id), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Editar usuario', href: route('usuarios.show', user.id) }]}>
            <Head title={`Editar usuario - ${user.name}`} />

            <form className="max-w-2xl mx-auto p-4 space-y-6" onSubmit={submit}>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <Label htmlFor="name">Nombre</Label>
                        <Input
                            id="name"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            disabled={processing}
                            required
                            autoFocus
                            placeholder="Nombre completo"
                        />
                        <InputError message={errors.name} className="mt-2" />
                    </div>

                    <div>
                        <Label htmlFor="email">Correo electrónico</Label>
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
                        />
                        <InputError message={errors.password_confirmation} className="mt-2" />
                    </div>

                    <div className="sm:col-span-2">
                        <Label htmlFor="address">Dirección</Label>
                        <Input
                            id="address"
                            value={data.address}
                            onChange={(e) => setData('address', e.target.value)}
                            disabled={processing}
                            placeholder="Dirección de residencia"
                        />
                        <InputError message={errors.address} className="mt-2" />
                    </div>

                    <div>
                        <Label htmlFor="phone">Teléfono</Label>
                        <Input
                            id="phone"
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            disabled={processing}
                            placeholder="Número de teléfono"
                        />
                        <InputError message={errors.phone} className="mt-2" />
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

                    <div className="sm:col-span-2">
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
                                {roles.map((rol) => (
                                    <SelectItem key={rol.id} value={String(rol.id)}>
                                        {rol.nombre}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError message={errors.id_rol} className="mt-2" />
                    </div>
                </div>

                <Button type="submit" disabled={processing} className="w-full">
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2" />}
                    Guardar cambios
                </Button>
            </form>
        </AppLayout>
    );
}