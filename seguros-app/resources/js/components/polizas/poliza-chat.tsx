import { ChatPoliza } from "@/types";
import { Chat } from "../chat";
import { useEffect, useRef, useState } from "react";
import axios from "axios";

export function PolizaChat({ chats: initialChats, authUser, polizaId }: { chats: ChatPoliza[], authUser: number, polizaId: number }) {
    const chatContainerRef = useRef<HTMLDivElement>(null);
    const [chats, setChats] = useState<ChatPoliza[]>(initialChats);
    const [mensaje, setMensaje] = useState("");

    // // Efecto para desplazar el scroll hacia abajo cuando se actualizan los chats
    // useEffect(() => {
    //     if (chatContainerRef.current) {
    //         chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
    //     }
        
    //     console.log(`🔌 Conectando al canal: chatPoliza.${polizaId}`);
    //     const channel = window.Echo.private(`chatPoliza.${polizaId}`);

    //     // Debug: Verificar eventos del canal
    //     channel
    //         .subscribed(() => {
    //             console.log('✅ Suscrito exitosamente al canal');
    //         })
    //         .error((error: any) => {
    //             console.error('❌ Error al suscribirse:', error);
    //         });

    //     channel.listen('MessageSent', (e: any) => {
    //         console.log("📨 Mensaje recibido por WebSocket:", e);
    //         console.log("📨 Datos del mensaje:", JSON.stringify(e, null, 2));
            
    //         setChats((prev) => {
    //             console.log("🔄 Actualizando chats. Chats anteriores:", prev.length);
    //             const newChats = [...prev, e];
    //             console.log("🔄 Nuevos chats:", newChats.length);
    //             return newChats;
    //         });
    //     });

    //     // Debug: Escuchar todos los eventos para ver qué llega
    //     channel.listenForWhisper('.client-typing', (e: any) => {
    //         console.log('👂 Whisper recibido:', e);
    //     });

    //     return () => {
    //         console.log(`🔌 Desconectando del canal: chatPoliza.${polizaId}`);
    //         window.Echo.leave(`chatPoliza.${polizaId}`);
    //     };
    // }, [polizaId, chats]);

    // En poliza-chat.tsx - SOLO PARA PRUEBAS
useEffect(() => {
    if (chatContainerRef.current) {
        chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
    }
    
    console.log(`🔌 Conectando al canal PÚBLICO: chatPoliza.${polizaId}`);
    const channel = window.Echo.channel(`chatPoliza.${polizaId}`); // channel() en lugar de private()

    channel
        .subscribed(() => {
            console.log('✅ Suscrito exitosamente al canal público');
        })
        .error((error: any) => {
            console.error('❌ Error al suscribirse:', error);
        });

    channel.listen('MessageSent', (e: any) => {
        console.log("📨 Mensaje recibido por WebSocket:", e);
        setChats((prev) => [...prev, e]);
    });

    return () => {
        console.log(`🔌 Desconectando del canal: chatPoliza.${polizaId}`);
        window.Echo.leave(`chatPoliza.${polizaId}`);
    };
}, [polizaId, chats]);

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
            <div className="mb-2 text-sm text-gray-600">
                Debug: Canal: chatPoliza.{polizaId} | Mensajes: {chats.length}
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
                        onChange={(e) => setMensaje(e.target.value)}
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