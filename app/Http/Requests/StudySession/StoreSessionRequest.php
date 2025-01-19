<?php

namespace App\Http\Requests\StudySession;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'minutes' => ['integer'],
            'rest_minutes' => ['integer'],
            'name' => ['required', 'string'],
            'uri' => ['required', 'string', 'unique:study_sessions,uri']
        ];
    }
}
