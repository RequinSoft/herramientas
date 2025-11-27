<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserEditRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {

        return 
        [
            //            
            'user' => 
            [
                'required',               
                Rule::unique('users')->ignore($this->id)
            ],
            'name' => 
            [
                'required',
            ],
            'email' => 
            [
                'required',
                Rule::unique('users')->ignore($this->id)
            ]
        ];
    }

    public function messages()
    {
        return
        [
            'user.required' => 'El usuario es obligatorio',
            'user.unique' => 'Usuario Duplicado',
            'name.required' => 'El nombre es obligatorio',
            'name.unique' => 'Nombre Duplicado',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'El email ya está en uso',
        ];
    }
}
