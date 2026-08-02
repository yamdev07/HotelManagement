<?php

namespace App\Http\Controllers;

use App\Services\AssistantActions;
use App\Services\AssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Chat de l'assistant IA du back-office (app de gestion).
 * Réservé aux utilisateurs connectés ; l'assistant répond à partir des données
 * de l'hôtel de l'utilisateur.
 */
class AssistantController extends Controller
{
    public function __construct(private AssistantService $assistant) {}

    public function chat(Request $request): JsonResponse
    {
        abort_unless($this->assistant->isConfigured(), 404);

        $data = $request->validate([
            'messages' => ['required', 'array', 'min:1', 'max:20'],
            'messages.*.role' => ['required', 'string', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:1000'],
        ]);

        $result = $this->assistant->reply($request->user(), $data['messages']);

        return response()->json([
            'ok' => $result['ok'],
            'reply' => $result['reply'],
            'pending' => $result['pending'] ?? null,
        ]);
    }

    /** Transcrit un message vocal (Groq Whisper) et renvoie le texte. */
    public function transcribe(Request $request): JsonResponse
    {
        abort_unless($this->assistant->isConfigured(), 404);

        $request->validate([
            'audio' => ['required', 'file', 'max:25600'], // 25 Mo max
        ]);

        $file = $request->file('audio');
        $result = $this->assistant->transcribe($file->get(), $file->getClientOriginalName() ?: 'audio.webm');

        return response()->json([
            'ok' => $result['ok'],
            'text' => $result['text'],
        ]);
    }

    /**
     * Exécute une action confirmée par l'utilisateur (après proposition de l'IA).
     * La sécurité repose ici : chaque action revérifie les permissions.
     */
    public function execute(Request $request, AssistantActions $actions): JsonResponse
    {
        abort_unless($this->assistant->isConfigured(), 404);

        $data = $request->validate([
            'tool' => ['required', 'string', 'max:60'],
            'args' => ['array'],
        ]);

        // Seules les actions d'écriture connues passent par ici.
        abort_unless($actions->isWrite($data['tool']), 422);

        [$ok, $message] = $actions->execute($request->user(), $data['tool'], $data['args'] ?? []);

        return response()->json(['ok' => $ok, 'message' => $message]);
    }
}
