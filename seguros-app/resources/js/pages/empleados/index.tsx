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
    //Obtener datos de la pagina
    const { user, users, roles } = usePage<{ user: User, users: User[], roles: Rol[] }>().props;
    const userLogged = user;  // Array con el usuario actual para pasar a modales
    const [isCreating, setIsCreating] = useState(false);  // Controla modal de creación
    const [isEditing, setIsEditing] = useState(false);    // Controla modal de edición
    const [usuarioSeleccionado, setUsuarioSeleccionado] = useState<User | null>(null);  // Usuario a editar

    //Funcion para editar
    const handleEdit = (usuario: User) => {
        setUsuarioSeleccionado(usuario);
        setIsEditing(true);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Empleados', href: '/empleados' }]}>
            <Head title="Empleados" />
            <div className="container mx-auto px-4 py-6">
                <Button className="mb-5" onClick={() => setIsCreating(true)}>
                    <Plus className="mr-2" />Nuevo empleado
                </Button>

                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {/* Mostrar usuarios */}
                    {users.map((usuario) => (
                        <UserCard
                            key={usuario.id}
                            usuario={usuario}
                            onEdit={() => handleEdit(usuario)}
                        />
                    ))}
                </div>
            </div>

            {/* Modal para crear */}
            <CrearEmpleadoModal
                usuario={userLogged}
                isOpen={isCreating}
                onClose={() => setIsCreating(false)}
                roles={roles}
                rolUsuarioActual={user.rol.id}
            />

            {/* Modal para editar */}
            {usuarioSeleccionado && (
                <EditarEmpleadoModal
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