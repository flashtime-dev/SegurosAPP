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
import { Compania, Comunidad, Agente } from "@/types";

type Props = {
    isOpen: boolean;
    onClose: () => void;
    companias: Compania[];
    comunidades: Comunidad[];
    agentes: Agente[];
    poliza?: any; 
};

export default function EditarPolizaModal({ isOpen, onClose, companias, comunidades, agentes, poliza }: Props & { poliza?: any }) {
    // Uso del hook useForm para manejar el estado del formulario
    const { data, setData, post, processing, errors, reset } = useForm({
        _method: 'PUT', // Definir el método HTTP como PUT, ya que estamos actualizando una póliza
        id_compania: '',
        id_comunidad: '',
        id_agente: '',
        alias: '',
        numero: '',
        fecha_efecto: '',
        cuenta: '',
        forma_pago: '',
        prima_neta: '',
        prima_total: '',
        pdf_poliza: null as File | null, // Para manejar el archivo PDF de la póliza (si se adjunta uno nuevo)
        observaciones: '',
        estado: '',
    });
    // useEffect que se ejecuta cada vez que `poliza` cambia, para rellenar el formulario con los datos existentes.
    useEffect(() => {
        if (poliza) {
            const formatFecha = (fecha: string) => {
                const date = new Date(fecha);
                return date.toISOString().split('T')[0]; // Convierte a "yyyy-MM-dd"
            };
            // Actualizar el estado del formulario con los datos de la póliza
            setData({
                _method: 'PUT', // Mantener el método
                id_compania: String(poliza.id_compania) || '',
                id_comunidad: String(poliza.id_comunidad) || '',
                id_agente: String(poliza.id_agente) || '',
                alias: poliza.alias || '',
                numero: poliza.numero || '',
                fecha_efecto: formatFecha(poliza.fecha_efecto) || '',
                cuenta: poliza.cuenta || '',
                forma_pago: poliza.forma_pago || '',
                prima_neta: poliza.prima_neta || '',
                prima_total: poliza.prima_total || '',
                pdf_poliza: null,
                observaciones: poliza.observaciones || '',
                estado: poliza.estado || '',
            });
        }
    }, [poliza, companias, comunidades, agentes]); // Dependencias: cuando cambian estos datos, actualizamos el estado
    // Función que maneja el envío del formulario
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (poliza) {
            // Usar POST en lugar de PUT para manejar archivos correctamente
            post(route('polizas.update', poliza.id), {
                forceFormData: true, // Forzar el uso de FormData para manejar el archivo PDF correctamente
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
                    <DialogTitle>Editar Póliza</DialogTitle>
                    <DialogDescription>Modifica los campos para actualizar la póliza.</DialogDescription>
                </DialogHeader>

                {/* ScrollArea para el contenido del formulario */}
                <ScrollArea className="max-h-[70vh]">
                    <form onSubmit={handleSubmit} className="space-y-4">
                        {/* Compañía */}
                        <div>
                            <Label htmlFor="id_compania">Compañía *</Label>
                            <Select onValueChange={(value) => setData('id_compania', value)} value={data.id_compania} required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona una compañía" />
                                </SelectTrigger>
                                <SelectContent>
                                    {companias.map((compania) => (
                                        <SelectItem key={compania.id} value={String(compania.id)}>
                                            {compania.nombre}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <InputError message={errors.id_compania} className="mt-2" />
                        </div>

                        {/* Comunidad */}
                        <div>
                            <Label htmlFor="id_comunidad">Comunidad *</Label>
                            <Select onValueChange={(value) => setData('id_comunidad', value)} value={data.id_comunidad} required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona una comunidad" />
                                </SelectTrigger>
                                <SelectContent>
                                    {comunidades.map((comunidad) => (
                                        <SelectItem key={comunidad.id} value={String(comunidad.id)}>
                                            {comunidad.nombre}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <InputError message={errors.id_comunidad} className="mt-2" />
                        </div>

                        {/* Agente */}
                        <div>
                            <Label htmlFor="id_agente">Agente</Label>
                            <Select onValueChange={(value) => setData('id_agente', value)} value={data.id_agente}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona un agente" />
                                </SelectTrigger>
                                <SelectContent>
                                    {agentes.map((agente) => (
                                        <SelectItem key={agente.id} value={String(agente.id)}>
                                            {agente.nombre}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <InputError message={errors.id_agente} className="mt-2" />
                        </div>

                        {/* Alias */}
                        <div>
                            <Label htmlFor="alias">Alias</Label>
                            <Input
                                id="alias"
                                value={data.alias}
                                onChange={(e) => {
                                    const value = e.target.value;
                                    setData('alias', value.charAt(0).toUpperCase() + value.slice(1));
                                }}
                                disabled={processing}
                                placeholder="Alias del Cliente"
                            />
                            <InputError message={errors.alias} className="mt-2" />
                        </div>

                        {/* Número de póliza */}
                        <div>
                            <Label htmlFor="numero">Número de Póliza *</Label>
                            <Input
                                id="numero"
                                value={data.numero}
                                onChange={(e) => setData('numero', e.target.value)}
                                disabled={processing}
                                placeholder="123456789012345678901"
                                required
                            />
                            <InputError message={errors.numero} className="mt-2" />
                        </div>

                        {/* Fecha de efecto */}
                        <div>
                            <Label htmlFor="fecha_efecto">Fecha de Efecto *</Label>
                            <Input
                                id="fecha_efecto"
                                type="date"
                                className="cursor-pointer"
                                value={data.fecha_efecto}
                                onChange={(e) => setData('fecha_efecto', e.target.value)}
                                style={{ colorScheme: 'dark' }}
                                disabled={processing}
                                required
                            />
                            <InputError message={errors.fecha_efecto} className="mt-2" />
                        </div>

                        {/* Cuenta */}
                        <div>
                            <Label htmlFor="cuenta">Cuenta</Label>
                            <Input
                                id="cuenta"
                                value={data.cuenta}
                                onChange={(e) => setData('cuenta', e.target.value)}
                                disabled={processing}
                                placeholder="1234 5678 90 1234567890"
                            />
                            <InputError message={errors.cuenta} className="mt-2" />
                        </div>

                        {/* Forma de pago */}
                        <div>
                            <Label htmlFor="forma_pago">Forma de Pago *</Label>
                            <Select onValueChange={(value) => setData('forma_pago', value)} value={data.forma_pago} required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona una forma de pago" />
                                </SelectTrigger>
                                <SelectContent>
                                    {['Bianual', 'Anual', 'Semestral', 'Trimestral', 'Mensual'].map((forma) => (
                                        <SelectItem key={forma} value={forma}>
                                            {forma}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <InputError message={errors.forma_pago} className="mt-2" />
                        </div>

                        {/* Prima neta */}
                        <div>
                            <Label htmlFor="prima_neta">Prima Neta *</Label>
                            <Input
                                id="prima_neta"
                                type="number"
                                step="0.01"
                                value={data.prima_neta}
                                onChange={(e) => setData('prima_neta', e.target.value)}
                                disabled={processing}
                                required
                                placeholder="0.00"
                            />
                            <InputError message={errors.prima_neta} className="mt-2" />
                        </div>

                        {/* Prima total */}
                        <div>
                            <Label htmlFor="prima_total">Prima Total *</Label>
                            <Input
                                id="prima_total"
                                type="number"
                                step="0.01"
                                value={data.prima_total}
                                onChange={(e) => setData('prima_total', e.target.value)}
                                disabled={processing}
                                required
                                placeholder="0.00"
                            />
                            <InputError message={errors.prima_total} className="mt-2" />
                        </div>

                        {/* PDF de la póliza */}
                        <div>
                            <Label htmlFor="pdf_poliza">PDF de la Póliza</Label>
                            <Input
                                id="pdf_poliza"
                                type="file"
                                accept=".pdf" // Solo permite archivos PDF
                                className="hidden"
                                onChange={(e) => setData('pdf_poliza', e.target.files?.[0] || null)} // Solo selecciona un archivo
                                disabled={processing}
                            />
                            {/* Botón personalizado que abre el selector de archivo */}
                            <label htmlFor="pdf_poliza">
                                <div className="border-input file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive flex h-auto w-full min-w-0 cursor-pointer flex-col space-y-1 rounded-md border bg-transparent px-3 py-2 text-base whitespace-normal shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium hover:bg-gray-100 focus-visible:ring-[3px] disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm dark:hover:bg-gray-800">
                                    {data.pdf_poliza?.name || 'Ningún archivo seleccionado'}

                                    {/* Esta linea muestra los archivos que tiene la poliza si es que hay */}
                                    {/* {data.pdf_poliza?.name || poliza?.pdf_poliza?.split("/").pop()?.replace(/^\d+_/, "") || "Ningún archivo seleccionado"} */}
                                </div>
                            </label>
                            <InputError message={errors.pdf_poliza} className="mt-2" />
                        </div>

                        {/* Observaciones */}
                        <div>
                            <Label htmlFor="observaciones">Observaciones</Label>
                            <textarea
                                id="observaciones"
                                value={data.observaciones}
                                onChange={(e) => setData('observaciones', e.target.value)}
                                disabled={processing}
                                className="w-full rounded-md border p-2"
                                placeholder="Escribe tus observaciones sobre la póliza aquí..."
                            />
                            <InputError message={errors.observaciones} className="mt-2" />
                        </div>

                        {/* Estado */}
                        <div>
                            <Label htmlFor="estado">Estado *</Label>
                            <Select onValueChange={(value) => setData('estado', value)} value={data.estado} required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecciona un estado" />
                                </SelectTrigger>
                                <SelectContent>
                                    {['En Vigor', 'Anulada', 'Solicitada', 'Externa', 'Vencida'].map((estado) => (
                                        <SelectItem key={estado} value={estado}>
                                            {estado}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <InputError message={errors.estado} className="mt-2" />
                        </div>

                        <DialogFooter>
                            <DialogClose asChild>
                                <Button type="button" variant="outline" onClick={onClose}>
                                    Cancelar
                                </Button>
                            </DialogClose>
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Guardando...' : 'Guardar Cambios'}
                            </Button>
                        </DialogFooter>
                    </form>
                </ScrollArea>
            </DialogContent>
        </Dialog>
    );
}