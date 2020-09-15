<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UtilisateurRequest extends FormRequest
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
        $rules = [
            'required',
            'email',
            'max:100',
        ];

        $rules[] = Rule::unique('users');

        return [
            'name' => 'required|string|max:60',
            'email' => $rules,
            'password' => 'required|string|max:60',
        ];
    }
}
