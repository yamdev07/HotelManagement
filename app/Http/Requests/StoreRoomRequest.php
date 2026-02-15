<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Récupérer l'ID de la chambre si elle existe (pour l'update)
        $roomId = $this->route('room') ? $this->route('room')->id : null;

        return [
            'type_id' => 'required|exists:types,id',
            'room_status_id' => 'required|exists:room_statuses,id',
            'number' => 'required|string|max:10|unique:rooms,number,'.$roomId,
            'capacity' => 'required|integer|min:1|max:10',
            'price' => 'required|numeric|min:0',
            'view' => 'nullable|string|max:500',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'type_id.required' => 'Please select a room type',
            'type_id.exists' => 'Selected room type is invalid',
            'room_status_id.required' => 'Please select a room status',
            'room_status_id.exists' => 'Selected room status is invalid',
            'number.required' => 'Room number is required',
            'number.unique' => 'This room number already exists',
            'capacity.required' => 'Capacity is required',
            'capacity.integer' => 'Capacity must be a whole number',
            'capacity.min' => 'Capacity must be at least 1',
            'capacity.max' => 'Capacity cannot exceed 10',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price cannot be negative',
            'view.max' => 'View description cannot exceed 500 characters',
        ];
    }
}
