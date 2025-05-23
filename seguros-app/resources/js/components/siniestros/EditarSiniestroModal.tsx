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
    siniestro: any;
};

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

export default function EditarSiniestroModal({ isOpen, onClose, polizas, siniestro }: Props) {
    const { data, setData, put, processing, errors, reset } = useForm<FormData>({
        id_poliza: '',
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
        if (siniestro) {
            const formatFecha = (fecha: string) => {
                const date = new Date(fecha);
                return date.toISOString().split("T")[0]; // Convierte a "yyyy-MM-dd"
            };
            setData({
                id_poliza: String(siniestro.id_poliza),
                declaracion: siniestro.declaracion,
                tramitador: siniestro.tramitador,
                expediente: siniestro.expediente,
                exp_cia: siniestro.exp_cia,
                exp_asist: siniestro.exp_asist,
                fecha_ocurrencia: formatFecha(siniestro.fecha_ocurrencia),
                adjunto: null,
                contactos: siniestro.contactos || []
            });
        } else {
            reset();
        }
    }, [polizas, siniestro]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (siniestro) {
            put(route("siniestros.update", siniestro.id), {
                onSuccess: () => {
                    reset();
                    onClose();
                },
            });
        }
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Editar Siniestro</DialogTitle>
                    <DialogDescription>
                        Modifica los campos para actualizar el siniestro.
                    </DialogDescription>
                </DialogHeader>


                {/* ScrollArea para el contenido del formulario */}
                <ScrollArea className="max-h-[70vh]">
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="grid gap-4">
                            <div>
                                <Label htmlFor="id_poliza">Póliza</Label>
                                <Select
                                    onValueChange={(value) => setData("id_poliza", value)}
                                    value={data.id_poliza}
                                    required
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
                                    onChange={(e) => {
                                        const value = e.target.value;
                                        setData('declaracion', value.charAt(0).toUpperCase() + value.slice(1));
                                    }}
                                    required
                                    placeholder="Daños por aguan en baño principal"
                                />
                                <InputError message={errors.declaracion} />
                            </div>

                            <div>
                                <Label htmlFor="tramitador">Tramitador</Label>
                                <Input
                                    id="tramitador"
                                    value={data.tramitador}
                                    onChange={(e) => {
                                        const value = e.target.value;
                                        setData('tramitador', value.charAt(0).toUpperCase() + value.slice(1));
                                    }}
                                    placeholder="Nombre del tramitador"
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
                                        required
                                        placeholder="SIN-2025-001"
                                    />
                                    <InputError message={errors.expediente} />
                                </div>

                                <div>
                                    <Label htmlFor="exp_cia">Expediente CIA</Label>
                                    <Input
                                        id="exp_cia"
                                        value={data.exp_cia}
                                        onChange={e => setData('exp_cia', e.target.value)}
                                        placeholder="CIA-2025-COM-0012045"
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
                                    placeholder="AST-2025-001"
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
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() => setData('contactos', [...data.contactos, { nombre: '', cargo: '', piso: '', telefono: '' }])}
                                    >
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
                                                onClick={() => {
                                                    const nuevosContactos = [...data.contactos];
                                                    nuevosContactos.splice(index, 1);
                                                    setData('contactos', nuevosContactos);
                                                }}
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
                                                    onChange={e => {
                                                        const nuevosContactos = [...data.contactos];
                                                        nuevosContactos[index] = { ...contacto, nombre: e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1) };
                                                        setData('contactos', nuevosContactos);
                                                    }}
                                                    required
                                                    placeholder="Nombre del contacto"
                                                />
                                            </div>
                                            <div>
                                                <Label htmlFor={`cargo-${index}`}>Cargo</Label>
                                                <Input
                                                    id={`cargo-${index}`}
                                                    value={contacto.cargo}
                                                    onChange={e => {
                                                        const nuevosContactos = [...data.contactos];
                                                        nuevosContactos[index] = { ...contacto, cargo: e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1) };
                                                        setData('contactos', nuevosContactos);
                                                    }}
                                                    placeholder="Gerente de Siniestros"
                                                />
                                            </div>
                                            <div>
                                                <Label htmlFor={`piso-${index}`}>Piso</Label>
                                                <Input
                                                    id={`piso-${index}`}
                                                    value={contacto.piso}
                                                    onChange={e => {
                                                        const nuevosContactos = [...data.contactos];
                                                        nuevosContactos[index] = { ...contacto, piso: e.target.value.charAt(0).toUpperCase() + e.target.value.slice(1) };
                                                        setData('contactos', nuevosContactos);
                                                    }}
                                                    placeholder="Piso 3"
                                                />
                                            </div>
                                            <div>
                                                <Label htmlFor={`telefono-${index}`}>Teléfono</Label>
                                                <Input
                                                    id={`telefono-${index}`}
                                                    value={contacto.telefono}
                                                    onChange={e => {
                                                        const nuevosContactos = [...data.contactos];
                                                        nuevosContactos[index] = { ...contacto, telefono: e.target.value };
                                                        setData('contactos', nuevosContactos);
                                                    }}
                                                    required
                                                    placeholder="+34 123 456 789"
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
                                {processing ? "Guardando..." : "Guardar Cambios"}
                            </Button>
                        </DialogFooter>
                    </form>
                </ScrollArea>
            </DialogContent>
        </Dialog>
    );
}