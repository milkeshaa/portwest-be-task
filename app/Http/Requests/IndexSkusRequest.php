<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @param int $per_page
 * @param int $page
 * @param string $sort
 * @param ?string $filter 
 */
class IndexSkusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'per_page' => [
                'sometimes',
                'integer',
                'min:0',
            ],
            'page' => [
                'sometimes',
                'integer',
                'min:1',
            ],
            'filter' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'sort' => [
                'sometimes',
                'string',
                Rule::in(['asc', 'desc']),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'per_page' => $this->input('per_page', 20),
            'page' => $this->input('page', 1),
            'filter' => $this->input('filter', null),
            'sort' => $this->input('sort', 'asc'),
        ]);
    }
}
