<?php

namespace App\Http\Requests;

use App\Traits\JSONErrors;
use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
{
    use JSONErrors;
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
            'starts_at' => 'required|date',
            'governorate_id' => 'required|exists:governorates,id',
            'details' => 'required|string',
            'number_of_seats' => 'required|gt:5',
            'estimated_time' => 'required',
            'car_plate' => 'required',
            'cost' => 'required|numeric',
        ];
    }
}
