import { UsuariosMenu } from "@/components/usuarios/usuarios-menu";
import AppLayout from "@/layouts/app-layout";
import { TipoPermiso, Rol, User } from "@/types";
import { Head, usePage } from "@inertiajs/react";
import { UserCard } from "@/components/usuarios/user-card";
import { Plus } from "lucide-react";
import { Button } from "@/components/ui/button";
import { useState } from "react";
import CrearUsuarioModal from "@/components/usuarios/CrearUsuarioModal";
import EditarUsuarioModal from "@/components/usuarios/EditarUsuarioModal";

export default function Index() {
    const { users, roles, tipoPermisos } = usePage<{ users: User[], roles: Rol[], tipoPermisos: TipoPermiso[] }>().props;

    const [isCreating, setIsCreating] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [usuarioSeleccionado, setUsuarioSeleccionado] = useState<User | null>(null);

    const handleEdit = (usuario: User) => {
        setUsuarioSeleccionado(usuario);
        setIsEditing(true);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Usuarios', href: '/usuarios' }]}>
            <Head title="Usuarios" />
            <div className="container mx-auto px-4 py-6">
                <UsuariosMenu/>

                <Button className="mb-5" onClick={() => setIsCreating(true)}>
                    <Plus className="mr-2"/>Nuevo usuario
                </Button>

                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {users.map((usuario) => (
                        <UserCard 
                            key={usuario.id} 
                            usuario={usuario}
                            onEdit={() => handleEdit(usuario)}
                        />
                    ))}
                </div>
            </div>

            <CrearUsuarioModal
                usuarios={users}
                isOpen={isCreating}
                onClose={() => setIsCreating(false)}
                roles={roles}
            />

            {usuarioSeleccionado && (
                <EditarUsuarioModal
                    usuarios={users}
                    isOpen={isEditing}
                    onClose={() => {
                        setIsEditing(false);
                        setUsuarioSeleccionado(null);
                    }}
                    roles={roles}
                    user={usuarioSeleccionado}
                />
            )}
        </AppLayout>
    );
}