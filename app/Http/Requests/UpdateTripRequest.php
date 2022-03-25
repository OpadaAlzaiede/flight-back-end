<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
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
            'starts_at' => 'date',
            'arrives_at' => 'date',
            'governorate_id' => 'exists:governorates,id',
            'details' => 'string',
            'number_of_seats' => 'gt:3',
            'cost' => 'numeric',
            'destination' => 'string'
        ];
    }
}
