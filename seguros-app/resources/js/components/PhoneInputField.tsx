import React from "react";
import PhoneInput from "react-phone-input-2";
import "react-phone-input-2/lib/style.css";
import InputError from "@/components/input-error";
import { PlaceholderPattern } from "./ui/placeholder-pattern";

type Props = {
    value: string;
    onChange: (value: string) => void;
    error?: string;
    name?: string;
};

export default function PhoneInputField({ value, onChange, error, name = "phone" }: Props) {
    return (
        <div>
            <PhoneInput
                country={"es"} // País por defecto, España
                value={value}
                onChange={(phone) => onChange(phone)}
                inputProps={{
                    name,
                    required: false,
                    autoFocus: false,
                    placeholder: "+34 123 456 789",
                }}
                // Sólo estos países estarán disponibles en el dropdown
                onlyCountries={["es", "us", "fr", "gb", "de", "it", "pt", "mx", "ar", "br"]}

                // Puedes usar preferredCountries para poner algunos arriba en la lista
                preferredCountries={["es", "us"]}
                enableAreaCodes={false} // Permite mostrar y editar códigos de área para países que los tienen
                disableCountryCode={false} // Permite mostrar y editar el código de país
                disableDropdown={false}
                countryCodeEditable={true}
                inputStyle={{ width: "100%" }}
            />
            <InputError message={error} className="mt-2" />
        </div>
    );
}