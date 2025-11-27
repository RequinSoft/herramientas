<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonalEditRequest extends FormRequest
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
        return [
            //          
            'id' => 
            [                 
                'required',        
                Rule::unique('personal')->ignore($this->id),
            ],
            'nombre' => 
            [
                'required',
            ]
        ];
    }
    

    public function messages()
    {
        return
        [
            'id.required' => 'El ID es obligatorio',
            'id.unique' => 'ID Duplicado',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Nombre Duplicado',
        ];
    }
}
