<?php

namespace App\Http\Requests;

use App\Traits\JSONErrors;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'father_name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'phone' => 'required',
            'role_id' => 'required|exists:roles,id',
            'id_photo' => 'file|mimes:png,jpg,jpeg|max:12000'
        ];
    }
}
