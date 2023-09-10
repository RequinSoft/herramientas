<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupsEditRequest extends FormRequest
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
            'group' => 
            [
                'required',               
                Rule::unique('groups')->ignore($this->id)
            ]
        ];
    }

    public function messages()
    {
        return
        [
            'group.required' => 'El grupo es obligatorio',
            'group.unique' => 'Grupo Duplicado',
        ];
    }
}
