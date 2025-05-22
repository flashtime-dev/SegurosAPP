import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
    DialogClose,
    DialogDescription,
} from "@/components/ui/dialog";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Poliza } from "@/types";

type Props = {
    isOpen: boolean;
    onClose: () => void;
    polizas: Poliza[];
    polizaSeleccionada?: string;
};

export default function CrearSiniestroModal({ isOpen, onClose, polizas, polizaSeleccionada }: Props) {
    type FormData = {
        id_poliza: string;
        declaracion: string;
        tramitador: string;
        expediente: string;
        exp_cia: string;
        exp_asist: string;
        fecha_ocurrencia: string;
        adjunto: File | null;
        contactos: { nombre: string; cargo: string; piso: string; telefono: string }[];
    };

    const { data, setData, post, processing, errors, reset } = useForm<FormData>({
        id_poliza: polizaSeleccionada || '',
        declaracion: '',
        tramitador: '',
        expediente: '',
        exp_cia: '',
        exp_asist: '',
        fecha_ocurrencia: '',
        adjunto: null,
        contactos: []
    });

    useEffect(() => {
        if (polizaSeleccionada) {
            setData('id_poliza', polizaSeleccionada);
        }
    }, [polizaSeleccionada]);

    const agregarContacto = () => {
        setData('contactos', [...data.contactos, { nombre: '', cargo: '', piso: '', telefono: '' }]);
    };

    const actualizarContacto = (index: number, campo: string, valor: string) => {
        const nuevosContactos = [...data.contactos];
        nuevosContactos[index] = { ...nuevosContactos[index], [campo]: valor };
        setData('contactos', nuevosContactos);
    };

    const eliminarContacto = (index: number) => {
        setData('contactos', data.contactos.filter((_, i) => i !== index));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route("siniestros.store"), {
            onSuccess: () => {
                reset(); // Limpia el formulario
                onClose(); // Cierra el modal
            },
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Nuevo Siniestro</DialogTitle>
                    <DialogDescription>
                        Completa los campos para crear un nuevo siniestro.
                    </DialogDescription>
                </DialogHeader>

                <ScrollArea className="max-h-[70vh]">
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="grid gap-4">
                            <div>
                                <Label htmlFor="id_poliza">Póliza</Label>
                                <Select
                                    onValueChange={(value) => setData("id_poliza", value)}
                                    defaultValue={data.id_poliza}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selecciona una póliza" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {polizas.map((poliza) => (
                                            <SelectItem key={poliza.id} value={String(poliza.id)}>
                                                {poliza.numero}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError message={errors.id_poliza} />
                            </div>

                            <div>
                                <Label htmlFor="declaracion">Declaración</Label>
                                <Input
                                    id="declaracion"
                                    value={data.declaracion}
                                    onChange={e => setData('declaracion', e.target.value)}
                                />
                                <InputError message={errors.declaracion} />
                            </div>

                            <div>
                                <Label htmlFor="tramitador">Tramitador</Label>
                                <Input
                                    id="tramitador"
                                    value={data.tramitador}
                                    onChange={e => setData('tramitador', e.target.value)}
                                />
                                <InputError message={errors.tramitador} />
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="expediente">Expediente</Label>
                                    <Input
                                        id="expediente"
                                        value={data.expediente}
                                        onChange={e => setData('expediente', e.target.value)}
                                    />
                                    <InputError message={errors.expediente} />
                                </div>

                                <div>
                                    <Label htmlFor="exp_cia">Expediente CIA</Label>
                                    <Input
                                        id="exp_cia"
                                        value={data.exp_cia}
                                        onChange={e => setData('exp_cia', e.target.value)}
                                    />
                                    <InputError message={errors.exp_cia} />
                                </div>
                            </div>

                            <div>
                                <Label htmlFor="exp_asist">Expediente Asistencia</Label>
                                <Input
                                    id="exp_asist"
                                    value={data.exp_asist}
                                    onChange={e => setData('exp_asist', e.target.value)}
                                />
                                <InputError message={errors.exp_asist} />
                            </div>

                            <div>
                                <Label htmlFor="fecha_ocurrencia">Fecha de Ocurrencia</Label>
                                <Input
                                    id="fecha_ocurrencia"
                                    type="date"
                                    value={data.fecha_ocurrencia}
                                    onChange={e => setData('fecha_ocurrencia', e.target.value)}
                                />
                                <InputError message={errors.fecha_ocurrencia} />
                            </div>

                            <div>
                                <Label htmlFor="adjunto">Adjunto</Label>
                                <Input
                                    id="adjunto"
                                    type="file"
                                    onChange={e => setData('adjunto', e.target.files?.[0] || null)}
                                />
                                <InputError message={errors.adjunto} />
                            </div>

                            <div className="space-y-4">
                                <div className="flex justify-between items-center">
                                    <Label>Contactos</Label>
                                    <Button type="button" variant="outline" onClick={agregarContacto}>
                                        Agregar Contacto
                                    </Button>
                                </div>

                                {data.contactos.map((contacto, index) => (
                                    <div key={index} className="border p-4 rounded-lg space-y-4">
                                        <div className="flex justify-end">
                                            <Button
                                                type="button"
                                                variant="destructive"
                                                size="sm"
                                                onClick={() => eliminarContacto(index)}
                                            >
                                                Eliminar
                                            </Button>
                                        </div>
                                        <div className="grid grid-cols-2 gap-4">
                                            <div>
                                                <Label htmlFor={`nombre-${index}`}>Nombre</Label>
                                                <Input
                                                    id={`nombre-${index}`}
                                                    value={contacto.nombre}
                                                    onChange={e => actualizarContacto(index, 'nombre', e.target.value)}
                                                />
                                            </div>
                                            <div>
                                                <Label htmlFor={`cargo-${index}`}>Cargo</Label>
                                                <Input
                                                    id={`cargo-${index}`}
                                                    value={contacto.cargo}
                                                    onChange={e => actualizarContacto(index, 'cargo', e.target.value)}
                                                />
                                            </div>
                                            <div>
                                                <Label htmlFor={`piso-${index}`}>Piso</Label>
                                                <Input
                                                    id={`piso-${index}`}
                                                    value={contacto.piso}
                                                    onChange={e => actualizarContacto(index, 'piso', e.target.value)}
                                                />
                                            </div>
                                            <div>
                                                <Label htmlFor={`telefono-${index}`}>Teléfono</Label>
                                                <Input
                                                    id={`telefono-${index}`}
                                                    value={contacto.telefono}
                                                    onChange={e => actualizarContacto(index, 'telefono', e.target.value)}
                                                />
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <DialogFooter>
                            <DialogClose asChild>
                                <Button type="button" variant="outline" onClick={onClose}>
                                    Cancelar
                                </Button>
                            </DialogClose>
                            <Button type="submit" disabled={processing}>
                                {processing ? "Creando..." : "Crear"}
                            </Button>
                        </DialogFooter>
                    </form>
                </ScrollArea>
            </DialogContent>
        </Dialog>
    );
}