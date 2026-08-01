<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Vraie image, formats sûrs, taille bornée (4 Mo) contre les abus.
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function messages()
    {
        return [
            'image.required' => __('room.image_err_required'),
            'image.image' => __('room.image_err_type'),
            'image.mimes' => __('room.image_err_type'),
            'image.max' => __('room.image_err_size'),
        ];
    }
}
