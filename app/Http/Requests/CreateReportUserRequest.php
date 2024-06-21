<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReportUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->sanitize();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'reported_to' => ['required', 'integer', 'exists:users,id'],
            'notes' => ['required', 'string'],
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['notes'] = isset($input['notes']) ? htmlspecialchars($input['notes']) : '';

        $this->replace($input);
    }
}
