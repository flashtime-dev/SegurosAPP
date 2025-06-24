import React, { useState, useEffect } from "react";
import { Dialog, DialogContent, DialogTitle, DialogDescription } from "@/components/ui/dialog";
import { motion, AnimatePresence } from "framer-motion";

/**
 * Hook personalizado para detectar el código Konami y gestos táctiles
 * Muestra un modal cuando se activa cualquiera de los dos
 */
const useKonamiCode = () => {
    // Estado para controlar la visibilidad del modal
    const [showModal, setShowModal] = useState(false);

    useEffect(() => {
        // Secuencia de teclas para el código Konami
        const konamiCode = [
            "ArrowUp",
            "ArrowUp",
            "ArrowDown",
            "ArrowDown",
            "ArrowLeft",
            "ArrowRight",
            "ArrowLeft",
            "ArrowRight",
        ];

        let index = 0;

        // Manejador de eventos de teclado
        const onKeyDown = (e: KeyboardEvent) => {
            if (e.key === konamiCode[index]) {
                index++;
                if (index === konamiCode.length) {
                    setShowModal(true);
                    index = 0;
                }
            } else {
                index = 0;
            }
        };

        // Variables para gesto "rayo" con un solo swipe
        let lastY = 0;
        let phases: ("down" | "up")[] = [];
        let phaseStartY = 0;
        const MIN_SWIPE_DISTANCE = 30;

        // Resetear el estado del gesto
        const resetGesture = () => {
            phases = [];
            lastY = 0;
            phaseStartY = 0;
        };

        // Manejadores de eventos táctiles
        const onTouchStart = (e: TouchEvent) => {
            if (e.touches.length === 1) {
                lastY = e.touches[0].clientY;
                phaseStartY = lastY;
                phases = [];
            }
        };

        const onTouchMove = (e: TouchEvent) => {
            if (e.touches.length !== 1) return;

            const currentY = e.touches[0].clientY;
            const deltaY = currentY - lastY;

            // Ignorar movimientos muy pequeños
            if (Math.abs(deltaY) < 5) return;

            // Determinar dirección actual
            const currentDirection = deltaY > 0 ? "down" : "up";

            // Si no hay fases registradas, iniciar
            if (phases.length === 0) {
                phases.push(currentDirection);
                phaseStartY = lastY;
            } else {
                const lastPhase = phases[phases.length - 1];
                if (currentDirection !== lastPhase) {
                    // Cambio de dirección detectado, registrar fase si tiene distancia suficiente
                    const phaseDistance = Math.abs(currentY - phaseStartY);
                    if (phaseDistance > MIN_SWIPE_DISTANCE) {
                        phases.push(currentDirection);
                        phaseStartY = currentY;
                    }
                }
            }

            lastY = currentY;
        };

        // Verificar si el gesto está completo
        const onTouchEnd = () => {
            if (
                phases.length === 3 &&
                phases[0] === "down" &&
                phases[1] === "up" &&
                phases[2] === "down"
            ) {
                setShowModal(true);
            }
            resetGesture();
        };

        // Registrar event listeners
        window.addEventListener("keydown", onKeyDown);
        window.addEventListener("touchstart", onTouchStart);
        window.addEventListener("touchmove", onTouchMove);
        window.addEventListener("touchend", onTouchEnd);

        // Limpiar event listeners al desmontar
        return () => {
            window.removeEventListener("keydown", onKeyDown);
            window.removeEventListener("touchstart", onTouchStart);
            window.removeEventListener("touchmove", onTouchMove);
            window.removeEventListener("touchend", onTouchEnd);
        };
    }, []);

    return { showModal, setShowModal };
};

// Componente del Rayo
const Lightning = () => (
    <motion.div
        initial={{ opacity: 0, scale: 0 }}
        animate={{ opacity: 1, scale: 1 }}
        exit={{ opacity: 0, scale: 0 }}
        transition={{ duration: 0.3 }}
        className="relative"
    >
        {/* Círculo blanco con sombra */}
        <div className="w-32 h-32 rounded-full bg-stone-100 shadow-[0_0_15px_rgba(205,205,205,0.5)] flex items-center justify-center">
            {/* Rayo más grande que sobresale del círculo */}
            <motion.div
                className="w-40 h-40 text-yellow-500 absolute rotate-[12deg]"
                animate={{
                    scale: [0.95, 1.05, 0.95],
                }}
                transition={{
                    duration: 1.5,
                    repeat: Infinity,
                }}
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    stroke="black"     // Añadimos el contorno negro
                    strokeWidth="0.5"  // Grosor del contorno
                    className="drop-shadow-[0_0_8px_rgba(234,179,8,0.5)]"
                >
                    {/* Rayo con puntas más pronunciadas */}
                    <path d="M13 3l-1.5 7h4c0.3 0 0.5 0.2 0.4 0.5l-4.4 10c-0.2 0.4-0.8 0.2-0.7-0.3L12 13H8c-0.3 0-0.5-0.2-0.4-0.5l4.4-9.2C12.2 2.9 13 2.8 13 3z" />
                </svg>
            </motion.div>
        </div>
    </motion.div>
);

// Componente del Modal
export const MensajeOcultoModal = () => {
    const { showModal, setShowModal } = useKonamiCode();

    // Referencia para manejar el foco inicial
    const initialRef = React.useRef<HTMLDivElement>(null);

    return (
        <Dialog open={showModal} onOpenChange={setShowModal}>
            <DialogContent
                className="sm:max-w-md overflow-hidden !border-0 !outline-none bg-slate-100 p-2
                [&>button]:!ring-0 [&>button]:!outline-none [&>button]:border-none [&>button]:focus:!outline-none [&>button]:focus:!ring-0"
                ref={initialRef}
                onOpenAutoFocus={(e) => {
                    e.preventDefault();
                    initialRef.current?.focus();
                }}
            >
                {/* Títulos ocultos para accesibilidad */}
                <DialogTitle className="sr-only">
                    Mensaje secreto
                </DialogTitle>
                <DialogDescription className="sr-only">
                    Modal con mensaje easter egg de creador
                </DialogDescription>
                {/* Contenedor principal con gradiente */}
                <div
                    className="relative flex flex-col items-center justify-center space-y-6 p-8 bg-gradient-to-br from-red-600 to-red-700">
                    <Lightning />
                    {/* Contenedor de texto animado */}
                    <motion.div className="space-y-2">
                        <motion.h2
                            initial={{ y: -20 }}
                            animate={{ y: 0 }}
                            className="text-3xl font-bold text-white text-center drop-shadow-[0_2px_2px_rgba(0,0,0,0.3)]"
                        >
                            ⚡ ¡Flash-Time! ⚡
                        </motion.h2>
                        <motion.p
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            className="text-center text-white/90 font-medium drop-shadow-[0_1px_1px_rgba(0,0,0,0.3)]"
                        >
                            ¡Aplicación creada por Cristy!
                        </motion.p>
                    </motion.div>
                </div>
            </DialogContent>
        </Dialog>
    );
};

export default useKonamiCode;
