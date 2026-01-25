<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Menu;
use App\Models\RoomStatus;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    // Page d'accueil du site vitrine
    public function home()
    {
        $featuredRooms = Room::with(['type', 'roomStatus', 'images']) // <-- CORRECTION: 'images' au pluriel
            ->where('room_status_id', 1) // Available
            ->limit(3)
            ->get();
                
        return view('frontend.pages.home', compact('featuredRooms'));
    }

     // Liste des chambres
    public function rooms()
    {
        $rooms = Room::with(['type', 'roomStatus', 'images']) // <-- CORRECTION: 'images' au pluriel
            ->where('room_status_id', 1) // Available
            ->paginate(9);
                
        return view('frontend.pages.rooms', compact('rooms'));
    }

    // Détails d'une chambre
    public function roomDetails($id)
    {
        $room = Room::with(['type', 'roomStatus', 'images', 'facilities']) // <-- CORRECTION: 'images' au pluriel
            ->findOrFail($id);
                        
        $relatedRooms = Room::with(['type', 'roomStatus', 'images']) // <-- CORRECTION: 'images' au pluriel
            ->where('type_id', $room->type_id)
            ->where('id', '!=', $room->id)
            ->where('room_status_id', 1) // Available
            ->limit(3)
            ->get();
                
        return view('frontend.pages.room-details', compact('room', 'relatedRooms'));
    }

    // Restaurant vitrine
    public function restaurant()
    {
        $menus = Menu::all();
        return view('frontend.pages.restaurant', compact('menus'));
    }

    // Services
    public function services()
    {
        return view('frontend.pages.services');
    }

    // Contact
    public function contact()
    {
        return view('frontend.pages.contact');
    }

    // Envoyer message de contact
    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);

        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès !');
    }

    // Dans app/Http\Controllers/FrontendController.php
    public function restaurantReservationStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'persons' => 'required|integer|min:1|max:20',
            'table_type' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        // Ici, vous pouvez sauvegarder la réservation dans la base de données
        // Par exemple :
        // RestaurantReservation::create($validated);
        
        // Pour l'instant, retournez une réponse JSON
        return response()->json([
            'success' => true,
            'message' => 'Réservation envoyée avec succès !'
        ]);
    }

    public function contactSubmit(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'newsletter' => 'nullable|boolean',
        ]);

        try {
            // Ici, vous pouvez :
            // 1. Envoyer un email
            // Mail::to('contact@luxurypalace.com')->send(new ContactFormMail($validated));
            
            // 2. Sauvegarder dans la base de données
            // $contact = \App\Models\ContactMessage::create($validated);
            
            // 3. Retourner avec un message de succès
            return redirect()->back()->with([
                'success' => 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.',
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.',
                'status' => 'error'
            ])->withInput();
        }
    }
}