import { Rol } from "@/types";

export function RolCard({ rol }: { rol: Rol }) {
    return (
        <div className="border rounded-lg shadow-md p-4 bg-white">
            <h2 className="text-lg font-bold text-gray-800">{rol.nombre}</h2>
            <div className="mt-4">
                <button className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Ver permisos
                </button>
            </div>
        </div>
    );
}