<?php

namespace App\Http\Requests;

use InfyOm\Generator\Request\APIRequest;

/**
 * Class UpdateUserNotificationRequest
 */
class UpdateUserNotificationRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'is_subscribed' => 'required',
        ];

        return $rules;
    }
}
