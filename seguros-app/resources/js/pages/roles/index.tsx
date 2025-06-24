import { UsuariosMenu } from "@/components/usuarios/usuarios-menu";
import AppLayout from "@/layouts/app-layout";
import { TipoPermiso, Rol, User, Permiso } from "@/types";
import { Head, usePage } from "@inertiajs/react";
import { MostrarRolesPermisos } from "@/components/roles/MostrarRolesPermisos";
import { Plus } from "lucide-react";
import { Button } from "@/components/ui/button";
import { useState } from "react";
import CrearRolModal from "@/components/roles/CrearRolModal";
import EditarRolModal from "@/components/roles/EditarRolModal";

export default function Index() {
    // Obtenemos los datos de la página usando usePage
    const { roles, tipoPermisos, permisos } = usePage<{ roles: Rol[], tipoPermisos: TipoPermiso[], permisos: Permiso[] }>().props;
    
    // Definimos los estados para manejar la creación y edición de usuarios
    const [isCreating, setIsCreating] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [rolSeleccionado, setRolSeleccionado] = useState<Rol | null>(null);

    // Función para manejar la edición de un usuario
    const handleEdit = (rol: Rol) => {
        setRolSeleccionado(rol);
        setIsEditing(true);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Roles', href: '/roles' }]}>
            <Head title="Roles" />
            <div className="container mx-auto px-4 py-6">
                {/* Titulo del contenido */}
                <h1 className="text-2xl font-bold mb-6">Roles</h1>
                <div className="flex justify-between items-center">
                    {/* Submenu usuarios/roles */}
                    <UsuariosMenu />

                    {/* Botón para crear un nuevo rol */}
                    <Button className="mb-5" onClick={() => setIsCreating(true)}>
                        <Plus className="mr-2" />Nuevo rol
                    </Button>
                </div>

                {/* Caja con roles y permisos agrupados por tipo */}
                <MostrarRolesPermisos
                    roles={roles}
                    tipoPermisos={tipoPermisos}
                    onEditRol={handleEdit}
                />

                {/* Modal para crear un nuevo rol */}
                <CrearRolModal
                    isOpen={isCreating}
                    onClose={() => setIsCreating(false)}
                    permisos={permisos}
                />

                {/* Modal para editar un usuario existente */}
                {rolSeleccionado && (
                    <EditarRolModal
                        isOpen={isEditing}
                        onClose={() => {
                            setIsEditing(false);
                            setRolSeleccionado(null);
                        }}
                        rol={rolSeleccionado}
                        permisos={permisos}
                        permisosRol={rolSeleccionado.permisos || []}
                    />
                )}
            </div>
        </AppLayout>
    );
}