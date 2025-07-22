<?php

namespace App\Http\Requests;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
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
        return [
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:users,correo',
            'password' => 'required|min:8|confirmed',
            'rfc' => 'required|string|size:13',
            'tipo_persona' => 'required|in:Física,Moral',
        ];
    }

    /**
     * Get custom validation messages in Spanish
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe tener un formato válido.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.size' => 'El RFC debe tener exactamente 13 caracteres.',
            'tipo_persona.required' => 'El tipo de persona es obligatorio.',
            'tipo_persona.in' => 'El tipo de persona debe ser Física o Moral.',
        ];
    }

    /**
     * Configure the validator instance with custom RFC validation
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $rfc = strtoupper($this->rfc);
            if (User::where('rfc', $rfc)->exists()) {
                $validator->errors()->add('rfc', 'Ya existe un usuario registrado con este RFC.');
            }

            if (Proveedor::where('rfc', $rfc)->exists()) {
                $validator->errors()->add('rfc', 'Ya existe un proveedor registrado con este RFC.');
            }
        });
    }
}
