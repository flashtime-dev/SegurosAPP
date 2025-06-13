import { CheckCircle, XCircle, Info, Zap } from "lucide-react";

export default function CustomToast({ type, message }: { type: 'success' | 'error' | 'info'; message: string }) {
    const icons = {
        success: <CheckCircle className="w-5 h-5 text-green-600" />,
        error: <XCircle className="w-5 h-5 text-red-600" />,
        info: <Info className="w-5 h-5 text-yellow-600" />,
    };

    const bgColors = {
        success: 'bg-green-100',
        error: 'bg-red-100',
        info: 'bg-yellow-100',
    };

    const textColors = {
        success: 'text-green-800',
        error: 'text-red-800',
        info: 'text-yellow-800',
    };
    

    return (
        <div className={`flex items-center gap-2 p-3 rounded-md border ${bgColors[type]} ${textColors[type]} border-current`}>
            {icons[type]}
            <span className="font-medium">{message}</span>
        </div>
    );
}
