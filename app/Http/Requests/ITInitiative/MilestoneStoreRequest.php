<?php

namespace App\Http\Requests\ITInitiative;

use App\Models\Milestone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MilestoneStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'milestone_type' => $this->input('milestone_type') !== null
                ? (int) $this->input('milestone_type')
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'output' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:255'],
            'milestone_type' => ['nullable', 'integer', Rule::in(Milestone::roadmapTypeCodes())],
            'start_date' => ['nullable', 'date', 'required_with:end_date'],
            'end_date' => ['nullable', 'date', 'required_with:start_date', 'after_or_equal:start_date'],
        ];
    }
}
