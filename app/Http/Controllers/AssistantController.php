<?php

namespace App\Http\Controllers;

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
        ]);
    }
}
