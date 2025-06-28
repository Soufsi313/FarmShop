<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('manage users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users')->ignore($userId)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in([User::ROLE_USER, User::ROLE_ADMIN])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.alpha_dash' => 'Le nom d\'utilisateur ne peut contenir que des lettres, chiffres, tirets et underscores.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
