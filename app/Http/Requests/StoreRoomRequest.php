<?php

namespace App\Http\Requests;

use App\Support\TenantManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Room::class) ?? false;
    }

    public function rules(): array
    {
        // Numéro de chambre unique PAR hôtel (deux hôtels peuvent avoir une chambre "101").
        $hotelId = app(TenantManager::class)->getHotelId();
        $scope = fn ($q) => $hotelId ? $q->where('hotel_id', $hotelId) : $q;
        $uniqueNumber = Rule::unique('rooms', 'number')->where($scope);
        // Le type référencé doit appartenir à CET hôtel.
        $typeExists = Rule::exists('types', 'id')->where($scope);

        return [
            'type_id' => ['required', $typeExists],
            'room_status_id' => 'required|exists:room_statuses,id',
            'number' => ['required', 'string', 'max:10', $uniqueNumber],
            'name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:10',
            'price' => 'required|numeric|min:0',
            'view' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'type_id.required' => 'Please select a room type',
            'room_status_id.required' => 'Please select a room status',
            'number.required' => 'Room number is required',
            'number.unique' => 'This room number already exists',
            'capacity.required' => 'Capacity is required',
            'price.required' => 'Price is required',
        ];
    }
}
