<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
        $id = $this->route('role')->id;
        $rules = Role::$rules;
        $rules['name'] = 'required|string|max:100|unique:roles,name,'.$id;

        return $rules;
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['name'] = htmlspecialchars($input['name']);

        $this->replace($input);
    }
}
