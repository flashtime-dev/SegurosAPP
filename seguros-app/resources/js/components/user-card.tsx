import { User } from "@/types";

export function UserCard({ usuario }: { usuario: User }) {
    return (
        <div className="border rounded-lg shadow-md p-4 bg-white">
            <h2 className="text-lg font-bold text-gray-800">{usuario.name}</h2>
            <p className="text-gray-600">{usuario.email}</p>
            <div className="mt-4">
                <button className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Ver perfil
                </button>
            </div>
        </div>
    );
}