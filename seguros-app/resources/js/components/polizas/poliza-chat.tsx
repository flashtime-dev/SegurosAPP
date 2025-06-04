import { ChatPoliza } from "@/types";
import { Chat } from "../chat";
import { useEffect, useRef, useState } from "react";
import axios from "axios";

export function PolizaChat({ chats: initialChats, authUser, polizaId, isClosed }: { chats: ChatPoliza[], authUser: number, polizaId: number, isClosed: boolean }) {
    const chatContainerRef = useRef<HTMLDivElement>(null);
    const [chats, setChats] = useState<ChatPoliza[]>(initialChats);
    const [mensaje, setMensaje] = useState("");
    
    useEffect(() => {
        if (chatContainerRef.current) {
            chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
        }
    }, [chats]);

    useEffect(() => {
        console.log(`🔌 Conectando al canal privado: chatPoliza.${polizaId}`);
        //const channel = window.Echo.channel(`chatPoliza.${polizaId}`); // channel() en lugar de private()
        const channel = window.Echo.private(`chatPoliza.${polizaId}`); // Asegúrate de que el canal sea privado si es necesario

        channel
            .subscribed(() => {
                console.log('✅ Suscrito exitosamente al canal privado');
            })
            .error((error: any) => {
                console.error('❌ Error al suscribirse:', error);
            });

        channel.listen('MessageSent', (e: any) => {
            console.log("📨 Mensaje recibido por WebSocket:", e);
            setChats((prev) => [...prev, e]); // Actualiza el estado con el nuevo mensaje
        });

        return () => {
            console.log(`🔌 Desconectando del canal: chatPoliza.${polizaId}`);
            window.Echo.leave(`chatPoliza.${polizaId}`);
        };
    }, [polizaId]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!mensaje.trim()) return;

        console.log("📤 Enviando mensaje:", mensaje);

        try {
            const response = await axios.post(`/chat-poliza/${polizaId}`, { mensaje });
            console.log("✅ Respuesta del servidor:", response.data);

            // Temporalmente agregar el mensaje aquí también para debug
            setChats([...chats, response.data.chat]);
            setMensaje("");
        } catch (error) {
            console.error("❌ Error al enviar el mensaje:", error);
        }
    };

    return (
        <div className="mt-4">
            <h3 className="text-lg font-semibold">Chat</h3>
            {/* <div className="mb-2 text-sm text-gray-600">
                Debug: Canal: chatPoliza.{polizaId} | Mensajes: {chats.length}
            </div> */}
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
                        onChange={(e) => setMensaje(e.target.value)}
                        className="border rounded-md p-2 flex-grow"
                        disabled={isClosed}
                    />
                    <button type="submit" className={`text-white rounded-md p-2 ${
                            isClosed ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600'
                        }`} disabled={isClosed}>
                        Enviar
                    </button>
                </form>
            </div>
        </div>
    );
}