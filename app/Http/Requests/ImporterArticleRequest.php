<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImporterArticleRequest extends FormRequest
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
        return [
            'doi' => 'nullable|required_without:bibtex|string',
            'bibtex' => 'nullable|required_without:doi|string',
            'cite.*' => 'nullable|integer',
            'cite_par.*' => 'nullable|integer',
        ];
    }
}
