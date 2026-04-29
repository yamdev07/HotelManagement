<?php

namespace App\Http\Requests;

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

        return [
            'type_id'        => 'required|exists:types,id',
            'room_status_id' => 'required|exists:room_statuses,id',
            'number'         => ['required', 'string', 'max:10', Rule::unique('rooms', 'number')->ignore($roomId)],
            'name'           => 'nullable|string|max:255',
            'capacity'       => 'required|integer|min:1|max:10',
            'price'          => 'required|numeric|min:0',
            'view'           => 'nullable|string|max:500',
        ];
    }
}
