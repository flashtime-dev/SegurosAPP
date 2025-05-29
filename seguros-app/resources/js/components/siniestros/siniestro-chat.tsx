import { ChatSiniestro } from "@/types";
import { Chat } from "../chat";
import { useEffect, useRef, useState } from "react";
import axios from "axios";

export function SiniestroChat({ chats: initialChats, authUser, siniestroId }: { chats: ChatSiniestro[], authUser: number, siniestroId: number }) {
    const chatContainerRef = useRef<HTMLDivElement>(null);
    const [chats, setChats] = useState<ChatSiniestro[]>(initialChats);
    const [mensaje, setMensaje] = useState("");

    // Efecto para desplazar el scroll hacia abajo cuando se actualizan los chats
    useEffect(() => {
        if (chatContainerRef.current) {
            chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
        }
    }, [chats]);

    
    useEffect(() => {
        console.log(`🔌 Conectando al canal privado: chatSiniestro.${siniestroId}`);
        //const channel = window.Echo.channel(`chatPoliza.${polizaId}`); // channel() en lugar de private()
        const channel = window.Echo.private(`chatSiniestro.${siniestroId}`); // Asegúrate de que el canal sea privado si es necesario

        channel
            .subscribed(() => {
                console.log('✅ Suscrito exitosamente al canal privado');
            })
            .error((error: any) => {
                console.error('❌ Error al suscribirse:', error);
            });

        channel.listen('MessageSentSiniestro', (e: any) => {
            console.log("📨 Mensaje recibido por WebSocket:", e);
            setChats((prev) => [...prev, e]); // Actualiza el estado con el nuevo mensaje
        });

        return () => {
            console.log(`🔌 Desconectando del canal: chatSiniestro.${siniestroId}`);
            window.Echo.leave(`chatSiniestro.${siniestroId}`);
        };
    }, [siniestroId]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault(); // Evita que la página se recargue
        if (!mensaje.trim()) return; // No enviar si el mensaje está vacío

        console.log("📤 Enviando mensaje:", mensaje);

        try {
            const response = await axios.post(`/chat-siniestro/${siniestroId}`, { mensaje });
            console.log("✅ Respuesta del servidor:", response.data);

            setChats([...chats, response.data.chat]); // Agrega el nuevo mensaje al estado
            setMensaje(""); // Limpia el campo de entrada
        } catch (error) {
            console.error("Error al enviar el mensaje:", error);
        }
    };

    return (
        <div className="mt-4">
            <h3 className="text-lg font-semibold">Chat</h3>
            <div className="mb-2 text-sm text-gray-600">
                Debug: Canal: chatSiniestro.{siniestroId} | Mensajes: {chats.length}
            </div>
            <div
                ref={chatContainerRef}
                className="bg-gray-100 border rounded-md p-4 h-[calc(100vh-200px)] sm:h-[400px] overflow-y-scroll overflow-x-hidden w-full"
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
                <form onSubmit={handleSubmit} className="flex gap-2">
                    <input
                        type="text"
                        placeholder="Escribe tu mensaje..."
                        value={mensaje}
                        onChange={(e) => setMensaje(e.target.value)} // Actualiza el estado con el valor del input
                        className="border rounded-md p-2 flex-grow"
                    />
                    <button type="submit" className="bg-blue-500 text-white rounded-md p-2">
                        Enviar
                    </button>
                </form>
            </div>
        </div>
    );
}