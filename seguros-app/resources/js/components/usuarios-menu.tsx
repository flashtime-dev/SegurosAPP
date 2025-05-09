import { Tabs, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { TipoPermiso, Rol, User } from "@/types";

export function UsuariosMenu() {
    // Determinar la pestaña activa según la ruta actual
    const activeTab = route().current("roles.index") ? "roles" : "usuarios";

    return (
        <div>
            <Tabs defaultValue={activeTab} value={activeTab} className="w-full">
                <TabsList className="mb-6">
                    {/* Redirige a usuarios.index */}
                    <TabsTrigger value="usuarios" onClick={() => (window.location.href = route('usuarios.index'))}>
                        Usuarios
                    </TabsTrigger>
                    {/* Redirige a roles.index */}
                    <TabsTrigger value="roles" onClick={() => (window.location.href = route('roles.index'))}>
                        Roles
                    </TabsTrigger>
                </TabsList>
            </Tabs>
        </div>
    );
}