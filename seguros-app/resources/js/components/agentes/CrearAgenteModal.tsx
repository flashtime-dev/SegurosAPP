import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogClose, DialogDescription } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';
import PhoneInputField from "@/components/PhoneInputField";
import { useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

export default function CrearAgenteModal({ isOpen, onClose }: { isOpen: boolean, onClose: () => void }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        nombre: '',
        email: '',
        telefono: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('agentes.store'), {
            onSuccess: () => {
                reset();
                onClose();
            },
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Nuevo Agente</DialogTitle>
                    <DialogDescription>Completa la información para registrar un nuevo agente.</DialogDescription>
                </DialogHeader>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <Label htmlFor="nombre">Nombre *</Label>
                        <Input
                            id="nombre"
                            value={data.nombre}
                            onChange={(e) => {
                                const value = e.target.value;
                                setData('nombre', value.charAt(0).toUpperCase() + value.slice(1));
                            }}
                            disabled={processing}
                            required
                            placeholder="Nombre completo"
                        />
                        <InputError message={errors.nombre} className="mt-2" />
                    </div>
                    <div>
                        <Label htmlFor="email">Email *</Label>
                        <Input
                            id="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            disabled={processing}
                            required
                            placeholder="email@example.com"
                        />
                        <InputError message={errors.email} className="mt-2" />
                    </div>
                    <div>
                        <Label htmlFor="telefono">Teléfono</Label>
                        <PhoneInputField
                            value={data.telefono}
                            onChange={(value) => {
                                if (!value) return setData('telefono', '');
                                const cleaned = value.replace(/\s/g, '');
                                setData('telefono', cleaned.startsWith('+') ? cleaned : `+${cleaned}`);
                            }}
                            error={errors.telefono}
                        />
                    </div>
                    <DialogFooter>
                        <DialogClose asChild>
                            <Button type="button" variant="outline">
                                Cancelar
                            </Button>
                        </DialogClose>
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Guardando...' : 'Guardar'}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
