<?php

namespace App\Http\Requests;

use App\Models\ZoomMeeting;
use Illuminate\Foundation\Http\FormRequest;

class CreateMeetingRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return ZoomMeeting::$rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'agenda.required' => 'Description field is required.',
        ];
    }
}
