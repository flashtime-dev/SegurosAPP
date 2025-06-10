import { CheckCircle, XCircle, Info, Zap } from "lucide-react";

export default function CustomToast({ type, message }: { type: 'success' | 'error' | 'info' | 'oculto'; message: string }) {
    const icons = {
        success: <CheckCircle className="w-5 h-5 text-green-600" />,
        error: <XCircle className="w-5 h-5 text-red-600" />,
        info: <Info className="w-5 h-5 text-yellow-600" />,
        //oculto: <Zap className="w-6 h-6 text-[#FFEA00] drop-shadow-[0_0_5px_rgb(255,235,50)]" />, // Icono rayo con glow
        oculto: <span className="text-yellow-400 text-xl drop-shadow-[0_0_1px_rgb(255,234,0)]">⚡</span>,
    };

    const bgColors = {
        success: 'bg-green-100',
        error: 'bg-red-100',
        info: 'bg-yellow-100',
        oculto: 'bg-red-700',
    };

    const textColors = {
        success: 'text-green-800',
        error: 'text-red-800',
        info: 'text-yellow-800',
        oculto: 'text-[#FFEA00] font-bold tracking-wide',
    };
    

    return (
        <div className={`flex items-center gap-2 p-3 rounded-md border ${bgColors[type]} ${textColors[type]} border-current`}>
            {icons[type]}
            <span className="font-medium">{message}</span>
            {type === 'oculto' && icons.oculto /* Rayo al final solo en oculto */}
        </div>
    );
}
