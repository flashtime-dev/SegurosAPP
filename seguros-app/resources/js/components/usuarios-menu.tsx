import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { UserCard } from "@/components/user-card";
import { RolCard } from "@/components/rol-card";
import { Rol, User } from "@/types";

import { Badge } from "@/components/ui/badge";
import { Plus } from "lucide-react";
import { Button } from "@headlessui/react";

export function UsuariosMenu({ usuarios, roles }: { usuarios: User[]; roles: Rol[] }) {
    return (
        <div>
            <Tabs defaultValue="usuarios" className="w-full">
                <TabsList className="mb-6">
                    <TabsTrigger value="usuarios">Usuarios</TabsTrigger>
                    <TabsTrigger value="roles">Roles</TabsTrigger>
                </TabsList>

                {/* Pestaña de Usuarios */}
                <TabsContent value="usuarios">
                    <Button className="mb-5" onClick={() => window.location.href = route('usuarios.create')}>
                        <Badge className="bg-blue-500 text-white"><Plus></Plus>Nuevo usuario</Badge>
                    </Button>
                    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        {usuarios.map((usuario) => (
                            <UserCard key={usuario.id} usuario={usuario} />
                        ))}
                    </div>
                </TabsContent>

                {/* Pestaña de Roles */}
                <TabsContent value="roles">
                    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        {roles.map((rol) => (
                            <RolCard key={rol.id} rol={rol} />
                        ))}
                    </div>
                </TabsContent>
            </Tabs>
        </div>
    );
}