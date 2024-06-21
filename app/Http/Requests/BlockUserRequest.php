<?php

namespace App\Http\Requests;

/**
 * Class BlockUserRequest
 */
class BlockUserRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules['blocked_to'] = 'required';
        $rules['is_blocked'] = 'required';

        return $rules;
    }
}
