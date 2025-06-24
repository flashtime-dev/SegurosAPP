<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatPoliza;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Esta clase maneja el evento cuando se envía un mensaje en un chat relacionado con una póliza de seguro
 * Implementa ShouldBroadcastNow para transmitir el evento inmediatamente sin usar colas
 */
class MessageSent implements ShouldBroadcastNow
{
    // Dispatchable: Permite disparar el evento
    // InteractsWithSockets: Proporciona funcionalidad para WebSockets
    // SerializesModels: Permite serializar modelos Eloquent para la transmisión
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ChatPoliza $message)
    {
        // Recibe un modelo ChatPoliza como parámetro
        $this->message = $message;

        // Registra un log informativo cuando se crea el evento
        try {
            Log::info("🎯 Evento MessageSent creado", [
                'message_id' => $this->message->id,
                'poliza_id' => $this->message->id_poliza
            ]);
        } catch (Throwable $e) {
            Log::error("❌ Error en constructor de MessageSent: " . $e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    // Define el canal privado donde se transmitirá el mensaje
    public function broadcastOn(): array
    {
        // El canal se nombra como chatPoliza.{id_poliza}
        $channel = 'chatPoliza.' . $this->message->id_poliza;

        // Registra un log informativo al definir el canal de broadcast
        try {
            Log::info("📺 Broadcasting en canal privado: " . $channel);
            // Usa PrivateChannel para asegurar que solo usuarios autorizados puedan escuchar
            return [
                new PrivateChannel($channel),
            ];
        } catch (Throwable $e) {
            Log::error("❌ Error al definir canal de broadcast: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            return []; // Devuelve un array vacío en caso de error
        }

    }

    // Define los datos que se transmitirán con el evento
    public function broadcastWith(): array
    {
        try {
            // Carga la relación 'usuario' del mensaje usando load()
            $this->message->load('usuario');

            $data = [
                'id' => $this->message->id,
                'id_poliza' => $this->message->id_poliza,
                'id_usuario' => $this->message->id_usuario,
                'mensaje' => $this->message->mensaje,
                'adjunto' => $this->message->adjunto,
                'created_at' => $this->message->created_at,
                'usuario' => $this->message->usuario
            ];

            Log::info("📦 Datos del broadcast:", $data);
            
            return $data;

        } catch (Throwable $e) {
            Log::error("❌ Error al preparar datos para el broadcast: " . $e->getMessage(), [
                'exception' => $e,
                'message_id' => $this->message->id ?? null,
            ]);
            return []; // O puedes lanzar una excepción si prefieres detener el flujo
        }
    }
}
