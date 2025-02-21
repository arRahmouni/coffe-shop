<?php

namespace app\Http\Requests\Api;

use Illuminate\Validation\Rules\Password;

class RegisterRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'username'      => ['required', 'string', 'min:5', 'max:255', 'regex:/^[a-z0-9_]+$/', 'unique:users,username'],
            'password'      => ['required', 'confirmed', Password::defaults()],
        ];

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
