<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100|unique:types,name,'.($this->type ? $this->type->id : 'NULL'),
            'information' => 'nullable|string|max:1000',
            'base_price' => 'nullable|numeric|min:0|max:99999999',
            'capacity' => 'nullable|integer|min:1|max:20',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:50',
            'bed_type' => 'nullable|string|max:50',
            'bed_count' => 'nullable|integer|min:1|max:10',
            'size' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The room type name is required',
            'name.unique' => 'This room type name already exists',
            'capacity.max' => 'Capacity cannot exceed 20 persons',
            'base_price.max' => 'Price is too high',
        ];
    }

    // Préparer les données avant validation
    protected function prepareForValidation()
    {
        // Convertir amenities en array si c'est une chaîne
        if ($this->has('amenities') && is_string($this->amenities)) {
            $this->merge([
                'amenities' => json_decode($this->amenities, true) ?? [],
            ]);
        }
    }
}
