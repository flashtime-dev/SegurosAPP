import { UsuariosMenu } from "@/components/usuarios/usuarios-menu";
import AppLayout from "@/layouts/app-layout";
import { TipoPermiso, Rol, User } from "@/types";
import { Head, usePage } from "@inertiajs/react";
import { UserCard } from "@/components/usuarios/user-card";
import { Plus } from "lucide-react";
import { Button } from "@/components/ui/button";
import { useState } from "react";
import EditarEmpleadoModal from "@/components/empleados/EditarEmpleadoModal";
import CrearEmpleadoModal from "@/components/empleados/CrearEmpleadoModal";

export default function Index() {
    const {user, users, roles } = usePage<{ user: User, users: User[], roles: Rol[] }>().props;
    const userLogged = [user];
    const [isCreating, setIsCreating] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [usuarioSeleccionado, setUsuarioSeleccionado] = useState<User | null>(null);

    const handleEdit = (usuario: User) => {
        setUsuarioSeleccionado(usuario);
        setIsEditing(true);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Empleados', href: '/empleados' }]}>
            <Head title="Empleados" />
            <div className="container mx-auto px-4 py-6">
                <Button className="mb-5" onClick={() => setIsCreating(true)}>
                    <Plus className="mr-2"/>Nuevo empleado
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

            <CrearEmpleadoModal
                usuarios={userLogged}
                isOpen={isCreating}
                onClose={() => setIsCreating(false)}
                roles={roles}
                rolUsuarioActual={user.rol.id}
            />

            {usuarioSeleccionado && (
                <EditarEmpleadoModal
                    usuarios={userLogged}
                    isOpen={isEditing}
                    onClose={() => {
                        setIsEditing(false);
                        setUsuarioSeleccionado(null);
                    }}
                    roles={roles}
                    user={usuarioSeleccionado}
                    rolUsuarioActual={user.rol.id}
                />
            )}
        </AppLayout>
    );
}