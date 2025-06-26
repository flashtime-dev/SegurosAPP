import { useForm } from '@inertiajs/react';
import { Dialog, DialogContent,DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import TelefonosForm from './TelefonosForm';
interface CrearCompaniaModalProps {
    isOpen: boolean;
    onClose: () => void;
}

export default function CrearCompaniaModal({ isOpen, onClose }: CrearCompaniaModalProps) {
    const { data, setData, post, processing, errors, reset } = useForm<{
        nombre: string;
        url_logo: string;
        telefonos: { telefono: string; descripcion: string }[];
    }>({
        nombre: '',
        url_logo: '',
        telefonos: [],
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('companias.store'), {
            onSuccess: () => {
                reset();
                onClose();
            },
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={open => !open && onClose()}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Crear Compañía</DialogTitle>
                    <DialogDescription>Completa los campos para crear una nueva compañía.</DialogDescription>
                </DialogHeader>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <Label htmlFor="nombre">Nombre *</Label>
                        <Input id="nombre" value={data.nombre} onChange={e => setData('nombre', e.target.value)} required />
                        {errors.nombre && <p className="text-red-500 text-xs mt-1">{errors.nombre}</p>}
                    </div>
                    <div>
                        <Label htmlFor="url_logo">Logo (URL) *</Label>
                        <Input id="url_logo" value={data.url_logo} onChange={e => setData('url_logo', e.target.value)} required/>
                        {errors.url_logo && <p className="text-red-500 text-xs mt-1">{errors.url_logo}</p>}
                    </div>
                    <div>
                        <Label>Teléfonos</Label>
                        <TelefonosForm telefonos={data.telefonos} setTelefonos={t => setData('telefonos', t)} errors={errors}/>
                        {/* {errors.telefonos && <p className="text-red-500 text-xs mt-1">{errors.telefonos}</p>} */}
                    </div>
                    <div className="flex justify-end gap-2">
                        <Button type="button" variant="secondary" onClick={onClose} disabled={processing}>
                            Cancelar
                        </Button>
                        <Button type="submit" disabled={processing}>
                            Crear
                        </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    );
}