<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatSiniestro;
use Illuminate\Support\Facades\Log;
use Throwable;

class MessageSentSiniestro implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public ChatSiniestro $message)
    {
        try {
            Log::info("🎯 Evento MessageSentSiniestro creado", [
                'message_id' => $this->message->id,
                'siniestro_id' => $this->message->id_siniestro
            ]);
        } catch (Throwable $e) {
            Log::error("❌ Error en constructor de MessageSentSiniestro: " . $e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    // En MessageSent.php - SOLO PARA PRUEBAS
    public function broadcastOn(): array
    {
        $channel = 'chatSiniestro.' . $this->message->id_siniestro;
        try {
            Log::info("📺 Broadcasting en canal privado: " . $channel);
        } catch (Throwable $e) {
            Log::error("❌ Error al definir canal de broadcast: " . $e->getMessage(), [
                'exception' => $e,
            ]);
        }

        return [
            new PrivateChannel($channel),
        ];
    }


    public function broadcastWith(): array
    {
        try {
            $this->message->load('usuario');
        
            $data = [
                'id' => $this->message->id,
                'id_siniestro' => $this->message->id_siniestro,
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

            return [];
        }
    }
}
