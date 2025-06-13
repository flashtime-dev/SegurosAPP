import { ChatSiniestro } from "@/types";
import { Chat } from "../chat";
import { useEffect, useRef, useState } from "react";
import axios from "axios";
import { Button } from "@/components/ui/button";

//Mostrar chats de un siniestro

type Props = {
    chats: ChatSiniestro[];    // Lista inicial de mensajes
    authUser: number;          // ID del usuario autenticado
    siniestroId: number;       // ID del siniestro
    isClosed: boolean;         // Estado del siniestro (abierto/cerrado)
}

export function SiniestroChat({ chats: initialChats, authUser, siniestroId, isClosed }: Props) {
    //Estados y referencias
    const chatContainerRef = useRef<HTMLDivElement>(null);  // Referencia al contenedor del chat
    const [chats, setChats] = useState<ChatSiniestro[]>(initialChats);  // Estado de mensajes
    const [mensaje, setMensaje] = useState("");  // Estado del input de mensaje

    // Efecto para desplazar el scroll hacia abajo cuando se actualizan los chats
    useEffect(() => {
        if (chatContainerRef.current) {
            chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
        }
    }, [chats]);

    //Conexion del websocket
    useEffect(() => {
        console.log(`🔌 Conectando al canal privado: chatSiniestro.${siniestroId}`);
        //const channel = window.Echo.channel(`chatPoliza.${polizaId}`); // channel() en lugar de private()
        const channel = window.Echo.private(`chatSiniestro.${siniestroId}`); // Asegúrate de que el canal sea privado si es necesario

        channel
            .subscribed(() => {
                //console.log('✅ Suscrito exitosamente al canal privado');
            })
            .error((error: any) => {
                //console.error('❌ Error al suscribirse:', error);
            });

        channel.listen('MessageSentSiniestro', (e: any) => {
            //console.log("📨 Mensaje recibido por WebSocket:", e);
            setChats((prev) => [...prev, e]); // Actualiza el estado con el nuevo mensaje
        });

        return () => {
            //console.log(`🔌 Desconectando del canal: chatSiniestro.${siniestroId}`);
            window.Echo.leave(`chatSiniestro.${siniestroId}`);
        };
    }, [siniestroId]);


    //Envio de los mensajes
    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault(); // Evita que la página se recargue
        if (!mensaje.trim()) return; // No enviar si el mensaje está vacío

        //console.log("📤 Enviando mensaje:", mensaje);
        setMensaje(""); // Limpia el campo de entrada

        try {
            const response = await axios.post(`/chat-siniestro/${siniestroId}`, { mensaje });
            //console.log("✅ Respuesta del servidor:", response.data);

            setChats([...chats, response.data.chat]); // Agrega el nuevo mensaje al estado
            setMensaje(""); // Limpia el campo de entrada
        } catch (error) {
            //console.error("Error al enviar el mensaje:", error);
        }
    };

    return (
        //Interfaz del chat
        <div className="mt-4">
            <h3 className="text-lg font-semibold">Chat</h3>
            {/* Contenedor de mensajes */}
            <div
                ref={chatContainerRef}
                className="bg-gray-100 dark:bg-gray-950 border rounded-md p-4 h-[calc(100vh-200px)] sm:h-[400px] overflow-y-scroll overflow-x-hidden w-full"
            >
                {chats.length > 0 ? (
                    chats.map((chat) => (
                        <Chat key={chat.id} chat={chat} authUser={authUser} />
                    ))
                ) : (
                    <p>No hay mensajes en el chat.</p>
                )}
            </div>
            <div className="mt-4">
                {/* Envio de mensajes */}
                <form onSubmit={handleSubmit} className="flex gap-2">
                    <input
                        type="text"
                        placeholder="Escribe tu mensaje..."
                        value={mensaje}
                        onChange={(e) => setMensaje(e.target.value)} // Actualiza el estado con el valor del input
                        className="border rounded-md p-2 flex-grow dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                        disabled={isClosed}
                    />
                    <Button type="submit" className={`text-gray-100 rounded-md p-5 ${isClosed ? '!bg-gray-400 cursor-not-allowed' : 'cursor-pointer bg-blue-500 dark:bg-blue-700 hover:bg-blue-600 dark:hover:bg-blue-800 focus-visible:ring-blue-300 dark:focus-visible:ring-blue-400'
                        }`} disabled={isClosed}>
                        Enviar
                    </Button>
                </form>
            </div>
        </div>
    );
}