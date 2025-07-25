<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->routeIs('register.secure')) {
            return [
                'nombre' => 'required|string|max:255',
                'tipo_persona' => 'required|in:Física,Moral',
                'rfc' => 'required|string|max:255',
                'curp' => 'nullable|string|max:255',
                'cp' => 'required|string|max:5',
                'direccion' => 'required|string|max:255',
            ];
        }

        return [
            'document' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'email' => 'required|email|max:255|unique:users,correo',
            'password' => 'required|string|min:8|confirmed',
            'qr_url' => 'nullable|string',
            'sat_rfc' => 'nullable|string|max:13',
            'sat_nombre' => 'nullable|string|max:255',
            'sat_tipo_persona' => 'nullable|string|in:Física,Moral',
            'sat_email' => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'document.mimes' => 'El documento debe ser un archivo PDF, PNG, JPG o JPEG.',
            'document.max' => 'El documento no debe superar los 5MB.',
            'sat_file.mimes' => 'La constancia del SAT debe ser un archivo PDF.',
            'sat_file.max' => 'La constancia del SAT no debe superar los 5MB.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'nombre.required' => 'El nombre es requerido',
            'tipo_persona.required' => 'El tipo de persona es requerido',
            'tipo_persona.in' => 'El tipo de persona debe ser Física o Moral',
            'rfc.required' => 'El RFC es requerido',
            'cp.required' => 'El código postal es requerido',
            'cp.max' => 'El código postal no debe exceder 5 caracteres',
            'direccion.required' => 'La dirección es requerida',
            'sat_email.email' => 'El correo del SAT debe ser un email válido.',
        ];
    }
}
