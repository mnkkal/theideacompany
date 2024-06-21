<?php

namespace App\Http\Requests\API;

use App\Models\User;
use App\Rules\NoSpaceContaine;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'regex:/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:30', 'confirmed', new NoSpaceContaine()],
        ];
    }

    public function messages(): array
    {
        return User::$messages;
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['name'] = htmlspecialchars($input['name']);

        $this->replace($input);
    }
}
