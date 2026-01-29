<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id)
                  ->where('causer_type', User::class);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 25);
        $activities = $query->paginate($perPage);

        $users = User::orderBy('name')->get();
        $totalActivities = Activity::count();

        // UTILISE activity/index.blade.php
        return view('activity.index', compact('activities', 'users', 'totalActivities'));
    }

    // Nouvelle méthode pour la route /all
    public function all(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id)
                  ->where('causer_type', User::class);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Pas de pagination pour /all - on prend tout
        $activities = $query->get();
        $users = User::orderBy('name')->get();
        $totalActivities = Activity::count();

        // UTILISE activity/all.blade.php
        return view('activity.all', compact('activities', 'users', 'totalActivities'));
    }

    public function show($id)
    {
        $activity = Activity::with(['causer', 'subject'])->findOrFail($id);
        // UTILISE activity/show.blade.php
        return view('activity.show', compact('activity'));
    }

    public function getDetails($id)
    {
        $activity = Activity::with(['causer', 'subject'])->findOrFail($id);

        $eventColor = match($activity->event) {
            'created' => 'success',
            'updated' => 'warning',
            'deleted' => 'danger',
            'restored' => 'info',
            default => 'secondary'
        };

        $eventLabel = match($activity->event) {
            'created' => 'Création',
            'updated' => 'Modification',
            'deleted' => 'Suppression',
            'restored' => 'Restauration',
            default => ucfirst($activity->event)
        };

        return response()->json([
            'id' => $activity->id,
            'created_at' => $activity->created_at->format('d/m/Y H:i:s'),
            'description' => $activity->description,
            'event' => $activity->event,
            'event_label' => $eventLabel,
            'event_color' => $eventColor,
            'log_name' => $activity->log_name,
            'causer_name' => $activity->causer->name ?? null,
            'causer_email' => $activity->causer->email ?? null,
            'subject_type' => $activity->subject_type,
            'subject_id' => $activity->subject_id,
            'properties' => $activity->properties->toArray(),
            'changes' => [
                'old' => $activity->properties->get('old') ?? [],
                'attributes' => $activity->properties->get('attributes') ?? [],
            ],
            'ip_address' => $activity->properties->get('ip_address'),
            'user_agent' => $activity->properties->get('user_agent'),
        ]);
    }

    public function export($format = 'csv')
    {
        $activities = Activity::with('causer')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $activities->map(function($activity) {
            return [
                'ID' => $activity->id,
                'Date' => $activity->created_at->format('Y-m-d H:i:s'),
                'Description' => $activity->description,
                'Événement' => $activity->event,
                'Utilisateur' => $activity->causer->name ?? 'Système',
                'Email' => $activity->causer->email ?? 'N/A',
                'Objet Type' => class_basename($activity->subject_type),
                'Objet ID' => $activity->subject_id,
                'Propriétés' => json_encode($activity->properties->toArray()),
                'IP' => $activity->properties['ip_address'] ?? 'N/A',
            ];
        });

        if ($format === 'json') {
            return response()->json($data, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="activity-logs-' . now()->format('Y-m-d') . '.json"'
            ]);
        }

        // CSV
        $filename = 'activity-logs-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // En-têtes
            if ($data->isNotEmpty()) {
                fputcsv($file, array_keys($data->first()));
            }

            // Données
            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $date = now()->subDays($request->days);
        $deletedCount = Activity::where('created_at', '<', $date)->delete();

        // Log l'action de nettoyage
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'days' => $request->days,
                'deleted_count' => $deletedCount,
                'date_threshold' => $date->format('Y-m-d')
            ])
            ->log('a nettoyé les logs d\'activité');

        // CORRECTION ICI : Utilisez activity.index
        return redirect()->route('activity.index')
            ->with('success', "{$deletedCount} logs plus anciens que {$request->days} jours ont été supprimés.");
    }

    public function statistics()
    {
        $stats = [
            'total' => Activity::count(),
            'today' => Activity::whereDate('created_at', today())->count(),
            'yesterday' => Activity::whereDate('created_at', today()->subDay())->count(),
            'this_week' => Activity::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Activity::whereMonth('created_at', now()->month)->count(),
            'by_event' => Activity::selectRaw('event, COUNT(*) as count')
                ->groupBy('event')
                ->pluck('count', 'event'),
            'by_user' => Activity::selectRaw('causer_id, COUNT(*) as count')
                ->whereNotNull('causer_id')
                ->groupBy('causer_id')
                ->with('causer')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'by_model' => Activity::selectRaw('subject_type, COUNT(*) as count')
                ->groupBy('subject_type')
                ->orderBy('count', 'desc')
                ->get(),
        ];

        // UTILISE activity/statistics.blade.php
        return view('activity.statistics', compact('stats'));
    }
}