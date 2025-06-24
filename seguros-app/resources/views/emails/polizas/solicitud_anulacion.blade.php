<x-mail::message>
# Solicitud de Anulación de Póliza

Se ha solicitado la anulación de la siguiente póliza:

- **Alias:** {{ $poliza->alias ?? 'N/A' }}
- **Número:** {{ $poliza->numero ?? 'N/A' }}
- **Compañía:** {{ $poliza->compania->nombre ?? 'N/A' }}
- **Comunidad:** {{ $poliza->comunidad->nombre ?? 'N/A' }}
- **Fecha de Efecto:** {{ $poliza->fecha_efecto->format('d/m/Y') ?? 'N/A' }}
- **Estado:** {{ $poliza->estado }}

<x-mail::button :url="route('polizas.show', $poliza->id)">
Ver Póliza
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>