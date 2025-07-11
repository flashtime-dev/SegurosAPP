import * as React from "react"

import { cn } from "@/lib/utils"
 // Componente principal de la tarjeta (Card)
function Card({ className, ...props }: React.ComponentProps<"div">) {
  return (
    <div
      data-slot="card"
      className={cn(
        "bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-gray-200 py-6 shadow-sm hover:shadow-lg transition-shadow dark:bg-gray-800 dark:border-gray-700 dark:shadow-lg dark:hover:shadow-gray-700/17",
        className
      )}
      {...props}
    />
  )
}
// Componente para el encabezado de la tarjeta (CardHeader)
function CardHeader({ className, ...props }: React.ComponentProps<"div">) {
  return (
    <div
      data-slot="card-header"
      className={cn("flex flex-col gap-1.5 px-6", className)}
      {...props}
    />
  )
}
// Componente para el título de la tarjeta (CardTitle)
function CardTitle({ className, ...props }: React.ComponentProps<"div">) {
  return (
    <div
      data-slot="card-title"
      className={cn("leading-none font-semibold dark:text-gray-100", className)}
      {...props}
    />
  )
}
// Componente para la descripción de la tarjeta (CardDescription)
function CardDescription({ className, ...props }: React.ComponentProps<"div">) {
  return (
    <div
      data-slot="card-description"
      className={cn("text-muted-foreground text-sm dark:text-gray-300", className)}
      {...props}
    />
  )
}
// Componente para el contenido de la tarjeta (CardContent)
function CardContent({ className, ...props }: React.ComponentProps<"div">) {
  return (
    <div
      data-slot="card-content"
      className={cn("px-6 dark:text-gray-100", className)}
      {...props}
    />
  )
}

// Componente para el pie de página de la tarjeta (CardFooter)
function CardFooter({ className, ...props }: React.ComponentProps<"div">) {
  return (
    <div
      data-slot="card-footer"
      className={cn("flex items-center px-6", className)}
      {...props}
    />
  )
}
// Exportación de todos los componentes de la tarjeta
export { Card, CardHeader, CardFooter, CardTitle, CardDescription, CardContent }
