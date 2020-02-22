<?php

namespace App\Http\Requests;

use App\Keyword;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class KeywordRequest extends FormRequest
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

        if (! isset($this->route()->parameters()['keyword'])) {
            $rules[] = Rule::unique('keywords');
        } else {
            $rules[] = Rule::unique('keywords')->ignore($this->route()->parameters()['keyword']);
        }


        return [
            'nom' => $rules,
        ];
    }
}
