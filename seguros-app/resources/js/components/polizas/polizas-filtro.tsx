import * as React from "react";
// Definimos el tipo de las propiedades (Props) que recibe el componente
interface FiltroPolizasProps {
    // La función onFilter que se ejecutará cada vez que cambien los filtros
    onFilter: (filtros: {
        nombreCompania: string;
        nombreComunidad: string;
        numeroPoliza: string;
        cif: string;
    }) => void; 
}
// Componente de filtro de pólizas
const FiltroPolizas: React.FC<FiltroPolizasProps> = ({ onFilter }) => {
    // Definimos los estados para cada campo del filtro
    const [nombreCompania, setNombreCompania] = React.useState('');
    const [nombreComunidad, setNombreComunidad] = React.useState('');
    const [numeroPoliza, setNumeroPoliza] = React.useState('');
    const [cif, setCif] = React.useState('');

    // Llama a onFilter cada vez que cambie algún filtro
    React.useEffect(() => {
        onFilter({ nombreCompania, nombreComunidad, numeroPoliza, cif });
    }, [nombreCompania, nombreComunidad, numeroPoliza, cif]);

    return (
        <div className="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            {/* Campo de filtro para el nombre de la compañía */}
            <input
                type="text"
                name="nombreCompania"
                placeholder="Nombre compañía"
                value={nombreCompania}
                onChange={(e) => setNombreCompania(e.target.value)}
                className="w-full rounded border px-4 py-2 shadow-sm"
            />
            {/* Campo de filtro para el nombre de la comunidad */}
            <input
                type="text"
                name="nombreComunidad"
                placeholder="Nombre comunidad"
                value={nombreComunidad}
                onChange={(e) => setNombreComunidad(e.target.value)}
                className="w-full rounded border px-4 py-2 shadow-sm"
            />
            {/* Campo de filtro para el número de póliza */}
            <input
                type="text"
                name="numeroPoliza"
                placeholder="Número de póliza"
                value={numeroPoliza}
                onChange={(e) => setNumeroPoliza(e.target.value)}
                className="w-full rounded border px-4 py-2 shadow-sm"
            />
            {/* Campo de filtro para el CIF de la comunidad */}
            <input
                type="text"
                name="cif"
                placeholder="CIF de la comunidad"
                value={cif}
                onChange={(e) => setCif(e.target.value)}
                className="w-full rounded border px-4 py-2 shadow-sm"
            />
        </div>
    );
};

export default FiltroPolizas;
