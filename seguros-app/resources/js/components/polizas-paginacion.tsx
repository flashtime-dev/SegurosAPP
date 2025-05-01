import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationLink,
    PaginationPrevious,
    PaginationNext,
  } from "@/components/ui/pagination";
  import React from "react";
  
  interface PaginacionPolizasProps {
    paginaActual: number;
    totalPaginas: number;
    onPageChange: (pagina: number) => void;
  }
  
  const generarPaginas = (paginaActual: number, totalPaginas: number) => {
    const paginas = [];
  
    if (totalPaginas <= 7) {
      for (let i = 1; i <= totalPaginas; i++) paginas.push(i);
    } else {
      paginas.push(1);
  
      if (paginaActual > 4) paginas.push("...");
  
      const inicio = Math.max(2, paginaActual - 1);
      const fin = Math.min(totalPaginas - 1, paginaActual + 1);
  
      for (let i = inicio; i <= fin; i++) paginas.push(i);
  
      if (paginaActual < totalPaginas - 3) paginas.push("...");
  
      paginas.push(totalPaginas);
    }
  
    return paginas;
  };
  
  const PaginacionPolizas: React.FC<PaginacionPolizasProps> = ({
    paginaActual,
    totalPaginas,
    onPageChange,
  }) => {
    const paginas = generarPaginas(paginaActual, totalPaginas);
  
    return (
      <Pagination className="mt-6">
        <PaginationContent>
          <PaginationItem>
            <PaginationPrevious
              href="#"
              onClick={() => onPageChange(paginaActual - 1)}
              className={paginaActual === 1 ? "pointer-events-none opacity-50" : ""}
            />
          </PaginationItem>
  
          {paginas.map((pagina, idx) => (
            <PaginationItem key={idx}>
              {pagina === "..." ? (
                <span className="px-2 text-gray-500">...</span>
              ) : (
                <PaginationLink
                  href="#"
                  isActive={pagina === paginaActual}
                  onClick={() => onPageChange(pagina as number)}
                >
                  {pagina}
                </PaginationLink>
              )}
            </PaginationItem>
          ))}
  
          <PaginationItem>
            <PaginationNext
              href="#"
              onClick={() => onPageChange(paginaActual + 1)}
              className={paginaActual === totalPaginas ? "pointer-events-none opacity-50" : ""}
            />
          </PaginationItem>
        </PaginationContent>
      </Pagination>
    );
  };
  
  export default PaginacionPolizas;
  