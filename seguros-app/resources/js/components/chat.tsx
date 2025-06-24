import { ChatPoliza, ChatSiniestro } from "@/types";
import { usePage } from "@inertiajs/react";
import { format, parseISO } from "date-fns";

function formatearFecha(fechaIso: string) {
    return format(parseISO(fechaIso), "dd/MM/yyyy HH:mm");
}

export function Chat({ chat, authUser }: { chat: ChatPoliza | ChatSiniestro, authUser: number }) {
    const { auth } = usePage().props; // Obtén el usuario logueado
    const usuarioEmisor = chat.usuario.id === authUser; // Compara IDs

    return (
        <div className={`flex ${usuarioEmisor ? "justify-end" : "justify-start"} mb-2`}>
            <div className={`p-3 rounded-2xl shadow-md text-md
                ${usuarioEmisor
                    ? "bg-green-200 dark:bg-green-800 rounded-br-none text-left text-gray-900 dark:text-gray-100"
                    : "bg-white dark:bg-gray-800 border rounded-bl-none text-left text-gray-900 dark:text-gray-100"
                } max-w-full sm:max-w-[80%] lg:max-w-[60%]`}>
                <p className="font-semibold">{chat.usuario.name}</p>
                <p className="text-gray-800 dark:text-gray-100">{chat.mensaje}</p>
                <div className={`text-[12px] text-gray-500 dark:text-gray-200 mt-1 text-right`}>
                    {formatearFecha(chat.created_at)}
                </div>
            </div>
        </div>
    );
}
