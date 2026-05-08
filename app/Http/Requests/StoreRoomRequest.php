<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Room::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'type_id'        => 'required|exists:types,id',
            'room_status_id' => 'required|exists:room_statuses,id',
            'number'         => 'required|string|max:10|unique:rooms,number',
            'name'           => 'nullable|string|max:255',
            'capacity'       => 'required|integer|min:1|max:10',
            'price'          => 'required|numeric|min:0',
            'view'           => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'type_id.required'        => 'Please select a room type',
            'room_status_id.required' => 'Please select a room status',
            'number.required'         => 'Room number is required',
            'number.unique'           => 'This room number already exists',
            'capacity.required'       => 'Capacity is required',
            'price.required'          => 'Price is required',
        ];
    }
}
