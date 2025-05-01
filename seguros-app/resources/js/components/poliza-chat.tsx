import { ChatPoliza } from "@/types";
import { Chat } from "./chat";
import { useEffect, useRef } from "react";

export function PolizaChat({ chats, authUser }: { chats: ChatPoliza[], authUser: number }) {
    const chatContainerRef = useRef<HTMLDivElement>(null);

    // Efecto para desplazar el scroll hacia abajo cuando se actualizan los chats
    useEffect(() => {
        if (chatContainerRef.current) {
            chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
        }
    }, [chats]);

    const handleSubmit = (e: { preventDefault: () => void; }) => {
        e.preventDefault();
        
    };

    return (
        <div className="mt-4">
            <h3 className="text-lg font-semibold">Chat</h3>
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