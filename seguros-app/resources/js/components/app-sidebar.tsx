import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { Rol, type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { Building, FileText, LayoutGrid, Phone, ShieldUser , User, Zap } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Panel de Control',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Usuarios',
        href: '/usuarios',
        icon: User,
        role: [1],
    },
    {
        title: 'Empleados',
        href: '/empleados',
        icon: User,
        role: [2],
    },
    {
        title: 'Comunidades',
        href: '/comunidades',
        icon: Building,
        role: [1,2],
    },
    {
        title: 'Agentes',
        href: '/agentes',
        icon: ShieldUser,
        role: [1],
    },
    {
        title: 'Polizas',
        href: '/polizas',
        icon: FileText,
    },
    {
        title: 'Siniestros',
        href: '/siniestros',
        icon: Zap,
    },
    {
        title: 'Telefonos de Asistencia',
        href: '/telefonos-asistencia',
        icon: Phone,
    },
];

const footerNavItems: NavItem[] = [
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems}/>
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
