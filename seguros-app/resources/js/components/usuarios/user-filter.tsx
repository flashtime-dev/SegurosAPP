import { useState } from "react";

export function UserFilter() {
    const [nombre, setNombre] = useState('');
    const [email, setEmail] = useState('');
    const [rol, setRol] = useState('');

    const handleFilterChange = () => {
        // Aquí puedes manejar el cambio de filtros y enviar los datos al componente padre
        console.log('Filtros aplicados:', { nombre, email, rol });
    };

    return (
        <div className="mb-4">
            <h2 className="text-lg font-semibold mb-2">Filtrar Usuarios</h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input
                    type="text"
                    placeholder="Nombre"
                    value={nombre}
                    onChange={(e) => setNombre(e.target.value)}
                    className="border rounded px-3 py-2"
                />
                <input
                    type="text"
                    placeholder="Email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="border rounded px-3 py-2"
                />
                <select
                    value={rol}
                    onChange={(e) => setRol(e.target.value)}
                    className="border rounded px-3 py-2"
                >
                    <option value="">Seleccionar Rol</option>
                    <option value="admin">Admin</option>
                    <option value="user">Usuario</option>
                </select>
            </div>
            <button
                onClick={handleFilterChange}
                className="mt-4 bg-blue-500 text-white px-4 py-2 rounded"
            >
                Aplicar Filtros
            </button>
        </div>
    );
}