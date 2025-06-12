import * as React from "react";

interface FiltroPolizasProps {
    onFilter: (filtros: {
        nombreCompania: string;
        nombreComunidad: string;
        numeroPoliza: string;
        cif: string;
    }) => void;
}

const FiltroPolizas: React.FC<FiltroPolizasProps> = ({ onFilter }) => {
    const [nombreCompania, setNombreCompania] = React.useState('');
    const [nombreComunidad, setNombreComunidad] = React.useState('');
    const [numeroPoliza, setNumeroPoliza] = React.useState('');
    const [cif, setCif] = React.useState('');

    // Llama a onFilter cada vez que cambie algún filtro
    React.useEffect(() => {
        onFilter({ nombreCompania, nombreComunidad, numeroPoliza, cif });
    }, [nombreCompania, nombreComunidad, numeroPoliza, cif]);

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
            <input
                type="text"
                name="nombreCompania"
                placeholder="Nombre compañía"
                value={nombreCompania}
                onChange={(e) => setNombreCompania(e.target.value)}
                className="px-4 py-2 border rounded shadow-sm w-full"
            />
            <input
                type="text"
                name="nombreComunidad"
                placeholder="Nombre comunidad"
                value={nombreComunidad}
                onChange={(e) => setNombreComunidad(e.target.value)}
                className="px-4 py-2 border rounded shadow-sm w-full"
            />
            <input
                type="text"
                name="numeroPoliza"
                placeholder="Número de póliza"
                value={numeroPoliza}
                onChange={(e) => setNumeroPoliza(e.target.value)}
                className="px-4 py-2 border rounded shadow-sm w-full"
            />
            <input
                type="text"
                name="cif"
                placeholder="CIF de la comunidad"
                value={cif}
                onChange={(e) => setCif(e.target.value)}
                className="px-4 py-2 border rounded shadow-sm w-full"
            />
        </div>
    );
};

export default FiltroPolizas;
