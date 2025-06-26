import { useForm } from '@inertiajs/react';
import { Dialog, DialogContent, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import TelefonosForm from './TelefonosForm';
import { Compania } from '@/types';

interface EditarCompaniaModalProps {
    isOpen: boolean;
    onClose: () => void;
    compania: Compania;
}

export default function EditarCompaniaModal({ isOpen, onClose, compania }: EditarCompaniaModalProps) {
    const { data, setData, put, processing, errors, reset } = useForm<{
        nombre: string;
        url_logo: string;
        telefonos: { telefono: string; descripcion: string }[];
    }>({
        nombre: compania.nombre,
        url_logo: compania.url_logo,
        telefonos: compania.telefonos,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('companias.update', compania.id), {
            onSuccess: () => {
                reset();
                onClose();
            },
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={open => !open && onClose()}>
            <DialogContent>
                <DialogTitle>Editar Compañía</DialogTitle>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <Label htmlFor="nombre">Nombre</Label>
                        <Input id="nombre" value={data.nombre} onChange={e => setData('nombre', e.target.value)} required />
                        {errors.nombre && <p className="text-red-500 text-xs mt-1">{errors.nombre}</p>}
                    </div>
                    <div>
                        <Label htmlFor="url_logo">Logo (URL)</Label>
                        <Input id="url_logo" value={data.url_logo} onChange={e => setData('url_logo', e.target.value)} />
                        {errors.url_logo && <p className="text-red-500 text-xs mt-1">{errors.url_logo}</p>}
                    </div>
                    <div>
                        <Label>Teléfonos</Label>
                        <TelefonosForm telefonos={data.telefonos} setTelefonos={t => setData('telefonos', t)} />
                        {errors.telefonos && <p className="text-red-500 text-xs mt-1">{errors.telefonos}</p>}
                    </div>
                    <div className="flex justify-end gap-2">
                        <Button type="button" variant="secondary" onClick={onClose} disabled={processing}>
                            Cancelar
                        </Button>
                        <Button type="submit" disabled={processing}>
                            Guardar
                        </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    );
}