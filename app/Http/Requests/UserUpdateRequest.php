<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $userId = $this->route('user'); // Assumes the route parameter is named 'user'

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId, // Exclude current user's email from uniqueness check
            'password' => 'nullable|confirmed|string|min:6|max:255', // Password is optional for updates
            'roles' => 'required|exists:roles,id', // Validate role_id
        ];
    }
}
