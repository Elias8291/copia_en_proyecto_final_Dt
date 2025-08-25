<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();
        
        return [
            'nombre' => 'required|string|max:255',
            'correo' => [
                'required',
                'email',
                Rule::unique('users', 'correo')->ignore($userId)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El correo debe tener un formato válido.',
            'correo.unique' => 'Ya existe un usuario con este correo.',
            
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            
            'password_confirmation.string' => 'La confirmación de contraseña debe ser una cadena de texto.',
            'password_confirmation.min' => 'La confirmación de contraseña debe tener al menos 8 caracteres.'
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'correo' => 'correo',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña'
        ];
    }
}
