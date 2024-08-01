<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
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
        $clientId = $this->route('client_id') ?? $this->input('client_id') ?? null;
        $isRegistration = $this->route('is_registration') ?? $this->input('is_registration') ?? null;

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_no' => 'string|min:7|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($clientId), # The system should accept unique emails
            ],
            'interest_ids' => 'array',
            'interest_ids.*' => 'exists:interests,id',
        ];

        $rules['password'] = $clientId ? 'nullable|string|min:6|confirmed' : 'required|string|min:6|confirmed';
        $rules['birthday'] = $isRegistration ? 'date' : 'required|date';

        return $rules;
    }
}
