import { UsuariosMenu } from "@/components/usuarios-menu";
import AppLayout from "@/layouts/app-layout";
import { TipoPermiso, Rol, User } from "@/types";
import { Head, usePage } from "@inertiajs/react";
import { MostrarRolesPermisos } from "@/components/rol-card";
import { Plus } from "lucide-react";
import { Button } from "@/components/ui/button";

export default function Index() {
    const { props } = usePage<{ users: User[], roles: Rol[], tipoPermisos: TipoPermiso[] }>();
    const { roles, tipoPermisos } = props;
    return (
        <AppLayout breadcrumbs={[{ title: 'Roles', href: '/roles' }]}>
            <Head title="Roles" />
            <div className="container mx-auto px-4 py-6">
                <UsuariosMenu/>
                {/* Pestaña de Roles */}
                <Button className="mb-5" onClick={() => window.location.href = route('roles.create')}>
                    <Plus></Plus>Nuevo rol
                </Button>
                <MostrarRolesPermisos roles={roles} tipoPermisos={tipoPermisos} />
            </div>
        </AppLayout>
    );
}