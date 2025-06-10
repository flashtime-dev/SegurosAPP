import { useEffect } from "react";
import { toast } from "sonner";
import CustomToast from "@/components/ui/CustomToaster";

const useKonamiCode = () => {
    useEffect(() => {
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

        const onKeyDown = (e: KeyboardEvent) => {
            if (e.key === konamiCode[index]) {
                index++;
                if (index === konamiCode.length) {
                    toast.custom(() => (
                        <CustomToast type="oculto" message="¡Aplicación creada por Cristy!" />
                    ));
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

        const resetGesture = () => {
            phases = [];
            lastY = 0;
            phaseStartY = 0;
        };

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

        const onTouchEnd = () => {
            // Comprobar patrón: down -> up -> down (3 fases)
            if (
                phases.length === 3 &&
                phases[0] === "down" &&
                phases[1] === "up" &&
                phases[2] === "down"
            ) {
                toast.custom(() => (
                    <CustomToast type="oculto" message="¡Aplicación creada por Cristy!" />
                ));
            }
            resetGesture();
        };

        window.addEventListener("keydown", onKeyDown);
        window.addEventListener("touchstart", onTouchStart);
        window.addEventListener("touchmove", onTouchMove);
        window.addEventListener("touchend", onTouchEnd);

        return () => {
            window.removeEventListener("keydown", onKeyDown);
            window.removeEventListener("touchstart", onTouchStart);
            window.removeEventListener("touchmove", onTouchMove);
            window.removeEventListener("touchend", onTouchEnd);
        };
    }, []);
};

export default useKonamiCode;
