<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatPoliza;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public ChatPoliza $message)
    {
        Log::info("🎯 Evento MessageSent creado", [
            'message_id' => $this->message->id,
            'poliza_id' => $this->message->id_poliza
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
        $channel = 'chatPoliza.' . $this->message->id_poliza;
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
            'id_poliza' => $this->message->id_poliza,
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
