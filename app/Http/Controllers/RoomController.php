<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\Type;
use App\Repositories\Interface\ImageRepositoryInterface;
use App\Repositories\Interface\RoomRepositoryInterface;
use App\Repositories\Interface\RoomStatusRepositoryInterface;
use App\Repositories\Interface\TypeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function __construct(
        private RoomRepositoryInterface $roomRepository,
        private TypeRepositoryInterface $typeRepository,
        private RoomStatusRepositoryInterface $roomStatusRepositoryInterface
    ) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->roomRepository->getRoomsDatatable($request);
        }

        $types = $this->typeRepository->getTypeList($request);
        $roomStatuses = $this->roomStatusRepositoryInterface->getRoomStatusList($request);
        
        // Récupérer toutes les chambres avec leurs relations
        $rooms = Room::with(['type', 'roomStatus'])->paginate(10);

        return view('room.index', [
            'rooms' => $rooms,
            'types' => $types,
            'roomStatuses' => $roomStatuses,
        ]);
    }
    
    public function create()
    {
        $types = Type::all();
        $roomstatuses = RoomStatus::all();
        
        // Retourner directement la vue HTML
        return view('room.create', [
            'types' => $types,
            'roomstatuses' => $roomstatuses,
        ]);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|exists:types,id',
            'room_status_id' => 'required|exists:room_statuses,id',
            'number' => 'required|string|max:10|unique:rooms,number',
            'name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:10',
            'price' => 'required|numeric|min:0',
            'view' => 'nullable|string|max:500',
        ], [
            'type_id.required' => 'Please select a room type',
            'room_status_id.required' => 'Please select a room status',
            'number.required' => 'Room number is required',
            'number.unique' => 'This room number already exists',
            'capacity.required' => 'Capacity is required',
            'price.required' => 'Price is required',
        ]);

        // Si validation échoue
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Préparer les données
        $data = $validator->validated();
        
        // S'assurer que view n'est pas null
        if (empty($data['view'])) {
            $data['view'] = '';
        }
        
        // S'assurer que name est null si vide
        if (empty($data['name'])) {
            $data['name'] = null;
        }

        // Créer la chambre
        $room = Room::create($data);

        // Redirection vers la liste avec message de succès
        return redirect()->route('room.index')
            ->with('success', 'Room ' . $room->number . ' created successfully!');
    }

    public function show(Room $room)
    {
        $customer = [];
        $transaction = Transaction::where([
            ['check_in', '<=', Carbon::now()],
            ['check_out', '>=', Carbon::now()],
            ['room_id', $room->id]
        ])->first();
        
        if (!empty($transaction)) {
            $customer = $transaction->customer;
        }

        return view('room.show', [
            'customer' => $customer,
            'room' => $room,
        ]);
    }

    public function edit(Room $room)
    {
        $types = Type::all();
        $roomstatuses = RoomStatus::all();
        
        return view('room.edit', [
            'room' => $room,
            'types' => $types,
            'roomstatuses' => $roomstatuses,
        ]);
    }

    public function update(Request $request, Room $room)
    {
        // Validation pour l'update
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|exists:types,id',
            'room_status_id' => 'required|exists:room_statuses,id',
            'number' => 'required|string|max:10|unique:rooms,number,' . $room->id,
            'name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:10',
            'price' => 'required|numeric|min:0',
            'view' => 'nullable|string|max:500',
        ], [
            'type_id.required' => 'Please select a room type',
            'room_status_id.required' => 'Please select a room status',
            'number.required' => 'Room number is required',
            'number.unique' => 'This room number already exists',
            'capacity.required' => 'Capacity is required',
            'price.required' => 'Price is required',
        ]);

        // Si validation échoue
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Préparer les données
        $data = $validator->validated();
        
        // S'assurer que view n'est pas null
        if (empty($data['view'])) {
            $data['view'] = '';
        }
        
        // S'assurer que name est null si vide
        if (empty($data['name'])) {
            $data['name'] = null;
        }

        // Mettre à jour la chambre
        $room->update($data);

        return redirect()->route('room.index')
            ->with('success', 'Room ' . $room->number . ' updated successfully!');
    }

    public function destroy(Room $room, ImageRepositoryInterface $imageRepository)
    {
        try {
            $room->delete();

            // Supprimer les images associées
            $path = 'img/room/' . $room->number;
            $path = public_path($path);

            if (is_dir($path)) {
                $imageRepository->destroy($path);
            }

            // Redirection après suppression
            return redirect()->route('room.index')
                ->with('success', 'Room ' . $room->number . ' deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('room.index')
                ->with('failed', 'Room ' . $room->number . ' cannot be deleted!');
        }
    }
}