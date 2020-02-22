<?php

namespace App\Http\Requests;

use App\Categorie;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategorieRequest extends FormRequest
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
            'max:100',
        ];

        if (! isset($this->route()->parameters()['category'])) {
            $rules[] = Rule::unique('categories');
        } else {
            $rules[] = Rule::unique('categories')->ignore($this->route()->parameters()['category']);
        }


        return [
            'nom' => $rules,
        ];
    }
}
