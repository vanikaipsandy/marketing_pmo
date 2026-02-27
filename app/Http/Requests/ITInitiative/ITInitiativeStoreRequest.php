<?php

namespace App\Http\Requests\ITInitiative;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ITInitiativeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', Rule::unique('trs_projects', 'code')],
            'status' => ['required', 'integer', Rule::exists('trs_status_initiative', 'id')],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'initiative_ids' => ['nullable', 'array'],
            'initiative_ids.*' => [
                'integer',
                Rule::exists('mst_initiative', 'id')->where(static fn ($query) => $query->where('tipe_initiative', 2)),
            ],
        ];
    }
}
