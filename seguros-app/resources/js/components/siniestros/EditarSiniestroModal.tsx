import React, { useEffect, useRef } from "react";
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
import { Poliza, Siniestro } from "@/types";

type Props = {
    isOpen: boolean;
    onClose: () => void;
    polizas: Poliza[];
    siniestro: any; // El siniestro completo que vamos a editar, incluye contactos, id_poliza, etc.
};

type FormData = {
    _method: "PUT",
    id_poliza: string;
    declaracion: string;
    tramitador: string;
    expediente: string;
    exp_cia: string;
    exp_asist: string;
    fecha_ocurrencia: string;
    files: File[]; // Ahora el arreglo se llama `files`, tal como el backend espera
    contactos: { nombre: string; cargo: string; piso: string; telefono: string }[];
};

export default function EditarSiniestroModal({ isOpen, onClose, polizas, siniestro }: Props) {
    const { data, setData, post, processing, errors, reset } = useForm<FormData>({
        _method: "PUT", // Importante: agregar este campo para simular PUT
        id_poliza: "",
        declaracion: "",
        tramitador: "",
        expediente: "",
        exp_cia: "",
        exp_asist: "",
        fecha_ocurrencia: "",
        files: [],       // inicializamos empty array
        contactos: [],   // inicializamos vacío y luego lo llenamos en useEffect
    });

    // dentro de tu componente:
    const wasOpenedRef = useRef(false);
    useEffect(() => {
        if (siniestro) {
            // Formateamos la fecha a "yyyy-MM-dd" para el input type="date"
            const formatFecha = (fecha: string) => {
                if (!fecha) return "";
                const date = new Date(fecha);
                return date.toISOString().split("T")[0];
            };

            setData({
                _method: "PUT", // Mantener el método
                id_poliza: String(siniestro.id_poliza),
                declaracion: siniestro.declaracion || "",
                tramitador: siniestro.tramitador || "",
                expediente: siniestro.expediente || "",
                exp_cia: siniestro.exp_cia || "",
                exp_asist: siniestro.exp_asist || "",
                fecha_ocurrencia: formatFecha(siniestro.fecha_ocurrencia),
                files: [], // siempre empezamos vacío; si el usuario sube nuevos archivos, reemplazaremos
                contactos: siniestro.contactos
                    ? siniestro.contactos.map((c: any) => ({
                        nombre: c.nombre || "",
                        cargo: c.cargo || "",
                        piso: c.piso || "",
                        telefono: c.telefono || "",
                    }))
                    : [],
            });

        }
        if (!isOpen) {
            reset();
            wasOpenedRef.current = false;
        }
    }, [siniestro]);

    const agregarContacto = () => {
        setData("contactos", [
            ...data.contactos,
            { nombre: "", cargo: "", piso: "", telefono: "" },
        ]);
    };

    const actualizarContacto = (
        index: number,
        campo: keyof FormData["contactos"][number],
        valor: string
    ) => {
        const nuevosContactos = [...data.contactos];
        nuevosContactos[index] = { ...nuevosContactos[index], [campo]: valor };
        setData("contactos", nuevosContactos);
    };

    const eliminarContacto = (index: number) => {
        setData(
            "contactos",
            data.contactos.filter((_, i) => i !== index)
        );
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!siniestro) return;

        post(route("siniestros.update", siniestro.id), {
            // Al hacer put, Inertia enviará data.files como archivos
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
                    <DialogTitle>Editar Siniestro</DialogTitle>
                    <DialogDescription>
                        Modifica los campos para actualizar el siniestro.
                    </DialogDescription>
                </DialogHeader>

                <ScrollArea className="max-h-[70vh]">
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="grid gap-4">
                            {/* Póliza */}
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

                            {/* Declaración */}
                            <div>
                                <Label htmlFor="declaracion">Declaración</Label>
                                <Input
                                    id="declaracion"
                                    value={data.declaracion}
                                    onChange={(e) => {
                                        const value = e.target.value;
                                        setData(
                                            "declaracion",
                                            value.charAt(0).toUpperCase() + value.slice(1)
                                        );
                                    }}
                                    required
                                    placeholder="Daños por agua en baño principal"
                                />
                                <InputError message={errors.declaracion} />
                            </div>

                            {/* Tramitador */}
                            <div>
                                <Label htmlFor="tramitador">Tramitador</Label>
                                <Input
                                    id="tramitador"
                                    value={data.tramitador}
                                    onChange={(e) => {
                                        const value = e.target.value;
                                        setData(
                                            "tramitador",
                                            value.charAt(0).toUpperCase() + value.slice(1)
                                        );
                                    }}
                                    placeholder="Nombre del tramitador"
                                />
                                <InputError message={errors.tramitador} />
                            </div>

                            {/* Expediente / Exp_CIA */}
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="expediente">Expediente</Label>
                                    <Input
                                        id="expediente"
                                        value={data.expediente}
                                        onChange={(e) => setData("expediente", e.target.value)}
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
                                        onChange={(e) => setData("exp_cia", e.target.value)}
                                        placeholder="CIA-2025-COM-0012045"
                                    />
                                    <InputError message={errors.exp_cia} />
                                </div>
                            </div>

                            {/* Expediente Asistencia */}
                            <div>
                                <Label htmlFor="exp_asist">Expediente Asistencia</Label>
                                <Input
                                    id="exp_asist"
                                    value={data.exp_asist}
                                    onChange={(e) => setData("exp_asist", e.target.value)}
                                    placeholder="AST-2025-001"
                                />
                                <InputError message={errors.exp_asist} />
                            </div>

                            {/* Fecha de Ocurrencia */}
                            <div>
                                <Label htmlFor="fecha_ocurrencia">Fecha de Ocurrencia</Label>
                                <Input
                                    id="fecha_ocurrencia"
                                    type="date"
                                    value={data.fecha_ocurrencia}
                                    onChange={(e) =>
                                        setData("fecha_ocurrencia", e.target.value)
                                    }
                                />
                                <InputError message={errors.fecha_ocurrencia} />
                            </div>

                            {/* Archivos (files) */}
                            <div>
                                <Label htmlFor="files">Adjunto</Label>
                                <Input
                                    id="files"
                                    type="file"
                                    multiple
                                    onChange={(e) => {
                                        const archivos = e.target.files
                                            ? Array.from(e.target.files)
                                            : [];
                                        setData("files", archivos);
                                    }}
                                />
                                <InputError message={errors.files} />
                                {/* Si quieres mostrar el error de cada archivo, podrías descomentar:
                                    <InputError message={errors["files.*"]} />
                                */}
                            </div>

                            {/* Contactos */}
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
                                                    onChange={(e) =>
                                                        actualizarContacto(
                                                            index,
                                                            "nombre",
                                                            e.target.value.charAt(0).toUpperCase() +
                                                            e.target.value.slice(1)
                                                        )
                                                    }
                                                    required
                                                    placeholder="Nombre del contacto"
                                                />
                                                <InputError message={(errors as Record<string, string>)[`contactos.${index}.nombre`]} />
                                            </div>
                                            <div>
                                                <Label htmlFor={`cargo-${index}`}>Cargo</Label>
                                                <Input
                                                    id={`cargo-${index}`}
                                                    value={contacto.cargo}
                                                    onChange={(e) =>
                                                        actualizarContacto(
                                                            index,
                                                            "cargo",
                                                            e.target.value.charAt(0).toUpperCase() +
                                                            e.target.value.slice(1)
                                                        )
                                                    }
                                                    placeholder="Gerente de Siniestros"
                                                />
                                            </div>
                                            <div>
                                                <Label htmlFor={`piso-${index}`}>Piso</Label>
                                                <Input
                                                    id={`piso-${index}`}
                                                    value={contacto.piso}
                                                    onChange={(e) =>
                                                        actualizarContacto(
                                                            index,
                                                            "piso",
                                                            e.target.value.charAt(0).toUpperCase() +
                                                            e.target.value.slice(1)
                                                        )
                                                    }
                                                    placeholder="Piso 3"
                                                />
                                            </div>
                                            <div>
                                                <Label htmlFor="telefono">Teléfono</Label>
                                                <PhoneInputField
                                                    value={contacto.telefono}
                                                    onChange={(e) =>
                                                        actualizarContacto(
                                                            index,
                                                            "telefono",
                                                            e.target.value
                                                        )
                                                    }
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
