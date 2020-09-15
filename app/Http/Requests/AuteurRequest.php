<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuteurRequest extends FormRequest
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
            'string',
            'max:300',
        ];

        if (! isset($this->route()->parameters()['auteur'])) {
            $rules[] = Rule::unique('auteurs');
        } else {
            $rules[] = Rule::unique('auteurs')->ignore($this->route()->parameters()['auteur']);
        }


        return [
            'nom' => $rules,
        ];
    }
}
