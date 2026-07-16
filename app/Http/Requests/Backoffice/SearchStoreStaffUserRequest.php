<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class SearchStoreStaffUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'search' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'search.required' => 'La recherche est obligatoire.',
            'search.string' => 'La recherche doit être un texte.',
            'search.min' => 'Saisissez au moins 2 caractères.',
            'search.max' => 'La recherche ne peut pas dépasser 100 caractères.',
        ];
    }
}