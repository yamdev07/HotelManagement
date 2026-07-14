<?php

namespace App\Http\Requests;

use App\Support\TenantManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('room')) ?? false;
    }

    public function rules(): array
    {
        $roomId = $this->route('room')?->id;

        // Numéro unique PAR hôtel (hors la chambre en cours d'édition).
        $hotelId = app(TenantManager::class)->getHotelId();
        $scope = fn ($q) => $hotelId ? $q->where('hotel_id', $hotelId) : $q;
        $uniqueNumber = Rule::unique('rooms', 'number')->ignore($roomId)->where($scope);
        $typeExists = Rule::exists('types', 'id')->where($scope);

        return [
            'type_id'        => ['required', $typeExists],
            'room_status_id' => 'required|exists:room_statuses,id',
            'number'         => ['required', 'string', 'max:10', $uniqueNumber],
            'name'           => 'nullable|string|max:255',
            'capacity'       => 'required|integer|min:1|max:10',
            'price'          => 'required|numeric|min:0',
            'view'           => 'nullable|string|max:500',
        ];
    }
}
