<?php

namespace App\Http\Requests\Backoffice;

use App\Enums\StoreRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string|\Illuminate\Contracts\Validation\ValidationRule>>
     */
    public function rules(): array
    {
        return [
            'role' => [
                'required',
                Rule::enum(StoreRole::class),
            ],
        ];
    }
}