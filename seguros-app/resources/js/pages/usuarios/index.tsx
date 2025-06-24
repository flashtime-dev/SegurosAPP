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
    // Obtenemos los datos de la página usando usePage
    const { users, roles, tipoPermisos } = usePage<{ users: User[], roles: Rol[], tipoPermisos: TipoPermiso[] }>().props;

    // Definimos los estados para manejar la creación y edición de usuarios
    const [isCreating, setIsCreating] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [usuarioSeleccionado, setUsuarioSeleccionado] = useState<User | null>(null);

    // Función para manejar la edición de un usuario
    const handleEdit = (usuario: User) => {
        setUsuarioSeleccionado(usuario);
        setIsEditing(true);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Usuarios', href: '/usuarios' }]}>
            <Head title="Usuarios" />
            <div className="container mx-auto px-4 py-6">
                {/* Titulo del contenido */}
                <h1 className="text-2xl font-bold mb-6">Usuarios</h1>
                <div className="flex justify-between items-center">
                    {/* Submenu usuarios/roles */}
                    <UsuariosMenu/>

                    {/* Botón para crear un nuevo usuario */}
                    <Button className="mb-5" onClick={() => setIsCreating(true)}>
                        <Plus className="mr-2"/>Nuevo usuario
                    </Button>
                </div>

                {/* Lista de usuarios */}
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

            {/* Modal para crear un nuevo usuario */}
            <CrearUsuarioModal
                usuarios={users}
                isOpen={isCreating}
                onClose={() => setIsCreating(false)}
                roles={roles}
            />

            {/* Modal para editar un usuario existente */}
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