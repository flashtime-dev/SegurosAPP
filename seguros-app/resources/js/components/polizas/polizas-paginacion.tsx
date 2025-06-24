import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationLink,
    PaginationPrevious,
    PaginationNext,
} from "@/components/ui/pagination";
import React from "react";

// Definición de las props que espera el componente de paginación
interface PaginacionPolizasProps {
    paginaActual: number;
    totalPaginas: number;
    onPageChange: (pagina: number) => void;
}
// Función para generar las páginas de la paginación, teniendo en cuenta el número total de páginas y la página actual
const generarPaginas = (paginaActual: number, totalPaginas: number) => {
    const paginas = []; // Inicializamos un array vacío para guardar las páginas
    // Si el total de páginas es menor o igual a 7, mostramos todas las páginas
    if (totalPaginas <= 7) {
        for (let i = 1; i <= totalPaginas; i++) paginas.push(i);
    } else {
        paginas.push(1); // Siempre mostrar la primera página

        if (paginaActual > 4) paginas.push('...'); // Si la página actual está más allá de la cuarta, añadimos "..."
        // Definir el rango de páginas cercanas a la página actual
        const inicio = Math.max(2, paginaActual - 1); // El inicio es 2 o la página actual - 1
        const fin = Math.min(totalPaginas - 1, paginaActual + 1); // El fin es la página actual + 1 o la penúltima página
        // Agregar las páginas del rango cercano
        for (let i = inicio; i <= fin; i++) paginas.push(i);
        // Si la página actual está más cerca del final, añadir "..."
        if (paginaActual < totalPaginas - 3) paginas.push('...');
        // Siempre mostrar la última página
        paginas.push(totalPaginas);
    }

    return paginas; // Devolvemos las páginas generadas
};
// Componente de paginación para las pólizas
const PaginacionPolizas: React.FC<PaginacionPolizasProps> = ({
    paginaActual,
    totalPaginas,
    onPageChange,
}) => {
    const paginas = generarPaginas(paginaActual, totalPaginas);

    return (
        <Pagination className="mb-6">
            <PaginationContent>
                {/* Botón para ir a la página anterior */}
                <PaginationItem>
                    <PaginationPrevious
                        href="#"
                        onClick={() => onPageChange(paginaActual - 1)}
                        className={paginaActual === 1 ? 'pointer-events-none opacity-50' : ''}
                    />
                </PaginationItem>
                {/* Iterar sobre las páginas generadas y mostrar los elementos de paginación */}
                {paginas.map((pagina, idx) => (
                    <PaginationItem key={idx}>
                        {pagina === '...' ? (
                            // Si la página es "..." mostrar como un texto
                            <span className="px-2 text-gray-500">...</span>
                        ) : (
                            // Si la página es un número, mostrarla como un enlace
                            <PaginationLink href="#" isActive={pagina === paginaActual} onClick={() => onPageChange(pagina as number)}>
                                {pagina}
                            </PaginationLink>
                        )}
                    </PaginationItem>
                ))}
                {/* Botón para ir a la página siguiente */}
                <PaginationItem>
                    <PaginationNext
                        href="#"
                        onClick={() => onPageChange(paginaActual + 1)}
                        className={paginaActual === totalPaginas ? 'pointer-events-none opacity-50' : ''}
                    />
                </PaginationItem>
            </PaginationContent>
        </Pagination>
    );
};

export default PaginacionPolizas;
