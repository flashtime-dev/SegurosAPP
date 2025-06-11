<?php

namespace App\Http\Controllers;

use App\Models\ChatSiniestro;
use App\Events\MessageSentSiniestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Http\Middleware\CheckPermiso;

// Controlador para manejar los chats de siniestros
class ChatSiniestroController extends Controller
{
    public function __construct()
    {
        // Aplica middleware de permisos a métodos específicos
        $this->middleware(CheckPermiso::class . ':chats_siniestros.crear', ['only' => ['store']]);
    }
    /**
     * Método para almacenar un nuevo mensaje en el chat de un siniestro.
     */
    public function store(Request $request, $idSiniestro)
    {
        try {
            Log::info("📤 Recibida petición de chat de siniestro", [
                'siniestro_id' => $idSiniestro,
                'user_id' => Auth::id(),
                'mensaje' => $request->mensaje
            ]);

            // Validar los datos de entrada
            $request->validate([
                'mensaje' => 'required|string|max:1000',
                'adjunto' => 'nullable|boolean',
            ]);

            // Crear el mensaje del chat de siniestro
            $chat = ChatSiniestro::create([
                'id_siniestro' => $idSiniestro,
                'id_usuario' => Auth::id(), // Usuario autenticado
                'mensaje' => $request->mensaje,
                'adjunto' => $request->adjunto ?? false,
            ]);

            // Cargar el usuario para incluirlo en la respuesta
            $chat->load('usuario');

            Log::info("💾 Chat creado", [
                'chat_id' => $chat->id,
                'chat_data' => $chat->toArray()
            ]);

            // Enviar el mensaje a otros usuarios conectados
            // Esto asume que tienes configurado broadcasting en tu aplicación
            // y que el evento MessageSentSiniestro está correctamente configurado
            Log::info("📡 Enviando broadcast");
            broadcast(new MessageSentSiniestro($chat))->toOthers();
            Log::info("📡 Broadcast enviado");

            return response()->json(['success' => true, 'message' => 'Mensaje enviado correctamente.', 'chat' => $chat]);
        } catch (Throwable $e) {
            Log::error('❌ Error al guardar el mensaje del chat de siniestro:', [
                'exception' => $e,
                'siniestro_id' => $idSiniestro,
                'user_id' => Auth::id(),
                'mensaje' => $request->mensaje ?? null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el mensaje.',
            ], 500);
        }
    }

}
