<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\FloorPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
class FloorPlanController extends Controller
{
    /**
     * Middleware : accessible uniquement aux admins et super admins
     */
    // Les routes sont déjà protégées par ['auth', 'checkrole:Super,Admin'] dans web.php
 
    /**
     * Affiche l'éditeur du plan de salle
     */
    public function index()
    {
        $rooms = Room::orderBy('order')->get();

        // Définir les ensembles par défaut (tables + chaises + bar)
        $defaultElements = [];
        $x = 40; $y = 40; $dx = 120; $dy = 120;
        // 4 ensembles table ronde + 4 chaises
        for ($i = 0; $i < 4; $i++) {
            $tableId = uniqid('t4_');
            $defaultElements[] = [
                'id' => $tableId,
                'type' => 't-r4',
                'x' => $x + ($i % 2) * $dx,
                'y' => $y + intdiv($i, 2) * $dy,
                'rot' => 0,
                'label' => 'T4-' . ($i+1),
            ];
            // Placer 4 chaises autour
            for ($j = 0; $j < 4; $j++) {
                $angle = deg2rad(90 * $j);
                $cx = $defaultElements[count($defaultElements)-1]['x'] + 40 * cos($angle);
                $cy = $defaultElements[count($defaultElements)-1]['y'] + 40 * sin($angle);
                $defaultElements[] = [
                    'id' => uniqid('c4_'),
                    'type' => 'c-std',
                    'x' => $cx,
                    'y' => $cy,
                    'rot' => 90 * $j,
                    'label' => '',
                ];
            }
        }
        // 2 ensembles table rectangulaire + 6 chaises
        for ($i = 0; $i < 2; $i++) {
            $tableId = uniqid('t6_');
            $tx = $x + 2 * $dx + ($i * $dx);
            $ty = $y;
            $defaultElements[] = [
                'id' => $tableId,
                'type' => 't-q6',
                'x' => $tx,
                'y' => $ty,
                'rot' => 0,
                'label' => 'T6-' . ($i+1),
            ];
            // Placer 3 chaises de chaque côté
            for ($j = 0; $j < 3; $j++) {
                $cx1 = $tx - 40;
                $cy1 = $ty - 30 + $j * 30;
                $cx2 = $tx + 40;
                $cy2 = $ty - 30 + $j * 30;
                $defaultElements[] = [
                    'id' => uniqid('c6a_'),
                    'type' => 'c-std',
                    'x' => $cx1,
                    'y' => $cy1,
                    'rot' => 180,
                    'label' => '',
                ];
                $defaultElements[] = [
                    'id' => uniqid('c6b_'),
                    'type' => 'c-std',
                    'x' => $cx2,
                    'y' => $cy2,
                    'rot' => 0,
                    'label' => '',
                ];
            }
        }
        // Bar (comptoir)
        $defaultElements[] = [
            'id' => uniqid('bar_'),
            'type' => 't-bar',
            'x' => $x,
            'y' => $y + 2 * $dy + 40,
            'rot' => 0,
            'label' => 'Bar',
        ];

        $roomData = [];
        foreach ($rooms as $room) {
            $plan = FloorPlan::where('room_id', $room->id)->first();
            if ($plan && !empty($plan->layout)) {
                $roomData[$room->id] = $plan->layout;
            } else {
                // Injecter les ensembles par défaut si le plan est vide
                $roomData[$room->id] = $defaultElements;
            }
        }

        return view('admin.floor-plan', compact('rooms', 'roomData'));
    }
 
    /**
     * Enregistre le plan de salle (AJAX)
     */
    public function save(Request $request)
    {
        $request->validate([
            'room_data' => 'required|array',
        ]);
 
        foreach ($request->room_data as $roomId => $layout) {
            // Clé de recherche : room_id uniquement (unique par salle)
            // Le cast 'array' du modèle gère le json_encode automatiquement
            FloorPlan::updateOrCreate(
                ['room_id' => $roomId],
                [
                    'layout'     => $layout,
                    'updated_by' => Auth::id(),
                ]
            );
        }
 
        return response()->json([
            'success' => true,
            'message' => 'Plan enregistré avec succès !',
        ]);
    }
}
