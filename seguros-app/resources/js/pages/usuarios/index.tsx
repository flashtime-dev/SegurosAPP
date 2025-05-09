import { UsuariosMenu } from "@/components/usuarios-menu";
import AppLayout from "@/layouts/app-layout";
import { TipoPermiso, Rol, User } from "@/types";
import { Head, usePage } from "@inertiajs/react";
import { UserCard } from "@/components/user-card";
import { Plus } from "lucide-react";
import { Button } from "@/components/ui/button";

export default function Index() {
    const { props } = usePage<{ users: User[], roles: Rol[], tipoPermisos: TipoPermiso[] }>();
    const { users } = props;
    return (
        <AppLayout breadcrumbs={[{ title: 'Usuarios', href: '/usuarios' }]}>
            <Head title="Usuarios" />
            <div className="container mx-auto px-4 py-6">
                <UsuariosMenu/>

                <Button className="mb-5" onClick={() => window.location.href = route('usuarios.create')}>
                    <Plus></Plus>Nuevo usuario
                </Button>
                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {users.map((usuario) => (
                        <UserCard key={usuario.id} usuario={usuario} />
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}