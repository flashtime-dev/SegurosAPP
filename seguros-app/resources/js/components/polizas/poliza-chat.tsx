import { ChatPoliza } from "@/types";
import { Chat } from "../chat";
import { useEffect, useRef, useState } from "react";
import axios from "axios";
import { Button } from "@/components/ui/button";

export function PolizaChat({ chats: initialChats, authUser, polizaId, isClosed }: { chats: ChatPoliza[], authUser: number, polizaId: number, isClosed: boolean }) {
    const chatContainerRef = useRef<HTMLDivElement>(null);
    const [chats, setChats] = useState<ChatPoliza[]>(initialChats);
    const [mensaje, setMensaje] = useState('');
    // Efecto secundario que hace scroll automáticamente hacia el final del chat cuando llega un nuevo mensaje
    useEffect(() => {
        if (chatContainerRef.current) {
            chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight; // Desplaza el contenedor hacia abajo
        }
    }, [chats]); // El efecto se ejecuta cada vez que `chats` cambia (es decir, cuando se recibe un nuevo mensaje).
    // Efecto secundario para conectar al canal privado de WebSocket cuando el componente se monta
    useEffect(() => {
        //console.log(`🔌 Conectando al canal privado: chatPoliza.${polizaId}`);
        //const channel = window.Echo.channel(`chatPoliza.${polizaId}`); // channel() en lugar de private()
        // Aquí se conecta al canal de chat privado de la póliza usando Laravel Echo
        const channel = window.Echo.private(`chatPoliza.${polizaId}`); // Asegúrate de que el canal sea privado si es necesario

        channel
            .subscribed(() => {
                //console.log('✅ Suscrito exitosamente al canal privado');
            })
            .error((error: any) => {
                //console.error('❌ Error al suscribirse:', error);
            });
        // Escucha el evento 'MessageSent' para recibir nuevos mensajes en tiempo real
        channel.listen('MessageSent', (e: any) => {
            //console.log("📨 Mensaje recibido por WebSocket:", e);
            setChats((prev) => [...prev, e]); // Actualiza el estado con el nuevo mensaje
        });
        // Cleanup: al desmontar el componente, se deja de escuchar el canal.
        return () => {
            //console.log(`🔌 Desconectando del canal: chatPoliza.${polizaId}`);
            window.Echo.leave(`chatPoliza.${polizaId}`);
        };
    }, [polizaId]); // Este efecto depende del `polizaId`, por lo que se ejecuta cada vez que cambia el ID de la póliza.
    // Función para manejar el envío de un mensaje
    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!mensaje.trim()) return; // No envía el mensaje si está vacío o solo contiene espacios.

        //console.log("📤 Enviando mensaje:", mensaje);
        setMensaje(''); // Limpia el input inmediatamente despues de enviar el mensaje

        try {
            // Enviar el mensaje al backend usando Axios
            const response = await axios.post(`/chat-poliza/${polizaId}`, { mensaje });
            //console.log("✅ Respuesta del servidor:", response.data);
            // Temporalmente agregar el mensaje aquí también para debug
            setChats([...chats, response.data.chat]);
            setMensaje('');
        } catch (error) {
            console.error('❌ Error al enviar el mensaje:', error);
        }
    };

    return (
        <div className="mt-4">
            {/* Título del chat */}
            <h3 className="text-lg font-semibold">Chat</h3>
            {/* <div className="mb-2 text-sm text-gray-600">
                Debug: Canal: chatPoliza.{polizaId} | Mensajes: {chats.length}
            </div> */}
            <div
                ref={chatContainerRef} // Asocia la referencia para controlar el desplazamiento automático.
                className="h-[calc(100vh-200px)] w-full overflow-x-hidden overflow-y-scroll rounded-md border bg-gray-100 p-4 sm:h-[400px] dark:bg-gray-950"
            >
                {/* Mostrar los mensajes del chat */}
                {chats.length > 0 ? chats.map((chat) => <Chat key={chat.id} chat={chat} authUser={authUser} />) : <p>No hay mensajes en el chat.</p>}
            </div>
            {/* Formulario para enviar un mensaje */}
            <div className="mt-4">
                <form onSubmit={handleSubmit} className="flex gap-2">
                    <input
                        type="text"
                        placeholder="Escribe tu mensaje..."
                        value={mensaje}
                        onChange={(e) => setMensaje(e.target.value)}
                        className="flex-grow rounded-md border p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                        disabled={isClosed}
                    />
                    {/* Botón para enviar el mensaje, está deshabilitado si el chat está cerrado */}
                    <Button
                        type="submit"
                        className={`rounded-md p-5 text-gray-100 ${
                            isClosed
                                ? 'cursor-not-allowed !bg-gray-400'
                                : 'cursor-pointer bg-blue-500 hover:bg-blue-600 focus-visible:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus-visible:ring-blue-400'
                        }`}
                        disabled={isClosed}
                    >
                        Enviar
                    </Button>
                </form>
            </div>
        </div>
    );
}