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
    role?: number [];
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
    address: string;
    phone: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    state: number;
    rol: Rol;
    subusuarios: User[];
    usuario_creador:{
        id: number;
        id_usuario_creador: string;
    }
}

export interface Agente {
    id: number;
    nombre: string;
    email: string;
    telefono: string;
};

export interface Rol {
    id: number;
    nombre: string;
    permisos?: Permiso[];
}

export interface Permiso {
    id: number;
    nombre: string;
    descripcion: string;
    id_tipo: number;
    tipoPermiso: TipoPermiso;
}
export interface TipoPermiso {
    id: number;
    nombre: string;
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

export interface Telefono {
    id: number;
    telefono: string;
    descripcion: string;
}

export interface Compania {
    id: number;
    nombre: string;
    url_logo: string;
    telefonos: Telefono[];
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

export interface ChatSiniestro {
    id: number;
    id_siniestro: number;
    mensaje: string;
    created_at: string;
    usuario: {
        id: number;
        name: string;
    };
}

export interface Siniestro {
    id: number;
    declaracion: string;
    tramitador: string;
    expediente: string;
    exp_cia: string;
    exp_asist: string;
    fecha_ocurrencia: string;
    adjunto: string | null;
    poliza: Poliza;
    estado: string;
}

export interface Comunidad {
    id: number;
    nombre: string;
    cif: string;
    direccion: string;
    ubi_catastral: string;
    ref_catastral: string;
    telefono: string;
    polizas: Poliza[];
    users: User[];
}