<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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

        if (! isset($this->route()->parameters()['article'])) {
            $rules[] = Rule::unique('articles');
        } else {
            $rules[] = Rule::unique('articles')->ignore($this->route()->parameters()['article']);
        }


        return [
            'titre' => $rules,
            'url' => 'required|max:150|string',
            'date' => 'required|max:150|date',
        ];
    }
}
