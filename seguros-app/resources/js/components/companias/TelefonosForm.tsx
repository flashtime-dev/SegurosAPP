import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { useState } from 'react';
import PhoneInputField from '../PhoneInputField';

interface Telefono {
    telefono: string;
    descripcion: string;
}

interface TelefonosFormProps {
    telefonos: Telefono[];
    setTelefonos: (telefonos: Telefono[]) => void;
    errors?: Record<string, string>;
}

export default function TelefonosForm({ telefonos, setTelefonos, errors }: TelefonosFormProps) {
    const [nuevoTelefono, setNuevoTelefono] = useState<Telefono>({ telefono: '', descripcion: '' });

    const handleAdd = () => {
        if (nuevoTelefono.telefono.trim() !== '') {
            setTelefonos([...telefonos, nuevoTelefono]);
            setNuevoTelefono({ telefono: '', descripcion: '' });
        }
    };

    const handleRemove = (idx: number) => {
        setTelefonos(telefonos.filter((_, i) => i !== idx));
    };

    const handleChange = (idx: number, field: keyof Telefono, value: string) => {
        const nuevos = [...telefonos];
        nuevos[idx][field] = value;
        setTelefonos(nuevos);
    };

    return (
        <div className="space-y-2">
            <div className="flex gap-2">
                <div className="flex-1">
                    <Label htmlFor="telefono">Teléfono</Label>
                    <PhoneInputField
                        value={nuevoTelefono.telefono}
                        onChange={value => {
                            if (!value) return setNuevoTelefono({ ...nuevoTelefono, telefono: '' });
                            const cleaned = value.replace(/\s/g, '');
                            setNuevoTelefono({ ...nuevoTelefono, telefono: cleaned.startsWith('+') ? cleaned : `+${cleaned}` });
                        }}
                    />
                </div>
                <div className="flex-1">
                    <Label htmlFor="descripcion">Descripción</Label>
                    <Input id="descripcion" value={nuevoTelefono.descripcion} onChange={e => setNuevoTelefono({ ...nuevoTelefono, descripcion: e.target.value })} placeholder='Descripción'/>
                </div>
                <Button type="button" onClick={handleAdd} className="self-end">Añadir</Button>
            </div>
            <ul className="space-y-1">
                {telefonos.map((tel, idx) => (
                    <li key={idx} className="flex gap-2 items-start">
                        <div className="flex flex-col flex-1">
                            <PhoneInputField
                                value={tel.telefono}
                                onChange={(value) => {
                                    if (!value) return handleChange(idx, 'telefono', '');
                                    const cleaned = value.replace(/\s/g, '');
                                    handleChange(idx, 'telefono', cleaned.startsWith('+') ? cleaned : `+${cleaned}`);
                                }}

                            //error={errors.telefono}
                            />
                            {errors?.[`telefonos.${idx}.telefono`] && (
                                <p className="text-red-500 text-xs ml-1">{errors[`telefonos.${idx}.telefono`]}</p>
                            )}
                        </div>
                        <div className="flex flex-col flex-1">
                            <Input
                                value={tel.descripcion}
                                onChange={e => handleChange(idx, 'descripcion', e.target.value)}
                                className=""
                                placeholder="Descripción"
                            />
                            {errors?.[`telefonos.${idx}.descripcion`] && (
                                <p className="text-red-500 text-xs ml-1">{errors[`telefonos.${idx}.descripcion`]}</p>
                            )}
                        </div>

                        <Button type="button" variant="destructive" onClick={() => handleRemove(idx)}>
                            Eliminar
                        </Button>



                    </li>
                ))}
            </ul>
        </div>
    );
}