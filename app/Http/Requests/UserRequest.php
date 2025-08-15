<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $userId = $this->route('user')?->id;
        
        $rules = [
            'nombre' => 'required|string|max:255',
            'correo' => [
                'required',
                'email',
                Rule::unique('users', 'correo')->ignore($userId)->whereNull('deleted_at')
            ],
            'rfc' => [
                'required',
                'string',
                'max:13',
                Rule::unique('users', 'rfc')->ignore($userId)->whereNull('deleted_at')
            ],
            'role_id' => 'nullable|exists:roles,id'
        ];

        // Agregar reglas de contraseña solo para creación o si se proporciona
        if ($this->isMethod('POST') || $this->filled('password')) {
            $rules['password'] = $this->isMethod('POST') 
                ? 'required|string|min:8|confirmed'
                : 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe tener un formato válido.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.string' => 'El RFC debe ser una cadena de texto.',
            'rfc.max' => 'El RFC no puede tener más de 13 caracteres.',
            'rfc.unique' => 'Este RFC ya está registrado.',
            
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            
            'role_id.exists' => 'El rol seleccionado no es válido.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'correo' => 'correo electrónico',
            'rfc' => 'RFC',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
            'role_id' => 'rol'
        ];
    }
} 