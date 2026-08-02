<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Services\AssistantService;
use App\Support\TenantManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Point d'entrée du chat de l'assistant IA de la vitrine.
 * L'hôtel est résolu par slug (et devient le tenant courant) ; l'assistant
 * ne répond qu'avec les données de cet hôtel.
 */
class AssistantController extends Controller
{
    public function __construct(private AssistantService $assistant) {}

    public function chat(Request $request, string $slug): JsonResponse
    {
        $hotel = Hotel::where('slug', $slug)->firstOrFail();
        app(TenantManager::class)->setHotelId($hotel->id);

        abort_unless($hotel->hasActiveAccess(), 503);
        abort_unless(($hotel->show_assistant ?? true) && $this->assistant->isConfigured(), 404);

        $data = $request->validate([
            'messages' => ['required', 'array', 'min:1', 'max:20'],
            'messages.*.role' => ['required', 'string', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:1000'],
        ]);

        $result = $this->assistant->reply($hotel, $data['messages']);

        return response()->json([
            'ok' => $result['ok'],
            'reply' => $result['reply'],
        ]);
    }
}
