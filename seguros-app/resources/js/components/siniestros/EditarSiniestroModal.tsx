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
import PhoneInputField from "@/components/PhoneInputField";

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
    expediente: string;
    exp_cia: string;
    exp_asist: string;
    fecha_ocurrencia: string;
    files: File[]; // Se llama `files` el backend espera ese nombre
    contactos: { nombre: string; cargo: string; piso: string; telefono: string }[];
};

export default function EditarSiniestroModal({ isOpen, onClose, polizas, siniestro }: Props) {
    //Datos de form
    const { data, setData, post, processing, errors, reset } = useForm<FormData>({
        _method: "PUT", // Importante: agregar este campo para simular PUT
        id_poliza: "",
        declaracion: "",
        expediente: "",
        exp_cia: "",
        exp_asist: "",
        fecha_ocurrencia: "",
        files: [],
        contactos: [],
    });

    const wasOpenedRef = useRef(false);

    //Cargar datos iniciales
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
                expediente: siniestro.expediente || "",
                exp_cia: siniestro.exp_cia || "",
                exp_asist: siniestro.exp_asist || "",
                fecha_ocurrencia: formatFecha(siniestro.fecha_ocurrencia),
                files: [], // siempre empezamos vacío; si el usuario sube nuevos archivos, reemplazaremos
                contactos: siniestro.contactos
                    //Mapear datos de contactos
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

    //Agragar contacto
    const agregarContacto = () => {
        setData("contactos", [
            ...data.contactos,
            { nombre: "", cargo: "", piso: "", telefono: "" },
        ]);
    };

    //Actualizar contacto
    // Actualizar contacto existente
    const actualizarContacto = (index: number, campo: string, valor: string) => {
        const nuevosContactos = [...data.contactos];
        nuevosContactos[index] = { ...nuevosContactos[index], [campo]: valor };
        setData("contactos", nuevosContactos);
    };

    //Eliminar contacto
    const eliminarContacto = (index: number) => {
        setData(
            "contactos",
            data.contactos.filter((_, i) => i !== index)
        );
    };

    //Enviar formulario
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
                                <Label htmlFor="id_poliza">Póliza *</Label>
                                <Select
                                    onValueChange={(value) => setData("id_poliza", value)}
                                    value={data.id_poliza}
                                    required
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selecciona una póliza" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {/* Lista de polizas */}
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
                                <Label htmlFor="declaracion">Declaración *</Label>
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

                            {/* Expediente / Exp_CIA */}
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="expediente">Expediente *</Label>
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
                                    className="cursor-pointer"
                                    value={data.fecha_ocurrencia}
                                    onChange={(e) =>
                                        setData("fecha_ocurrencia", e.target.value)
                                    }
                                    style={{ colorScheme: 'dark' }}
                                />
                                <InputError message={errors.fecha_ocurrencia} />
                            </div>

                            {/* Archivos Adjuntos */}
                            <div>
                                <Label htmlFor="files">Adjunto</Label>

                                {/* Input oculto */}
                                <Input
                                    id="files"
                                    type="file"
                                    multiple
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    className="hidden"
                                    onChange={e => {
                                        if (e.target.files) {
                                            setData('files', Array.from(e.target.files));
                                        } else {
                                            setData('files', []);
                                        }
                                    }}
                                />

                                {/* Botón estilizado */}
                                <label htmlFor="files">
                                    <div
                                        className="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 border-input file:text-foreground placeholder:text-muted-foreground
                                            selection:bg-primary selection:text-primary-foreground flex h-auto w-full min-w-0 flex-col space-y-1 whitespace-normal rounded-md
                                            border bg-transparent px-3 py-2 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0
                                            file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm
                                            focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40
                                            aria-invalid:border-destructive"
                                    >
                                        {data.files && data.files.length > 0
                                            ? data.files.map(file => file.name).join(', ')
                                            : "Ningún archivo seleccionado"}
                                    </div>
                                </label>

                                {/* Visualización de errores */}
                                {Object.entries(errors)
                                    .filter(([key]) => key.startsWith('files.'))
                                    .map(([key, message], index) => {
                                        // Obtener el índice del archivo del key (files.0, files.1, etc.)
                                        const fileIndex = parseInt(key.split('.')[1]);
                                        // Obtener el nombre del archivo si existe
                                        const fileName = data.files[fileIndex]?.name;

                                        return (
                                            <InputError
                                                key={index}
                                                message={fileName ? `${fileName}: ${message}` : message}
                                                className="mt-2"
                                            />
                                        );
                                    })
                                }
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
                                                <Label htmlFor={`nombre-${index}`}>Nombre *</Label>
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
                                                <InputError message={(errors as Record<string, string>)[`contactos.${index}.cargo`]} />
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
                                                <InputError message={(errors as Record<string, string>)[`contactos.${index}.piso`]} />
                                            </div>
                                            <div>
                                                <Label htmlFor="telefono">Teléfono *</Label>
                                                <PhoneInputField
                                                    value={contacto.telefono}
                                                    onChange={(value) => {
                                                        if (!value) {
                                                            actualizarContacto(index, 'telefono', '');
                                                            return;
                                                        }
                                                        const cleaned = value.replace(/\s/g, '');
                                                        const normalized = cleaned === '' ? '' : (cleaned.startsWith('+') ? cleaned : `+${cleaned}`);
                                                        actualizarContacto(index, 'telefono', normalized);
                                                    }}
                                                    require={true}
                                                    error={(errors as Record<string, string>)[`contactos.${index}.telefono`]}
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
