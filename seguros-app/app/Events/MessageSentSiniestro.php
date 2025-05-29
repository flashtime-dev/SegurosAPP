<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatSiniestro;
use Illuminate\Support\Facades\Log;

class MessageSentSiniestro implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public ChatSiniestro $message)
    {
        Log::info("🎯 Evento MessageSent creado", [
            'message_id' => $this->message->id,
            'siniestro_id' => $this->message->id_siniestro
        ]);
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
        Log::info("📺 Broadcasting en canal privado: " . $channel);

        return [
            new PrivateChannel($channel), // Canal público en lugar de PrivateChannel
        ];
    }


    public function broadcastWith(): array
    {
        $this->message->load('usuario');
        
        $data = [
            'id' => $this->message->id,
            'id_siniestro' => $this->message->id_poliza,
            'id_usuario' => $this->message->id_usuario,
            'mensaje' => $this->message->mensaje,
            'adjunto' => $this->message->adjunto,
            'created_at' => $this->message->created_at,
            'usuario' => $this->message->usuario
        ];

        Log::info("📦 Datos del broadcast:", $data);
        return $data;
    }
}
