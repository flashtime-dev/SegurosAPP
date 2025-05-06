import { UsuariosMenu } from "@/components/usuarios-menu";
import AppLayout from "@/layouts/app-layout";
import { Rol, User } from "@/types";
import { Head, usePage } from "@inertiajs/react";

export default function Index() {
    const { props } = usePage<{ users: User[], roles: Rol[] }>();
    const { users, roles } = props;
    return (
        <AppLayout breadcrumbs={[{ title: 'Usuarios', href: '/usuarios' }]}>
            <Head title="Usuarios" />
            <div className="container mx-auto px-4 py-6">
                <UsuariosMenu usuarios={users} roles={roles} />
            </div>
        </AppLayout>
    );
}