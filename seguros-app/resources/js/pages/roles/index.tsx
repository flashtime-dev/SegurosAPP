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
    const { roles, tipoPermisos, permisos } = usePage<{ roles: Rol[], tipoPermisos: TipoPermiso[], permisos: Permiso[] }>().props;
    
    const [isCreating, setIsCreating] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [rolSeleccionado, setRolSeleccionado] = useState<Rol | null>(null);

    const handleEdit = (rol: Rol) => {
        setRolSeleccionado(rol);
        setIsEditing(true);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Roles', href: '/roles' }]}>
            <Head title="Roles" />
            <div className="container mx-auto px-4 py-6">
                <UsuariosMenu />

                <Button className="mb-5 cursor-pointer" onClick={() => setIsCreating(true)}>
                    <Plus className="mr-2" />Nuevo rol
                </Button>

                <MostrarRolesPermisos
                    roles={roles}
                    tipoPermisos={tipoPermisos}
                    onEditRol={handleEdit}
                />

                <CrearRolModal
                    isOpen={isCreating}
                    onClose={() => setIsCreating(false)}
                    permisos={permisos}
                />

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