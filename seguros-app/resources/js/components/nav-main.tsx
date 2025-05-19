import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { Rol, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';

export function NavMain({ items = [], }: { items: NavItem[]; }) {
    const page = usePage();
    const { auth } = page.props;
    const rol = (auth as { user: { id_rol: number } }).user.id_rol;

    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupLabel>Platform</SidebarGroupLabel>
            <SidebarMenu>
                {items
                    .filter(item => {
                        // Si el ítem no tiene roles especificados, se muestra
                        if (!item.role || !item.role.length) return true;
                        
                        // Si no hay rol de usuario, no mostrar items con restricción
                        if (!rol) return false;

                        // Si el rol del usuario coincide con alguno de los roles permitidos
                        if (item.role.includes(rol)) return true;

                        // Por defecto, no mostrar
                        return false;
                    })
                    .map((item) => (
                        <SidebarMenuItem key={item.title}>
                            <SidebarMenuButton  
                                asChild isActive={item.href === page.url}
                                tooltip={{ children: item.title }}
                            >
                                <Link href={item.href} prefetch>
                                    {item.icon && <item.icon />}
                                    <span>{item.title}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    ))}
            </SidebarMenu>
        </SidebarGroup>
    );
}
