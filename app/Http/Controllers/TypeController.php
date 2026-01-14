<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTypeRequest;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::orderBy('sort_order')->orderBy('name')->get();
        
        return view('type.index', compact('types'));
    }

    public function create()
    {
        // Retourne la page complète de création
        return view('type.create');
    }

    public function store(StoreTypeRequest $request)
    {
        try {
            // Préparer les données
            $data = $request->validated();
            
            // Gérer les amenities
            if ($request->has('amenities')) {
                $data['amenities'] = json_encode($request->amenities);
            }
            
            // Valeurs par défaut
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['sort_order'] = $request->sort_order ?? 0;
            
            // Créer le type
            $type = Type::create($data);
            
            // Si requête AJAX, retourner JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type "' . $type->name . '" created successfully!',
                    'type' => $type
                ]);
            }
            
            // Sinon, rediriger avec message de session
            return redirect()->route('type.index')
                ->with('success', 'Type "' . $type->name . '" created successfully!');
            
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating type: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()->with('error', 'Error creating type: ' . $e->getMessage());
        }
    }

    public function edit(Type $type)
    {
        // Retourne la page complète d'édition
        return view('type.edit', compact('type'));
    }

    public function update(StoreTypeRequest $request, Type $type)
    {
        try {
            // Préparer les données
            $data = $request->validated();
            
            // Gérer les amenities
            if ($request->has('amenities')) {
                $data['amenities'] = json_encode($request->amenities);
            } else {
                $data['amenities'] = null;
            }
            
            // Gérer le statut actif
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            
            // Mettre à jour
            $type->update($data);
            
            // Si requête AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type "' . $type->name . '" updated successfully!'
                ]);
            }
            
            // Redirection normale
            return redirect()->route('type.index')
                ->with('success', 'Type "' . $type->name . '" updated successfully!');
            
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating type: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()->with('error', 'Error updating type: ' . $e->getMessage());
        }
    }

    public function destroy(Type $type)
    {
        try {
            // Vérifier si des chambres utilisent ce type
            if ($type->rooms()->exists()) {
                $roomCount = $type->rooms()->count();
                
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete type "' . $type->name . '" because it is used by ' . $roomCount . ' room(s).'
                    ], 400);
                }
                
                return back()->with('error', 'Cannot delete type "' . $type->name . '" because it is used by ' . $roomCount . ' room(s).');
            }
            
            $typeName = $type->name;
            $type->delete();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type "' . $typeName . '" deleted successfully!'
                ]);
            }
            
            return redirect()->route('type.index')
                ->with('success', 'Type "' . $typeName . '" deleted successfully!');
            
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting type: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error deleting type: ' . $e->getMessage());
        }
    }
}