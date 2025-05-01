import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}
export interface Poliza {
    id: number;
    numero: string;
    estado: string;
    alias: string;
    fecha_efecto: string;
    cuenta: string;
    forma_pago: string;
    prima_neta: number;
    prima_total: number;
    pdf_poliza: string | null;
    observaciones: string | null;
    compania: {
        nombre: string;
        url_logo: string;
    };
    comunidad: {
        nombre: string;
        cif: string;
        direccion: string;
    };
}

export interface ChatPoliza {
    id: number;
    id_poliza: number;
    mensaje: string;
    created_at: string;
    usuario: {
        id: number;
        name: string;
    };
}